<?php
// Include database connection
include 'db_connection.php';
session_start();

include 'navbar.php';
// Check if the logged-in user is an entrepreneur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: index.php");
    exit;
}

// Check if application_id is set
if (!isset($_GET['application_id'])) {
    echo "Invalid application.";
    exit;
}

$application_id = intval($_GET['application_id']);

// Fetch the application details, including the cover letter
$query = "SELECT a.*, j.role AS job_role, j.startup_id, u.name AS job_seeker_name, a.cover_letter
          FROM Applications a
          JOIN Jobs j ON a.job_id = j.job_id
          JOIN Users u ON a.job_seeker_id = u.user_id
          WHERE a.application_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $application_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the application exists
if ($result->num_rows > 0) {
    $application = $result->fetch_assoc();
    $application_status = $application['status'];
    $job_seeker_name = $application['job_seeker_name'];
    $job_role = $application['job_role'];
    $cover_letter = $application['cover_letter']; // Get the cover letter
} else {
    echo "Application not found.";
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
    $new_status = $_POST['status'];

    // Validate status
    if (!in_array($new_status, ['reviewed', 'interviewed', 'hired', 'rejected'])) {
        echo "Invalid status.";
        exit;
    }

    // Update the application status
    $update_query = "UPDATE Applications SET status = ? WHERE application_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $new_status, $application_id);
    $update_stmt->execute();

    // Create the notification message
    $notification_message = "Your application for the $job_role role has been updated to $new_status.";

    // Optionally, create a notification for the job seeker
    $notification_query = "INSERT INTO Notifications (user_id, sender_id, type, application_id, job_id, message, status) 
                            VALUES (?, ?, 'application_status', ?, ?, ?, 'unread')";
    $notification_stmt = $conn->prepare($notification_query);
    $notification_stmt->bind_param(
        "iiiss", // Bind parameters
        $application['job_seeker_id'], // Job seeker ID
        $_SESSION['user_id'],           // Entrepreneur ID (sender)
        $application_id,                // Application ID
        $application['job_id'],         // Job ID (from the job associated with the application)
        $notification_message           // The notification message
    );
    $notification_stmt->execute();

    echo "Application status updated successfully.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
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

        h2 {
            text-align: center;
            color: #7289DA;
            margin-bottom: 20px;
        }

        .status-info {
            margin-bottom: 20px;
            font-size: 1.2em;
        }

        .status-form {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .status-form label {
            font-weight: bold;
            margin-right: 10px;
        }

        .status-form select {
            padding: 8px;
            font-size: 1em;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .btn-update {
            padding: 10px 20px;
            font-size: 1em;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-update:hover {
            background-color: #45a049;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #F44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-back:hover {
            background-color: #e53935;
        }

        .cover-letter {
            margin-top: 20px;
            padding: 10px;
            background-color: #2C2F33;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .cover-letter pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Application Status for <?php echo htmlspecialchars($job_seeker_name); ?> - 
            <?php echo htmlspecialchars($job_role); ?>
        </h2>
        <div class="status-info">
            <p><strong>Current Status:</strong> <span><?php echo ucfirst($application_status); ?></span></p>
        </div>

        <div class="cover-letter">
            <h3>Cover Letter:</h3>
            <pre><?php echo nl2br(htmlspecialchars($cover_letter)); ?></pre>
        </div>

        <form method="POST" action="application_status.php?application_id=<?php echo $application_id; ?>"
            class="status-form">
            <label for="status">Change Status:</label>
            <select name="status" id="status" required>
                <option value="reviewed" <?php if ($application_status == 'reviewed') echo 'selected'; ?>>Reviewed
                </option>
                <option value="interviewed" <?php if ($application_status == 'interviewed') echo 'selected'; ?>>
                    Interviewed</option>
                <option value="hired" <?php if ($application_status == 'hired') echo 'selected'; ?>>Hired</option>
                <option value="rejected" <?php if ($application_status == 'rejected') echo 'selected'; ?>>Rejected
                </option>
            </select>
            <button type="submit" class="btn-update">Update Status</button>
        </form>

        <a href="entrepreneurs.php" class="btn-back">Back to Dashboard</a>
    </div>

</body>

</html>
