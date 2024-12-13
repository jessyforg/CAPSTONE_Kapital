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
$query_startup = "SELECT * FROM Startups WHERE startup_id = ?";
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
    // Fetch admin details
    $admin_query = "SELECT a.admin_name FROM Admins a INNER JOIN Startups s ON a.admin_id = s.admin_id WHERE s.startup_id = ?";
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
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(45deg, #343131, #808080);
            color: #fff;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #f4f4f4;
        }

        .details {
            line-height: 1.8;
            margin-top: 20px;
        }

        .details strong {
            color: #ddd;
        }

        .details a {
            color: #D8A25E;
            text-decoration: none;
        }

        .details a:hover {
            text-decoration: underline;
        }

        button {
            background: #D8A25E;
            color: #fff;
            font-size: 16px;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            border: none;
            margin-top: 20px;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #D8A25E;
        }

        .error {
            color: #f44336;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($startup['name']); ?></h1>
        <div class="details">
            <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
            <p><strong>Funding Stage:</strong> <?php echo htmlspecialchars(ucwords($startup['funding_stage'])); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
            <p><strong>Website:</strong>
                <?php if (!empty($startup['website'])): ?>
                    <a href="<?php echo htmlspecialchars($startup['website']); ?>"
                        target="_blank"><?php echo htmlspecialchars($startup['website']); ?></a>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </p>
            <p><strong>Pitch Deck:</strong>
                <?php if (!empty($startup['pitch_deck_url'])): ?>
                    <a href="<?php echo htmlspecialchars($startup['pitch_deck_url']); ?>" target="_blank">View Pitch
                        Deck</a>
                <?php else: ?>
                    Not Provided
                <?php endif; ?>
            </p>
            <p><strong>Business Plan:</strong>
                <?php if (!empty($startup['business_plan_url'])): ?>
                    <a href="<?php echo htmlspecialchars($startup['business_plan_url']); ?>" target="_blank">View Business
                        Plan</a>
                <?php else: ?>
                    Not Provided
                <?php endif; ?>
            </p>
            <?php if ($admin_details): ?>
                <p><strong>Approved By Admin:</strong> <?php echo htmlspecialchars($admin_details['admin_name']); ?></p>
            <?php endif; ?>
        </div>

        <!-- Match/Unmatch Button for Investors -->
        <?php if ($user_role === 'investor'): ?>
            <?php if ($is_matched): ?>
                <form method="POST" action="startup_detail.php?startup_id=<?php echo htmlspecialchars($startup_id); ?>">
                    <input type="hidden" name="unmatch_startup_id" value="<?php echo htmlspecialchars($startup_id); ?>">
                    <button type="submit">Unmatch</button>
                </form>
            <?php else: ?>
                <form method="POST" action="startup_detail.php?startup_id=<?php echo htmlspecialchars($startup_id); ?>">
                    <input type="hidden" name="match_startup_id" value="<?php echo htmlspecialchars($startup_id); ?>">
                    <button type="submit">Match</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Edit Startup Button for Entrepreneurs -->
        <?php if ($is_owner): ?>
            <form method="GET" action="edit_startup.php">
                <input type="hidden" name="startup_id" value="<?php echo htmlspecialchars($startup_id); ?>">
                <button type="submit">Edit Startup</button>
            </form>
        <?php endif; ?>

        <button onclick="window.location.href='<?php echo ($user_role === 'entrepreneur') ? 'entrepreneurs.php' : 'investors.php'; ?>';">Back to Dashboard</button>
    </div>
</body>

</html>
