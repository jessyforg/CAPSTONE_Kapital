<?php
session_start();
include('navbar.php');
include('db_connection.php');

// Redirect if the user is not logged in or does not have the entrepreneur role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

$user_id = $_SESSION['user_id'];

// Retrieve the entrepreneur details
$query = "SELECT * FROM Entrepreneurs WHERE entrepreneur_id = '$user_id'";
$result = mysqli_query($conn, $query);
$entrepreneur = mysqli_fetch_assoc($result);

// Fetch startups posted by the entrepreneur and others
$startups_query = "
    SELECT * FROM Startups 
    WHERE entrepreneur_id = '$user_id' 
    OR entrepreneur_id IN (SELECT entrepreneur_id FROM Startups)
    ORDER BY created_at DESC";
$startups_result = mysqli_query($conn, $startups_query);
?>

<!-- Display the entrepreneur's dashboard -->
<div class="container">
    <h1>Welcome, <span
            class="entrepreneur-name"><?php echo isset($entrepreneur['name']) ? $entrepreneur['name'] : 'Entrepreneur'; ?></span>!
    </h1>

    <a href="create_startup.php" class="btn btn-secondary">Create New Startup</a>

    <!-- Link to post job (only visible to entrepreneurs) -->
    <a href="post-job.php" class="btn btn-primary">Post a Job</a>

    <h2>News Feed</h2>
    <?php
    while ($startup = mysqli_fetch_assoc($startups_result)):
        // Check if the current user is the one who posted the startup
        $is_entrepreneur_post = $startup['entrepreneur_id'] == $user_id;
        ?>
        <div class="startup-post">
            <h3><?php echo isset($startup['name']) ? $startup['name'] : 'Startup Name'; ?></h3>
            <p><strong>Industry:</strong> <?php echo isset($startup['industry']) ? $startup['industry'] : 'Not Provided'; ?>
            </p>
            <p><strong>Funding Stage:</strong>
                <?php echo isset($startup['funding_stage']) ? $startup['funding_stage'] : 'Not Provided'; ?></p>
            <p><strong>Description:</strong>
                <?php echo isset($startup['description']) ? $startup['description'] : 'No description provided'; ?></p>

            <!-- Show the edit button only if the logged-in entrepreneur posted the startup -->
            <?php if ($is_entrepreneur_post): ?>
                <a href="edit_startup.php?startup_id=<?php echo $startup['startup_id']; ?>" class="btn btn-warning">Edit
                    Startup</a>
            <?php endif; ?>

            <!-- Show the view details button for everyone -->
            <a href="startup_detail.php?startup_id=<?php echo $startup['startup_id']; ?>" class="btn btn-info">View
                Details</a>
        </div>
    <?php endwhile; ?>
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

    .entrepreneur-name {
        color: #D8A25E;
        /* Updated color for Entrepreneur's name */
    }

    h2 {
        font-size: 2rem;
        font-weight: bold;
        color: #D8A25E;
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

    .startup-post .btn-info:focus {
        outline: none;
    }

    .startup-post .btn-warning {
        background-color: #ffc107;
        color: white;
    }

    .startup-post .btn-warning:hover {
        background-color: #e0a800;
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

    .btn-primary {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>