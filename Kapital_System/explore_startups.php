<?php
session_start();
include('navbar.php');
include('db_connection.php');

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

$user_id = $_SESSION['user_id'];

// Fetch featured startups
$featured_query = "SELECT * FROM Startups ORDER BY created_at DESC LIMIT 5";
$featured_result = mysqli_query($conn, $featured_query);

// Apply filters if the form is submitted
$where_clauses = [];
if (!empty($_GET['industry'])) {
    $industry = mysqli_real_escape_string($conn, $_GET['industry']);
    $where_clauses[] = "industry LIKE '%$industry%'";
}
if (!empty($_GET['location'])) {
    $location = mysqli_real_escape_string($conn, $_GET['location']);
    $where_clauses[] = "location LIKE '%$location%'";
}
if (!empty($_GET['funding_stage'])) {
    $funding_stage = mysqli_real_escape_string($conn, $_GET['funding_stage']);
    $where_clauses[] = "funding_stage = '$funding_stage'";
}

// Combine filters into a WHERE clause
$where_clause = '';
if (count($where_clauses) > 0) {
    $where_clause = 'WHERE ' . implode(' AND ', $where_clauses);
}

// Fetch startups based on filters
$startups_query = "SELECT * FROM Startups $where_clause ORDER BY created_at DESC";
$startups_result = mysqli_query($conn, $startups_query);
?>

<div class="container">
    <h1>Explore Startups</h1>

    <!-- Search & Filter Form -->
    <form method="GET" action="explore_startups.php" class="filter-form">
        <input type="text" name="industry" placeholder="Industry" class="form-control"
            value="<?php echo $_GET['industry'] ?? ''; ?>">
        <input type="text" name="location" placeholder="Location" class="form-control"
            value="<?php echo $_GET['location'] ?? ''; ?>">
        <select name="funding_stage" class="form-control">
            <option value="">Funding Stage</option>
            <option value="seed" <?php echo (isset($_GET['funding_stage']) && $_GET['funding_stage'] === 'seed') ? 'selected' : ''; ?>>Seed</option>
            <option value="series_a" <?php echo (isset($_GET['funding_stage']) && $_GET['funding_stage'] === 'series_a') ? 'selected' : ''; ?>>Series A</option>
            <option value="series_b" <?php echo (isset($_GET['funding_stage']) && $_GET['funding_stage'] === 'series_b') ? 'selected' : ''; ?>>Series B</option>
            <option value="series_c" <?php echo (isset($_GET['funding_stage']) && $_GET['funding_stage'] === 'series_c') ? 'selected' : ''; ?>>Series C</option>
            <option value="exit" <?php echo (isset($_GET['funding_stage']) && $_GET['funding_stage'] === 'exit') ? 'selected' : ''; ?>>Exit</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
    </form>

    <!-- Featured Startups Section -->
    <h2>Featured Startups</h2>
    <div class="featured-startups">
        <?php if (mysqli_num_rows($featured_result) > 0): ?>
            <?php while ($startup = mysqli_fetch_assoc($featured_result)): ?>
                <div class="startup-card">
                    <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                    <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
                    <p><?php echo htmlspecialchars($startup['description']); ?></p>
                    <a href="startup_detail.php?startup_id=<?php echo $startup['startup_id']; ?>" class="btn btn-info">View
                        Details</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No featured startups at the moment.</p>
        <?php endif; ?>
    </div>

    <!-- Startups List -->
    <h2>All Startups</h2>
    <div class="startups-list">
        <?php if (mysqli_num_rows($startups_result) > 0): ?>
            <?php while ($startup = mysqli_fetch_assoc($startups_result)): ?>
                <div class="startup-card">
                    <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                    <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
                    <p><?php echo htmlspecialchars($startup['description']); ?></p>
                    <a href="startup_detail.php?startup_id=<?php echo $startup['startup_id']; ?>" class="btn btn-info">View
                        Details</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No startups found for the selected filters.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Embedded CSS -->
<style>
    .container {
        width: 80%;
        margin: 0 auto;
    }

    h1,
    h2 {
        text-align: center;
        color: #17a2b8;
    }

    .filter-form {
        margin: 20px 0;
    }

    .filter-form .form-control {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 100%;
    }

    .startup-card {
        background: #fff;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .startup-card h3 {
        margin-bottom: 10px;
        color: #333;
    }

    .startup-card p {
        margin: 5px 0;
        color: #555;
    }

    .btn-info {
        background-color: #17a2b8;
        color: #fff;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
    }

    .btn-info:hover {
        background-color: #138496;
    }
</style>