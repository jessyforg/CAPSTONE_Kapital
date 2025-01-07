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
                header("Location: application_status.php?application_id=" . $notification['application_id']);
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

        default:
            // If the notification type is unknown, redirect to the homepage
            header("Location: index.php");
            break;
    }
    exit;
} else {
    echo "Notification not found.";
    exit;
}
?>