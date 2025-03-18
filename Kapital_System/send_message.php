<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = trim($_POST['message']);
    
    if (empty($message)) {
        $_SESSION['error'] = "Message cannot be empty";
        if (isset($_POST['application_id'])) {
            header("Location: application_status.php?application_id=" . $_POST['application_id']);
        } else {
            header("Location: messages.php?chat_with=" . $receiver_id);
        }
        exit();
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert message
        $stmt = $conn->prepare("INSERT INTO Messages (sender_id, receiver_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
        $stmt->execute();

        // Create notification for receiver
        $sender_name = $_SESSION['name'];
        
        // Different notification message based on context
        if (isset($_POST['application_id'])) {
            $notification_message = "$sender_name sent you a message regarding your job application";
            $stmt = $conn->prepare("INSERT INTO Notifications (user_id, sender_id, type, message, application_id, status) VALUES (?, ?, 'message', ?, ?, 'unread')");
            $stmt->bind_param("iisi", $receiver_id, $sender_id, $notification_message, $_POST['application_id']);
        } else {
            $notification_message = "$sender_name sent you a new message";
            $stmt = $conn->prepare("INSERT INTO Notifications (user_id, sender_id, type, message, status) VALUES (?, ?, 'message', ?, 'unread')");
            $stmt->bind_param("iis", $receiver_id, $sender_id, $notification_message);
        }
        $stmt->execute();

        $conn->commit();
        $_SESSION['success'] = "Message sent successfully";

        // Check if it's an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['status' => 'success', 'message' => 'Message sent successfully']);
            exit;
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error sending message: " . $e->getMessage();

        // Check if it's an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }
    }

    // Only redirect if it's not an AJAX request
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        // Redirect based on context
        if (isset($_POST['application_id'])) {
            header("Location: application_status.php?application_id=" . $_POST['application_id']);
        } else {
            header("Location: messages.php?chat_with=" . $receiver_id);
        }
        exit();
    }
}
?> 