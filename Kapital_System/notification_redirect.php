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
            // Use application_id for the URL
            header("Location: application_status.php?application_id=" . $notification['application_id']);
            break;
        case 'investment_match':
            header("Location: match_details.php?startup_id=" . $notification['startup_id']);
            break;
        case 'job_offer':
            header("Location: job_offer_details.php?job_id=" . $notification['sender_id']);
            break;
        case 'message':
            header("Location: messages.php?conversation_id=" . $notification['sender_id']);
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