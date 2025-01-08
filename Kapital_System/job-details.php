<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once 'db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the role of the logged-in user
$role_query = "SELECT role FROM Users WHERE user_id = ?";
$role_stmt = $conn->prepare($role_query);
$role_stmt->bind_param('i', $user_id);
$role_stmt->execute();
$role_result = $role_stmt->get_result();
$user_role = $role_result->fetch_assoc()['role'];

// Check if the job_id is passed in the URL
if (isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);

    // Fetch job details
    $job_query = "SELECT j.job_id, j.role AS job_role, j.description AS job_description, j.salary_range_min, j.salary_range_max,
                         j.location, j.created_at AS job_created_at, s.name AS startup_name, s.entrepreneur_id
                  FROM Jobs j
                  JOIN Startups s ON j.startup_id = s.startup_id
                  WHERE j.job_id = ?";
    $job_stmt = $conn->prepare($job_query);
    $job_stmt->bind_param('i', $job_id);
    $job_stmt->execute();
    $job_result = $job_stmt->get_result();

    if ($job_result->num_rows > 0) {
        $job = $job_result->fetch_assoc();

        // Fetch application details
        $application_query = "SELECT application_id, status, cover_letter
                              FROM Applications
                              WHERE job_id = ? AND job_seeker_id = ?";
        $application_stmt = $conn->prepare($application_query);
        $application_stmt->bind_param('ii', $job_id, $user_id);
        $application_stmt->execute();
        $application_result = $application_stmt->get_result();

        if ($application_result->num_rows > 0) {
            $application = $application_result->fetch_assoc();
        } else {
            $error = "You haven't applied for this job yet.";
        }
    } else {
        $error = "Job not found.";
    }
} else {
    $error = "No job ID provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #1e1e1e;
            color: #fff;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #000;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #f3c000;
            font-size: 2em;
        }

        .details {
            margin: 20px 0;
            line-height: 1.6;
        }

        .details h2 {
            color: #f3c000;
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .details p {
            margin: 10px 0;
        }

        .cta-buttons {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .cta-buttons a {
            background-color: #f3c000;
            color: #000;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .cta-buttons a:hover {
            background-color: #ffab00;
            transform: scale(1.05);
        }

        .cta-buttons a:active {
            transform: scale(0.95);
        }

        .error {
            color: #ff4c4c;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php else: ?>
            <div class="header">
                <h1>Job Details</h1>
            </div>
            <div class="details">
                <h2>Startup Name:</h2>
                <p><?php echo htmlspecialchars($job['startup_name']); ?></p>

                <h2>Job Role:</h2>
                <p><?php echo htmlspecialchars($job['job_role']); ?></p>

                <h2>Job Description:</h2>
                <p><?php echo htmlspecialchars($job['job_description']); ?></p>

                <h2>Salary Range:</h2>
                <p>$<?php echo number_format($job['salary_range_min'], 2); ?> - $<?php echo number_format($job['salary_range_max'], 2); ?></p>

                <h2>Location:</h2>
                <p><?php echo htmlspecialchars($job['location']); ?></p>

                <h2>Application Status:</h2>
                <p><?php echo htmlspecialchars($application['status']); ?></p>

                <h2>Cover Letter:</h2>
                <p><?php echo htmlspecialchars($application['cover_letter']); ?></p>
            </div>
            <div class="cta-buttons">
                <a href="messages.php?chat_with=<?php echo $job['entrepreneur_id']; ?>">Send Message to Entrepreneur</a>
                <a href="job-seekers.php">Back to Dashboard</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
