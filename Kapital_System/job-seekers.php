<?php
// Include the database connection file
include 'db_connection.php';

// Start the session
session_start();

// Set the current page to highlight in the navbar
$currentPage = 'job-seekers';

// Include the navbar
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seekers</title>
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

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filter-form input,
        .filter-form select,
        .filter-form button {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .filter-form button {
            background-color: #6c757d;
            color: white;
            cursor: pointer;
            border: none;
        }

        .filter-form button:hover {
            background-color: #5a6268;
        }

        .job-post {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
        }

        .job-post:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .job-post h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 15px;
        }

        .job-post p {
            font-size: 1rem;
            color: #555;
            margin: 5px 0;
        }

        .btn-apply {
            display: inline-block;
            padding: 10px 15px;
            background-color: #f3c000;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-apply:hover {
            background-color: #ffab00;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Available Jobs</h1>

        <!-- Filter Form -->
        <form class="filter-form" method="GET" action="job-seekers.php">
            <input type="text" name="industry" placeholder="Industry"
                value="<?php echo isset($_GET['industry']) ? htmlspecialchars($_GET['industry']) : ''; ?>">
            <input type="text" name="location" placeholder="Location"
                value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
            <input type="text" name="role" placeholder="Role"
                value="<?php echo isset($_GET['role']) ? htmlspecialchars($_GET['role']) : ''; ?>">
            <input type="number" name="salary_min" placeholder="Min Salary"
                value="<?php echo isset($_GET['salary_min']) ? htmlspecialchars($_GET['salary_min']) : ''; ?>">
            <button type="submit">Apply Filters</button>
        </form>

        <!-- Job Listings -->
        <?php
        // Build query with filters
        $filter_conditions = "1=1"; // Default condition to simplify concatenation
        if (isset($_GET['industry']) && $_GET['industry'] != "") {
            $industry = mysqli_real_escape_string($conn, $_GET['industry']);
            $filter_conditions .= " AND Startups.industry = '$industry'";
        }
        if (isset($_GET['location']) && $_GET['location'] != "") {
            $location = mysqli_real_escape_string($conn, $_GET['location']);
            $filter_conditions .= " AND Jobs.location = '$location'";
        }
        if (isset($_GET['role']) && $_GET['role'] != "") {
            $role = mysqli_real_escape_string($conn, $_GET['role']);
            $filter_conditions .= " AND Jobs.role = '$role'";
        }
        if (isset($_GET['salary_min']) && $_GET['salary_min'] != "") {
            $salary_min = (int) $_GET['salary_min'];
            $filter_conditions .= " AND Jobs.salary_range_max >= $salary_min";
        }

        // Query to fetch job details
        $query = "
            SELECT Jobs.job_id, Jobs.role, Jobs.description, Jobs.requirements, Jobs.location, Jobs.salary_range_min, Jobs.salary_range_max, 
                   Startups.name AS startup_name, Startups.industry 
            FROM Jobs 
            JOIN Startups ON Jobs.startup_id = Startups.startup_id
            WHERE $filter_conditions
        ";
        $result = mysqli_query($conn, $query);

        // Display jobs
        if (mysqli_num_rows($result) > 0) {
            while ($job = mysqli_fetch_assoc($result)): ?>
                <div class="job-post">
                    <h3><?php echo htmlspecialchars($job['role']); ?></h3>
                    <p><strong>Startup:</strong> <?php echo htmlspecialchars($job['startup_name']); ?></p>
                    <p><strong>Industry:</strong> <?php echo htmlspecialchars($job['industry']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                    <p><strong>Salary:</strong> ₱<?php echo number_format($job['salary_range_min'], 2); ?> -
                        ₱<?php echo number_format($job['salary_range_max'], 2); ?></p>
                    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                    <p><strong>Requirements:</strong> <?php echo nl2br(htmlspecialchars($job['requirements'])); ?></p>
                    <a href="apply_job.php?job_id=<?php echo $job['job_id']; ?>" class="btn-apply">Apply</a>
                </div>
            <?php endwhile;
        } else {
            echo "<p>No jobs found with the current filters.</p>";
        }
        ?>
    </div>
</body>

</html>