<?php
session_start();
include('navbar.php'); // Include the navbar
include('db_connection.php'); // Include database connection

// Check if `startup_id` is passed in the query string
if (!isset($_GET['startup_id'])) {
    die("Startup ID is not provided.");
}

$startup_id = $_GET['startup_id'];

// Fetch startup details from the database
$query_startup = "SELECT s.*, u.name as entrepreneur_name, u.public_email as entrepreneur_email, 
                         u.contact_number as entrepreneur_contact 
                  FROM Startups s 
                  JOIN Users u ON s.entrepreneur_id = u.user_id 
                  WHERE s.startup_id = ?";
$stmt = $conn->prepare($query_startup);
$stmt->bind_param("i", $startup_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $startup = $result->fetch_assoc();
} else {
    die("Startup not found.");
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You need to log in to access this page.");
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Handle the match action (button click)
if (isset($_POST['match_startup_id'])) {
    $startup_id = mysqli_real_escape_string($conn, $_POST['match_startup_id']);

    // Check if this match already exists
    $check_match_query = "SELECT * FROM Matches WHERE investor_id = '$user_id' AND startup_id = '$startup_id'";
    $check_match_result = mysqli_query($conn, $check_match_query);

    if (mysqli_num_rows($check_match_result) == 0) {
        // Insert the match into the Matches table
        $insert_match_query = "INSERT INTO Matches (investor_id, startup_id, created_at) VALUES ('$user_id', '$startup_id', NOW())";
        mysqli_query($conn, $insert_match_query);
    }

    // Redirect to the same page with a GET request
    header("Location: startup_detail.php?startup_id=$startup_id");
    exit();
}

// Handle the unmatch action (button click)
if (isset($_POST['unmatch_startup_id'])) {
    $startup_id = mysqli_real_escape_string($conn, $_POST['unmatch_startup_id']);

    // Delete the match from the Matches table
    $delete_match_query = "DELETE FROM Matches WHERE investor_id = '$user_id' AND startup_id = '$startup_id'";
    mysqli_query($conn, $delete_match_query);

    // Redirect to the same page with a GET request
    header("Location: startup_detail.php?startup_id=$startup_id");
    exit();
}

// Check if the investor has already matched with this startup
$is_matched = false;
if ($user_role === 'investor') {
    $check_match_query = "SELECT * FROM Matches WHERE investor_id = ? AND startup_id = ?";
    $match_stmt = $conn->prepare($check_match_query);
    $match_stmt->bind_param("ii", $user_id, $startup_id);
    $match_stmt->execute();
    $match_result = $match_stmt->get_result();
    $is_matched = $match_result->num_rows > 0;
}

// Fetch admin and owner details (for entrepreneurs only)
$admin_details = null;
$is_owner = false;
if ($user_role === 'entrepreneur') {
    // Fetch admin details from the Users table
    $admin_query = "SELECT u.name FROM Users u INNER JOIN Startups s ON u.user_id = s.approved_by WHERE s.startup_id = ?";
    $admin_stmt = $conn->prepare($admin_query);
    $admin_stmt->bind_param("i", $startup_id);
    $admin_stmt->execute();
    $admin_result = $admin_stmt->get_result();
    if ($admin_result->num_rows > 0) {
        $admin_details = $admin_result->fetch_assoc();
    }

    // Check if the logged-in entrepreneur owns this startup
    $owner_query = "SELECT * FROM Startups WHERE startup_id = ? AND entrepreneur_id = ?";
    $owner_stmt = $conn->prepare($owner_query);
    $owner_stmt->bind_param("ii", $startup_id, $user_id);
    $owner_stmt->execute();
    $owner_result = $owner_stmt->get_result();
    $is_owner = $owner_result->num_rows > 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($startup['name']); ?> - Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #2C2F33;
            color: #f9f9f9;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: #23272A;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: 1px solid #40444B;
        }

        h1 {
            color: #7289DA;
            margin: 0 0 25px 0;
            font-size: 2em;
            font-weight: 600;
            text-align: center;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .details {
            display: grid;
            gap: 20px;
        }

        .detail-item {
            padding: 15px;
            background: rgba(114, 137, 218, 0.1);
            border-radius: 8px;
            border: 1px solid #40444B;
        }

        .detail-item strong {
            color: #7289DA;
            font-weight: 500;
            display: block;
            margin-bottom: 5px;
            font-size: 0.95em;
        }

        .detail-item p {
            margin: 0;
            color: #B9BBBE;
            font-size: 1em;
            line-height: 1.5;
        }

        .details a {
            color: #00b0f4;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .details a:hover {
            color: #7289DA;
            text-decoration: underline;
        }

        .funding-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: 500;
            background-color: #7289DA;
            color: white;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        button {
            flex: 1;
            min-width: 120px;
            background: #7289DA;
            color: #fff;
            font-size: 0.95em;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Poppins', sans-serif;
        }

        button:hover {
            background: #5b6eae;
            transform: translateY(-2px);
        }

        button.btn-match {
            background: #43B581;
        }

        button.btn-match:hover {
            background: #3ca374;
        }

        button.btn-unmatch {
            background: #f04747;
        }

        button.btn-unmatch:hover {
            background: #d84040;
        }

        button.btn-edit {
            background: #faa61a;
        }

        button.btn-edit:hover {
            background: #e59816;
        }

        button.btn-back {
            background: #747f8d;
        }

        button.btn-back:hover {
            background: #68727f;
        }

        .error {
            color: #f04747;
            background: rgba(240, 71, 71, 0.1);
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 20px;
            }

            .button-group {
                flex-direction: column;
            }

            button {
                width: 100%;
            }

            h1 {
                font-size: 1.6em;
                margin-bottom: 20px;
            }
        }

        .startup-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
            text-align: center;
        }

        .startup-header h1 {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php include('default_startup_logo.php'); ?>
        <div class="startup-header">
            <?php echo getStartupLogo($startup['logo_url'], 'large'); ?>
            <h1><?php echo htmlspecialchars($startup['name']); ?></h1>
        </div>
        <div class="details">
            <div class="detail-item">
                <strong>Industry</strong>
                <p><?php echo htmlspecialchars($startup['industry']); ?></p>
            </div>

            <div class="detail-item">
                <strong>Entrepreneur</strong>
                <p><?php echo htmlspecialchars($startup['entrepreneur_name']); ?></p>
                <?php if ($startup['approval_status'] === 'approved'): ?>
                    <?php if ($startup['entrepreneur_email']): ?>
                        <p><i class="fas fa-envelope"></i> <a href="mailto:<?php echo htmlspecialchars($startup['entrepreneur_email']); ?>"><?php echo htmlspecialchars($startup['entrepreneur_email']); ?></a></p>
                    <?php endif; ?>
                    <?php if ($startup['entrepreneur_contact']): ?>
                        <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($startup['entrepreneur_contact']); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="detail-item">
                <strong>Funding Stage</strong>
                <p><span class="funding-badge"><?php 
                    if (isset($startup['funding_stage']) && !is_null($startup['funding_stage'])) {
                        $funding_stage = strtolower($startup['funding_stage']);
                        $formatted_stage = str_replace('_', ' ', $funding_stage);
                        echo htmlspecialchars(ucwords($formatted_stage));
                    } else {
                        echo 'Not Specified';
                    }
                ?></span></p>
            </div>

            <div class="detail-item">
                <strong>Description</strong>
                <p><?php echo nl2br(htmlspecialchars($startup['description'])); ?></p>
            </div>

            <div class="detail-item">
                <strong>Location</strong>
                <p><?php echo htmlspecialchars($startup['location']); ?></p>
            </div>

            <div class="detail-item">
                <strong>Website</strong>
                <p>
                    <?php if (!empty($startup['website'])): ?>
                        <a href="<?php echo htmlspecialchars($startup['website']); ?>" target="_blank">
                            <i class="fas fa-external-link-alt"></i> <?php echo htmlspecialchars($startup['website']); ?>
                        </a>
                    <?php else: ?>
                        <span>Not provided</span>
                    <?php endif; ?>
                </p>
            </div>

            <div class="detail-item">
                <strong>Documents</strong>
                <p>
                    <?php if (!empty($startup['pitch_deck_url'])): ?>
                        <a href="<?php echo htmlspecialchars($startup['pitch_deck_url']); ?>" target="_blank">
                            <i class="fas fa-file-powerpoint"></i> View Pitch Deck
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($startup['business_plan_url'])): ?>
                        <?php if (!empty($startup['pitch_deck_url'])) echo ' • '; ?>
                        <a href="<?php echo htmlspecialchars($startup['business_plan_url']); ?>" target="_blank">
                            <i class="fas fa-file-pdf"></i> View Business Plan
                        </a>
                    <?php endif; ?>
                    <?php if (empty($startup['pitch_deck_url']) && empty($startup['business_plan_url'])): ?>
                        <span>No documents provided</span>
                    <?php endif; ?>
                </p>
            </div>

            <?php if ($admin_details): ?>
            <div class="detail-item">
                <strong>Approved By</strong>
                <p><?php echo htmlspecialchars($admin_details['name']); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <div class="button-group">
            <?php if ($user_role === 'investor'): ?>
                <?php if ($is_matched): ?>
                    <form method="POST" action="startup_detail.php?startup_id=<?php echo htmlspecialchars($startup_id); ?>" style="flex: 1;">
                        <input type="hidden" name="unmatch_startup_id" value="<?php echo htmlspecialchars($startup_id); ?>">
                        <button type="submit" class="btn-unmatch"><i class="fas fa-times"></i> Unmatch</button>
                    </form>
                <?php else: ?>
                    <form method="POST" action="startup_detail.php?startup_id=<?php echo htmlspecialchars($startup_id); ?>" style="flex: 1;">
                        <input type="hidden" name="match_startup_id" value="<?php echo htmlspecialchars($startup_id); ?>">
                        <button type="submit" class="btn-match"><i class="fas fa-check"></i> Match</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($is_owner): ?>
                <form method="GET" action="edit_startup.php" style="flex: 1;">
                    <input type="hidden" name="startup_id" value="<?php echo htmlspecialchars($startup_id); ?>">
                    <button type="submit" class="btn-edit"><i class="fas fa-edit"></i> Edit Startup</button>
                </form>
            <?php endif; ?>

            <button onclick="window.location.href='<?php 
                if ($user_role === 'entrepreneur') {
                    echo 'entrepreneurs.php';
                } elseif ($user_role === 'investor') {
                    echo 'investors.php';
                } elseif ($user_role === 'admin') {
                    echo 'admin-panel.php';
                } else {
                    echo 'index.php';
                }
            ?>'" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </button>
        </div>
    </div>
</body>

</html>
