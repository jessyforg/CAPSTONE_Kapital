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
    WHERE approval_status = 'approved' 
    AND startup_id NOT IN (
        SELECT startup_id 
        FROM Matches 
        WHERE investor_id = '$user_id'
    )
    $filter_conditions
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

        // Get the last inserted match_id
        $match_id = mysqli_insert_id($conn); // Get the match_id from the Matches table

        // Fetch the entrepreneur's user_id and email for the notification
        $entrepreneur_query = "
            SELECT Users.email, Users.user_id
            FROM Startups
            JOIN Users ON Startups.entrepreneur_id = Users.user_id
            WHERE Startups.startup_id = '$startup_id'";
        $entrepreneur_result = mysqli_query($conn, $entrepreneur_query);
        $entrepreneur = mysqli_fetch_assoc($entrepreneur_result);

        // Insert the notification for the entrepreneur
        $notification_message = "Your startup has been matched with an investor!";
        $notification_url = "match_details.php?match_id=$match_id"; // Use the match_id here
        $insert_notification_query = "
            INSERT INTO Notifications (user_id, sender_id, type, message, url, match_id) 
            VALUES ('" . $entrepreneur['user_id'] . "', '$user_id', 'investment_match', '$notification_message', '$notification_url', '$match_id')";
        mysqli_query($conn, $insert_notification_query);

        // Fetch startup details for the investor notification
        $startup_query = "SELECT name FROM Startups WHERE startup_id = '$startup_id'";
        $startup_result = mysqli_query($conn, $startup_query);
        $startup = mysqli_fetch_assoc($startup_result);

        // Insert the notification for the investor
        $notification_message_investor = "You have successfully matched with the startup: " . htmlspecialchars($startup['name']);
        $insert_notification_investor_query = "
            INSERT INTO Notifications (user_id, sender_id, type, message, match_id) 
            VALUES ('$user_id', NULL, 'investment_match', '$notification_message_investor', '$match_id')";
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
        }

        .btn-info:hover {
            text-decoration: none;
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

        .startup-header {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .startup-logo {
            width: 100px;
            height: 100px;
            flex-shrink: 0;
            border-radius: 8px;
            overflow: hidden;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .startup-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .startup-logo i {
            font-size: 40px;
            color: #17a2b8;
        }

        .startup-info {
            flex-grow: 1;
        }

        .startup-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .startup-post {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
        }

        .startup-post:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .startup-post h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
            margin-top: 0;
        }

        .startup-post p {
            font-size: 1rem;
            color: #555;
            margin: 5px 0;
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            .startup-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .startup-logo {
                width: 80px;
                height: 80px;
            }

            .startup-actions {
                justify-content: center;
            }
        }

        .search-section {
            background: #23272A;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 1px solid #40444B;
        }

        .search-section h2 {
            color: #7289DA;
            font-size: 1.5rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #2C2F33;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #40444B;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group:hover {
            border-color: #7289DA;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-group label {
            color: #B9BBBE;
            font-size: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            color: #7289DA;
            font-size: 1.1rem;
        }

        .form-control {
            background: #23272A;
            border: 1px solid #40444B;
            color: #FFFFFF;
            padding: 14px;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        .form-control:focus {
            border-color: #7289DA;
            outline: none;
            box-shadow: 0 0 0 2px rgba(114, 137, 218, 0.2);
            background: #2C2F33;
        }

        .form-control::placeholder {
            color: #72767D;
        }

        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%237289DA' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: calc(100% - 15px) center;
            padding-right: 40px;
        }

        select.form-control:hover {
            border-color: #7289DA;
        }

        .search-button {
            background: #7289DA;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-button:hover {
            background: #5b6eae;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .search-button i {
            font-size: 1.1rem;
        }

        .clear-filters {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #B9BBBE;
            text-decoration: none;
            margin-left: 20px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 6px;
        }

        .clear-filters:hover {
            color: #FFFFFF;
            background: rgba(114, 137, 218, 0.1);
        }

        .clear-filters i {
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .search-section {
                padding: 20px;
                margin: 15px;
            }

            .search-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-group {
                padding: 12px;
            }

            .search-button {
                width: 100%;
                justify-content: center;
                padding: 12px;
            }

            .clear-filters {
                display: flex;
                justify-content: center;
                margin: 15px 0 0 0;
                width: 100%;
                padding: 12px;
                background: rgba(114, 137, 218, 0.05);
            }
        }

        /* Add a universal box-sizing rule */
        *, *:before, *:after {
            box-sizing: border-box;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Welcome, <span class="investor-name">Investor!</span></h1>

        <div class="search-section">
            <h2><i class="fas fa-search"></i> Search & Filter Startups</h2>
            <form id="search-filter-form" method="GET" action="investors.php">
                <div class="search-grid">
                    <div class="form-group">
                        <label for="industry"><i class="fas fa-industry"></i> Industry</label>
                        <input type="text" id="industry" name="industry" placeholder="Enter industry type" class="form-control"
                            value="<?php echo isset($_GET['industry']) ? htmlspecialchars($_GET['industry']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="location"><i class="fas fa-map-marker-alt"></i> Location</label>
                        <input type="text" id="location" name="location" placeholder="Enter location" class="form-control"
                            value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="funding_stage"><i class="fas fa-chart-line"></i> Funding Stage</label>
                        <select id="funding_stage" name="funding_stage" class="form-control">
                            <option value="">All Stages</option>
                            <option value="seed" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'seed' ? 'selected' : ''; ?>>Seed</option>
                            <option value="series_a" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'series_a' ? 'selected' : ''; ?>>Series A</option>
                            <option value="series_b" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'series_b' ? 'selected' : ''; ?>>Series B</option>
                            <option value="series_c" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'series_c' ? 'selected' : ''; ?>>Series C</option>
                            <option value="exit" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'exit' ? 'selected' : ''; ?>>Exit</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="search-button">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>

                <?php if (isset($_GET['industry']) || isset($_GET['location']) || isset($_GET['funding_stage'])): ?>
                    <a href="investors.php" class="clear-filters">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <h2>Matched Startups</h2>
        <?php if (mysqli_num_rows($saved_startups_result) > 0): ?>
            <?php while ($startup = mysqli_fetch_assoc($saved_startups_result)): ?>
                <div class="startup-post">
                    <div class="startup-header">
                        <div class="startup-logo">
                            <?php if (!empty($startup['logo_url']) && file_exists($startup['logo_url'])): ?>
                                <img src="<?php echo htmlspecialchars($startup['logo_url']); ?>" alt="<?php echo htmlspecialchars($startup['name']); ?> logo">
                            <?php else: ?>
                                <i class="fas fa-building"></i>
                            <?php endif; ?>
                        </div>
                        <div class="startup-info">
                            <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                            <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>
                        </div>
                    </div>

                    <div class="startup-actions">
                        <a href="startup_detail.php?startup_id=<?php echo htmlspecialchars($startup['startup_id']); ?>" class="btn btn-info">View Details</a>
                        <form method="POST" action="investors.php" style="display:inline;">
                            <input type="hidden" name="unmatch_startup_id" value="<?php echo htmlspecialchars($startup['startup_id']); ?>">
                            <button type="submit" class="btn btn-danger">Unmatch</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No saved startups found or no approved startups available.</p>
        <?php endif; ?>

        <h2>Explore Startups</h2>
        <?php if (mysqli_num_rows($startups_result) > 0): ?>
            <?php while ($startup = mysqli_fetch_assoc($startups_result)): ?>
                <div class="startup-post">
                    <div class="startup-header">
                        <div class="startup-logo">
                            <?php if (!empty($startup['logo_url']) && file_exists($startup['logo_url'])): ?>
                                <img src="<?php echo htmlspecialchars($startup['logo_url']); ?>" alt="<?php echo htmlspecialchars($startup['name']); ?> logo">
                            <?php else: ?>
                                <i class="fas fa-building"></i>
                            <?php endif; ?>
                        </div>
                        <div class="startup-info">
                            <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                            <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>
                        </div>
                    </div>

                    <div class="startup-actions">
                        <form method="POST" action="investors.php" style="display:inline;">
                            <input type="hidden" name="match_startup_id" value="<?php echo htmlspecialchars($startup['startup_id']); ?>">
                            <button type="submit" class="btn btn-success">Match</button>
                        </form>
                        <a href="startup_detail.php?startup_id=<?php echo htmlspecialchars($startup['startup_id']); ?>" class="btn btn-info">View Details</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No approved startups found with the current filter.</p>
        <?php endif; ?>
    </div>

</body>

</html>
