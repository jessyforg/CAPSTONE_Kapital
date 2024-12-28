<?php
ob_start();
session_start();
include('db_connection.php');

// Redirect if the user is not logged in or does not have the investor role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'investor') {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}
include('navbar.php');

$user_id = $_SESSION['user_id'];

// Fetch saved startups for the investor, ensuring the startup is approved
$saved_startups_query = "
    SELECT Startups.* 
    FROM Matches
    JOIN Startups ON Matches.startup_id = Startups.startup_id
    WHERE Matches.investor_id = '$user_id'
    AND Startups.approval_status = 'approved' 
    ORDER BY Matches.created_at DESC";
$saved_startups_result = mysqli_query($conn, $saved_startups_query);

// Fetch all startups by default (without filters)
$filter_conditions = "";
if (isset($_GET['industry']) && $_GET['industry'] != "") {
    $industry = mysqli_real_escape_string($conn, $_GET['industry']);
    $filter_conditions .= " AND Startups.industry LIKE '%$industry%'";
}
if (isset($_GET['location']) && $_GET['location'] != "") {
    $location = mysqli_real_escape_string($conn, $_GET['location']);
    $filter_conditions .= " AND Startups.location LIKE '%$location%'";
}
if (isset($_GET['funding_stage']) && $_GET['funding_stage'] != "") {
    $funding_stage = mysqli_real_escape_string($conn, $_GET['funding_stage']);
    $filter_conditions .= " AND Startups.funding_stage = '$funding_stage'";
}

// Query to fetch all startups that match the filters or no filters
$startups_query = "
    SELECT * 
    FROM Startups
    WHERE approval_status = 'approved' $filter_conditions
    ORDER BY created_at DESC";
$startups_result = mysqli_query($conn, $startups_query);

// Handle the match action (button click)
if (isset($_POST['match_startup_id'])) {
    $startup_id = mysqli_real_escape_string($conn, $_POST['match_startup_id']);

    // Check if this match already exists
    $check_match_query = "SELECT * FROM Matches WHERE investor_id = '$user_id' AND startup_id = '$startup_id'";
    $check_match_result = mysqli_query($conn, $check_match_query);

    if (mysqli_num_rows($check_match_result) == 0) {
        // Insert the match into the Matches table
        $insert_match_query = "
            INSERT INTO Matches (investor_id, startup_id, created_at) 
            VALUES ('$user_id', '$startup_id', NOW())";
        mysqli_query($conn, $insert_match_query);

        // Fetch the entrepreneur's user_id and email for the notification
        $entrepreneur_query = "
            SELECT Users.email, Users.user_id
            FROM Startups
            JOIN Users ON Startups.entrepreneur_id = Users.user_id
            WHERE Startups.startup_id = '$startup_id'";
        $entrepreneur_result = mysqli_query($conn, $entrepreneur_query);
        $entrepreneur = mysqli_fetch_assoc($entrepreneur_result);

        // Insert a notification for the entrepreneur
        $notification_message = "Your startup has been matched with an investor!";
        $insert_notification_query = "
            INSERT INTO Notifications (user_id, sender_id, type, message) 
            VALUES ('" . $entrepreneur['user_id'] . "', '$user_id', 'investment_match', '$notification_message')";
        mysqli_query($conn, $insert_notification_query);

        // Insert a notification for the investor
        $notification_message_investor = "You have successfully matched with the startup: " . htmlspecialchars($startup['name']);
        $insert_notification_investor_query = "
            INSERT INTO Notifications (user_id, sender_id, type, message) 
            VALUES ('$user_id', NULL, 'investment_match', '$notification_message_investor')";
        mysqli_query($conn, $insert_notification_investor_query);
    }

    // After the match is processed, redirect to avoid resubmission
    header("Location: investors.php");  // Redirect to the same page
    exit();  // Stop the script to avoid any further execution
}

// Handle unmatch action (delete match)
if (isset($_POST['unmatch_startup_id'])) {
    $startup_id = mysqli_real_escape_string($conn, $_POST['unmatch_startup_id']);

    // Delete the match from the Matches table
    $delete_match_query = "DELETE FROM Matches WHERE investor_id = '$user_id' AND startup_id = '$startup_id'";
    mysqli_query($conn, $delete_match_query);

    // After unmatch is processed, redirect to avoid resubmission
    header("Location: investors.php");  // Redirect to the same page
    exit();  // Stop the script to avoid any further execution
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investor Dashboard</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .investor-name {
            color: #17a2b8;
        }

        h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #17a2b8;
            margin-bottom: 20px;
            text-align: center;
        }

        .startup-post {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
            overflow: hidden;
        }

        .startup-post:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .startup-post h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 15px;
        }

        .startup-post p {
            font-size: 1rem;
            color: #555;
            margin: 5px 0;
        }

        .startup-post .btn {
            padding: 10px 15px;
            font-size: 1rem;
            border-radius: 5px;
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            /* Remove the underline */
        }

        .btn-info:hover {
            text-decoration: none;
            /* Ensure no underline on hover */
        }

        .btn-success {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-control {
            margin-bottom: 10px;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
        }

        .form-control[type="text"] {
            font-size: 1rem;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Welcome, <span class="investor-name">Investor!</span></h1>

        <h2>Matched Startups</h2>
        <?php if (mysqli_num_rows($saved_startups_result) > 0): ?>
            <?php while ($startup = mysqli_fetch_assoc($saved_startups_result)): ?>
                <div class="startup-post">
                    <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                    <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>

                    <!-- View Details Button -->
                    <a href="startup_detail.php?startup_id=<?php echo htmlspecialchars($startup['startup_id']); ?>"
                        class="btn btn-info">View Details</a>

                    <!-- Unmatch Button -->
                    <form method="POST" action="investors.php" style="display:inline;">
                        <input type="hidden" name="unmatch_startup_id"
                            value="<?php echo htmlspecialchars($startup['startup_id']); ?>">
                        <button type="submit" class="btn btn-danger">Unmatch</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No saved startups found or no approved startups available.</p>
        <?php endif; ?>

        <h2>Explore Startups</h2>
        <!-- Search & Filter form -->
        <form id="search-filter-form" method="GET" action="investors.php">
            <input type="text" name="industry" placeholder="Industry" class="form-control"
                value="<?php echo isset($_GET['industry']) ? htmlspecialchars($_GET['industry']) : ''; ?>">
            <input type="text" name="location" placeholder="Location" class="form-control"
                value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
            <select name="funding_stage" class="form-control">
                <option value="">Funding Stage</option>
                <option value="seed" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'seed' ? 'selected' : ''; ?>>Seed</option>
                <option value="series_a" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'series_a' ? 'selected' : ''; ?>>Series A</option>
                <option value="series_b" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'series_b' ? 'selected' : ''; ?>>Series B</option>
                <option value="series_c" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'series_c' ? 'selected' : ''; ?>>Series C</option>
                <option value="exit" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'exit' ? 'selected' : ''; ?>>Exit</option>
            </select>
            <button type="submit" class="btn btn-secondary">Apply Filters</button>
        </form>

        <?php if (mysqli_num_rows($startups_result) > 0): ?>
            <?php while ($startup = mysqli_fetch_assoc($startups_result)): ?>
                <div class="startup-post">
                    <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                    <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>

                    <!-- Match Button -->
                    <form method="POST" action="investors.php" style="display:inline;">
                        <input type="hidden" name="match_startup_id"
                            value="<?php echo htmlspecialchars($startup['startup_id']); ?>">
                        <button type="submit" class="btn btn-success">Match</button>
                    </form>

                    <!-- View Details Button -->
                    <a href="startup_detail.php?startup_id=<?php echo htmlspecialchars($startup['startup_id']); ?>"
                        class="btn btn-info">View Details</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No approved startups found with the current filter.</p>
        <?php endif; ?>
    </div>

</body>

</html>