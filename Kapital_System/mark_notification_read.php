<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $notification_id = $data['notification_id'];
    $user_id = $_SESSION['user_id'];

    // Update notification status to 'read'
    $stmt = $conn->prepare("UPDATE Notifications SET status = 'read' WHERE notification_id = ? AND user_id = ?");
    $stmt->bind_param('ii', $notification_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}
?>
