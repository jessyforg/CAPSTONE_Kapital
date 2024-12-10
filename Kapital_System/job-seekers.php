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
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.8em;
            font-weight: 600;
            color: #000;
            margin-bottom: 20px;
        }

        .job-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .job-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            flex: 1 1 calc(33% - 20px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .job-card h3 {
            margin: 0 0 10px;
            font-size: 1.5em;
            color: #000;
        }

        .job-card p {
            margin: 5px 0;
            font-size: 0.9em;
            color: #555;
        }

        .job-card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #f3c000;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9em;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .job-card a:hover {
            background-color: #ffab00;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="section-title">Available Jobs</h1>
        <div class="job-list">
            <?php
            $query = "SELECT Jobs.job_id, Jobs.role, Jobs.description, Jobs.location, Jobs.salary_range_min, Jobs.salary_range_max, 
                      Startups.name AS startup_name, Startups.industry 
                      FROM Jobs 
                      JOIN Startups ON Jobs.startup_id = Startups.startup_id";
            $result = mysqli_query($conn, $query);

            while ($job = mysqli_fetch_assoc($result)): ?>
                <div class="job-card">
                    <h3><?php echo htmlspecialchars($job['role']); ?></h3>
                    <p><strong>Startup:</strong> <?php echo htmlspecialchars($job['startup_name']); ?></p>
                    <p><strong>Industry:</strong> <?php echo htmlspecialchars($job['industry']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                    <p><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary_range_min']); ?> -
                        <?php echo htmlspecialchars($job['salary_range_max']); ?></p>
                    <p><?php echo htmlspecialchars($job['description']); ?></p>
                    <a href="job-details.php?job_id=<?php echo $job['job_id']; ?>">View Details</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>