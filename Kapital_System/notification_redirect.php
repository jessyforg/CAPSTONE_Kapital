<?php
include 'db_connection.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit;
}

// Check if notification_id is set
if (!isset($_GET['notification_id'])) {
    header("Location: index.php");
    exit;
}

$notification_id = intval($_GET['notification_id']);

// Query to fetch the notification
$query = "SELECT * FROM Notifications WHERE notification_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $notification_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $notification = $result->fetch_assoc();

    // Mark notification as 'read'
    $update_query = "UPDATE Notifications SET status = 'read' WHERE notification_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("i", $notification_id);
    $update_stmt->execute();

    // Redirect based on the notification type
    switch ($notification['type']) {
        case 'application_status':
            if (!empty($notification['application_id'])) {
                // Get the application ID
                $application_id = $notification['application_id'];

                // Query to fetch the associated job details for the application
                $application_query = "SELECT job_id FROM Applications WHERE application_id = ?";
                $application_stmt = $conn->prepare($application_query);
                $application_stmt->bind_param("i", $application_id);
                $application_stmt->execute();
                $application_result = $application_stmt->get_result();

                if ($application_result->num_rows > 0) {
                    $application = $application_result->fetch_assoc();
                    $job_id = $application['job_id'];

                    // Redirect to the job details page
                    header("Location: job-details.php?job_id=" . $job_id);
                } else {
                    echo "Application not found.";
                }
            } else {
                echo "No application ID provided for this notification.";
            }
            break;

        case 'investment_match':
            if (!empty($notification['match_id'])) {
                header("Location: match_details.php?match_id=" . $notification['match_id']);
            } else {
                echo "No match ID provided for this notification.";
            }
            break;

        case 'job_offer':
            if (!empty($notification['job_id'])) {
                header("Location: job_offer_details.php?job_id=" . $notification['job_id']);
            } else {
                echo "No job ID provided for this notification.";
            }
            break;

        case 'message':
            if (!empty($notification['sender_id'])) {
                header("Location: messages.php?conversation_id=" . $notification['sender_id']);
            } else {
                echo "No sender ID provided for this notification.";
            }
            break;

        case 'startup_status':
            if (!empty($notification['startup_id'])) {
                // Query to fetch startup details based on startup_id
                $startup_query = "SELECT * FROM Startups WHERE startup_id = ?";
                $startup_stmt = $conn->prepare($startup_query);
                $startup_stmt->bind_param("i", $notification['startup_id']);
                $startup_stmt->execute();
                $startup_result = $startup_stmt->get_result();

                if ($startup_result->num_rows > 0) {
                    $startup = $startup_result->fetch_assoc();
                    // Redirect to the startup details page
                    header("Location: startup_detail.php?startup_id=" . $startup['startup_id']);
                } else {
                    echo "Startup not found.";
                }
            } else {
                echo "No startup ID provided for this notification.";
            }
            break;

        case 'system_alert':
            // For system alerts, you can either redirect to a general alert page
            // or handle it as needed. For now, redirecting to homepage:
            header("Location: index.php");
            break;

        default:
            // If the notification type is unknown, check for the 'url' field and redirect
            if (!empty($notification['url'])) {
                // Use the URL from the notification table for redirection
                header("Location: " . $notification['url']);
            } else {
                header("Location: index.php");
            }
            break;
    }
    exit;
} else {
    echo "Notification not found.";
    exit;
}
?>
