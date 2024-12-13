<?php
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

<div class="container">
    <h1>Welcome, <span class="investor-name">Investor!</span></h1>

    <h2>Saved Startups</h2>
    <?php if (mysqli_num_rows($saved_startups_result) > 0): ?>
        <?php while ($startup = mysqli_fetch_assoc($saved_startups_result)): ?>
            <div class="startup-post">
                <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>
                
                <!-- View Details Button -->
                <a href="startup_detail.php?startup_id=<?php echo htmlspecialchars($startup['startup_id']); ?>" class="btn btn-info">View Details</a>

                <!-- Unmatch Button -->
                <form method="POST" action="investors.php" style="display:inline;">
                    <input type="hidden" name="unmatch_startup_id" value="<?php echo htmlspecialchars($startup['startup_id']); ?>">
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
        <input type="text" name="industry" placeholder="Industry" class="form-control" value="<?php echo isset($_GET['industry']) ? htmlspecialchars($_GET['industry']) : ''; ?>">
        <input type="text" name="location" placeholder="Location" class="form-control" value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
        <select name="funding_stage" class="form-control">
            <option value="">Funding Stage</option>
            <option value="seed" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'seed' ? 'selected' : ''; ?>>Seed</option>
            <option value="series_a" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'series_a' ? 'selected' : ''; ?>>Series A</option>
            <option value="series_b" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'series_b' ? 'selected' : ''; ?>>Series B</option>
            <option value="series_c" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'series_c' ? 'selected' : ''; ?>>Series C</option>
            <option value="exit" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'exit' ? 'selected' : ''; ?>>Exit</option>
        </select>
        <button type="submit" class="btn btn-secondary">Search Startups</button>
    </form>

    <!-- Displaying filtered startups -->
    <h3>Startups</h3>
    <?php if (mysqli_num_rows($startups_result) > 0): ?>
        <?php while ($startup = mysqli_fetch_assoc($startups_result)): ?>
            <div class="startup-post">
                <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>
                
                <!-- View Details Button -->
                <a href="startup_detail.php?startup_id=<?php echo htmlspecialchars($startup['startup_id']); ?>" class="btn btn-info">View Details</a>

                <!-- Check if the startup is already matched -->
                <?php 
                $startup_id = $startup['startup_id'];
                $check_match_query = "SELECT * FROM Matches WHERE investor_id = '$user_id' AND startup_id = '$startup_id'";
                $check_match_result = mysqli_query($conn, $check_match_query);
                if (mysqli_num_rows($check_match_result) == 0): ?>
                    <!-- Match Button -->
                    <form method="POST" action="investors.php" style="display:inline;">
                        <input type="hidden" name="match_startup_id" value="<?php echo htmlspecialchars($startup['startup_id']); ?>">
                        <button type="submit" class="btn btn-success">Match</button>
                    </form>
                <?php else: ?>
                    <!-- Already Matched, no Match Button -->
                    <p>Already Matched</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No startups found matching your filters.</p>
    <?php endif; ?>
</div>

<!-- Embedded CSS (No Changes) -->
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
        margin-top: 15px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
    }

    .startup-post .btn-info {
        background-color: #17a2b8;
        color: white;
    }

    .startup-post .btn-info:hover {
        background-color: #138496;
    }

    .startup-post .btn-success {
        background-color: #28a745;
        color: white;
    }

    .startup-post .btn-success:hover {
        background-color: #218838;
    }

    .startup-post .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .startup-post .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .form-control {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>
