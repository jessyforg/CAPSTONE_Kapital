<?php
session_start();
include('navbar.php');
include('db_connection.php');

// Redirect if the user is not logged in or does not have the investor role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'investor') {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

$user_id = $_SESSION['user_id'];

// Fetch saved startups for the investor
$saved_startups_query = "
    SELECT Startups.*
    FROM Matches
    JOIN Startups ON Matches.startup_id = Startups.startup_id
    WHERE Matches.investor_id = '$user_id'
    ORDER BY Matches.created_at DESC";
$saved_startups_result = mysqli_query($conn, $saved_startups_query);
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
                <a href="<?php echo htmlspecialchars($startup['pitch_deck_url']); ?>" class="btn btn-info" target="_blank">View
                    Pitch Deck</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No saved startups found. Start exploring startups to save them here.</p>
    <?php endif; ?>

    <h2>Explore Startups</h2>
    <!-- Search & Filter form -->
    <form id="search-filter-form" method="GET" action="explore_startups.php">
        <input type="text" name="industry" placeholder="Industry" class="form-control">
        <input type="text" name="location" placeholder="Location" class="form-control">
        <select name="funding_stage" class="form-control">
            <option value="">Funding Stage</option>
            <option value="seed">Seed</option>
            <option value="series_a">Series A</option>
            <option value="series_b">Series B</option>
            <option value="series_c">Series C</option>
            <option value="exit">Exit</option>
        </select>
        <button type="submit" class="btn btn-secondary">Search Startups</button>
    </form>
</div>

<!-- Embedded CSS -->
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