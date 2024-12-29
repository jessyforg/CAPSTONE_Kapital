<?php
session_start();
include('navbar.php');
include('db_connection.php');

// Check if user is logged in and is a job seeker
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'job_seeker') {
    header("Location: sign_in.php");
    exit("You must be logged in as a job seeker.");
}

// Get the logged-in user details
$user_id = $_SESSION['user_id'];
$query_user = "SELECT * FROM Users WHERE user_id = '$user_id'";
$result_user = mysqli_query($conn, $query_user);
if ($result_user && mysqli_num_rows($result_user) > 0) {
    $user = mysqli_fetch_assoc($result_user);
} else {
    die("User not found.");
}

// Retrieve job details if job_id is set
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Query to get job details
    $query_job = "SELECT j.*, s.name AS startup_name, s.industry, s.location AS startup_location
                  FROM Jobs j
                  JOIN Startups s ON j.startup_id = s.startup_id
                  WHERE j.job_id = '$job_id'";
    $result_job = mysqli_query($conn, $query_job);

    if ($result_job && mysqli_num_rows($result_job) > 0) {
        $job = mysqli_fetch_assoc($result_job);
    } else {
        die("Job not found.");
    }
} else {
    die("Job ID is missing.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <title>Job Details</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e0e0e0;
            /* Lighter grey background */
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            /* White background for the content */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2e7d32;
            /* Dark green for headings */
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group,
        .section-title,
        .section-content,
        .startup-info,
        .job-details {
            margin-top: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
            color: #333333;
            /* Darker text for readability */
        }

        .section-content {
            margin-top: 10px;
            font-size: 16px;
            color: #333333;
            /* Darker text for readability */
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333333;
            /* Dark text for labels */
        }

        input,
        textarea,
        select,
        button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            background-color: #f4f7fc;
            /* Light background for inputs */
            color: #333333;
            /* Dark text inside inputs */
            font-size: 16px;
        }

        input::placeholder,
        textarea::placeholder {
            color: #888;
            /* Lighter placeholder text */
            font-size: 16px;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            background-color: #ffffff;
            /* White background on focus */
            border-color: #4caf50;
            /* Green border on focus */
        }

        button {
            background-color: #4caf50;
            /* Green button */
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
            /* Slightly darker green on hover */
        }

        .apply-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4caf50;
            /* Green apply button */
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            text-align: center;
        }

        .apply-btn:hover {
            background-color: #45a049;
            /* Darker green on hover */
        }

        .startup-info {
            margin-top: 20px;
            border-top: 2px solid #ddd;
            padding-top: 20px;
        }

        .startup-info h3 {
            color: #333333;
            font-size: 24px;
        }

        .startup-info p {
            color: #555555;
            /* Slightly lighter text for less important info */
        }

        .error {
            color: #f44336;
            /* Red for errors */
        }

        .success {
            color: #4caf50;
            /* Green for success */
        }

        textarea {
            resize: vertical;
            height: 150px;
        }

        select {
            padding: 12px 10px;
            background: #f4f7fc;
            /* Light background for select input */
            color: #333333;
            /* Dark text */
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        select option {
            background-color: #ffffff;
            color: black;
        }

        /* Specific adjustments for job role and location visibility */
        .job-details h2 {
            color: #333333;
            /* Dark text color for job role */
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .job-details p {
            color: #333333;
            /* Dark text color for location */
            font-size: 1rem;
            margin-bottom: 15px;
        }
    </style>


</head>

<body>

    <div class="container">
        <h1>Job Details</h1>

        <?php if (isset($job)): ?>
            <div class="job-details">
                <h2><?php echo htmlspecialchars($job['role']); ?></h2>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($job['startup_location']); ?></p>

                <div class="section-title">Job Description</div>
                <div class="section-content"><?php echo nl2br(htmlspecialchars($job['description'])); ?></div>

                <div class="section-title">Job Requirements</div>
                <div class="section-content"><?php echo nl2br(htmlspecialchars($job['requirements'])); ?></div>

                <div class="section-title">Salary Range</div>
                <div class="section-content">
                    ₱<?php echo htmlspecialchars($job['salary_range_min']) . " - ₱" . htmlspecialchars($job['salary_range_max']); ?>
                </div>


                <div class="startup-info">
                    <h3>Startup Information</h3>
                    <p><strong>Startup Name:</strong> <?php echo htmlspecialchars($job['startup_name']); ?></p>
                    <p><strong>Industry:</strong> <?php echo htmlspecialchars($job['industry']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['startup_location']); ?></p>

                    <?php if (!empty($job['pitch_deck_url'])): ?>
                        <p><a href="<?php echo htmlspecialchars($job['pitch_deck_url']); ?>" target="_blank">View Pitch Deck</a>
                        </p>
                    <?php else: ?>
                        <p>No pitch deck available.</p>
                    <?php endif; ?>
                </div>

                <a href="apply_job.php?job_id=<?php echo $job['job_id']; ?>" class="apply-btn">Apply for this Job</a>
            </div>
        <?php else: ?>
            <p>Job not found.</p>
        <?php endif; ?>

    </div>

</body>

</html>