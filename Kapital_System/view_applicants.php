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

// Check if startup_id is provided
if (!isset($_GET['startup_id'])) {
    echo "Invalid startup.";
    exit;
}

$startup_id = intval($_GET['startup_id']);

// Fetch job applicants for the specific startup
$query = "SELECT a.*, u.name AS job_seeker_name, j.role 
          FROM Applications a
          JOIN Jobs j ON a.job_id = j.job_id
          JOIN Users u ON a.job_seeker_id = u.user_id
          WHERE j.startup_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $startup_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the startup details for the header
$startup_query = "SELECT name FROM Startups WHERE startup_id = ?";
$startup_stmt = $conn->prepare($startup_query);
$startup_stmt->bind_param("i", $startup_id);
$startup_stmt->execute();
$startup_result = $startup_stmt->get_result();
$startup = $startup_result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applicants for <?php echo htmlspecialchars($startup['name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Add your styles here */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #2C2F33;
            color: #f9f9f9;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #23272A;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #7289DA;
            margin-bottom: 20px;
        }

        .applicant-list {
            margin-top: 20px;
        }

        .applicant {
            background: #333;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .applicant h3 {
            margin: 0;
            color: #FFB74D;
        }

        .applicant p {
            margin: 5px 0;
        }

        .btn-view {
            background-color: #17a2b8;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-view:hover {
            background-color: #138496;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Job Applicants for <?php echo htmlspecialchars($startup['name']); ?></h1>

        <div class="applicant-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($applicant = $result->fetch_assoc()): ?>
                    <div class="applicant">
                        <h3><?php echo htmlspecialchars($applicant['job_seeker_name']); ?></h3>
                        <p><strong>Role:</strong> <?php echo htmlspecialchars($applicant['role']); ?></p>
                        <p><strong>Application Status:</strong> <?php echo ucfirst(htmlspecialchars($applicant['status'])); ?>
                        </p>
                        <a href="application_status.php?application_id=<?php echo $applicant['application_id']; ?>"
                            class="btn-view">View Application Status</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No applicants yet for this startup.</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>