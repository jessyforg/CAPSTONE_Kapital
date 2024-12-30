<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $message_id = $data['message_id'];
    $user_id = $_SESSION['user_id'];

    // Update message status to 'read'
    $stmt = $conn->prepare("UPDATE Messages SET status = 'read' WHERE message_id = ? AND (sender_id = ? OR receiver_id = ?)");
    $stmt->bind_param('iii', $message_id, $user_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}
?>
