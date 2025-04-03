<?php
include 'db_connection.php';
session_start();

// Check if notification_id is set
if (!isset($_GET['notification_id'])) {
    echo "No notification ID provided.";
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
    
    echo "<h2>Notification Details</h2>";
    echo "<pre>";
    print_r($notification);
    echo "</pre>";
    
    // Check user role
    $user_id = $_SESSION['user_id'] ?? 'Not logged in';
    $user_query = "SELECT role FROM Users WHERE user_id = ?";
    $user_stmt = $conn->prepare($user_query);
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    
    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        echo "<h3>User Role: " . $user['role'] . "</h3>";
    } else {
        echo "<h3>User Role: Not found</h3>";
    }
} else {
    echo "Notification not found.";
}
?> 