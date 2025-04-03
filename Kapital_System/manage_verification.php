<?php
session_start();
include('db_connection.php');

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// Handle document deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $document_id = intval($_POST['document_id']);
    
    // Verify the document belongs to the user
    $check_query = "SELECT file_path FROM Verification_Documents WHERE document_id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $document_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $document = $result->fetch_assoc();
        
        // Delete the file from the server
        if (file_exists($document['file_path'])) {
            unlink($document['file_path']);
        }
        
        // Delete the record from the database
        $delete_query = "DELETE FROM Verification_Documents WHERE document_id = ? AND user_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("ii", $document_id, $user_id);
        
        if ($stmt->execute()) {
            $message = "Document deleted successfully.";
        } else {
            $error = "Error deleting document.";
        }
    } else {
        $error = "Document not found or unauthorized.";
    }
}

// Handle document update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $document_id = $_POST['document_id'] ?? 0;
    $rejection_reason = $_POST['rejection_reason'] ?? '';

    // Get document details
    $doc_query = "SELECT vd.*, u.user_id, u.name, u.email 
                  FROM Verification_Documents vd 
                  JOIN Users u ON vd.user_id = u.user_id 
                  WHERE vd.document_id = ?";
    $stmt = $conn->prepare($doc_query);
    $stmt->bind_param("i", $document_id);
    $stmt->execute();
    $document = $stmt->get_result()->fetch_assoc();

    if ($document) {
        if ($action === 'approve') {
            // Update document status
            $update_query = "UPDATE Verification_Documents 
                           SET status = 'approved', 
                               reviewed_by = ?, 
                               reviewed_at = CURRENT_TIMESTAMP 
                           WHERE document_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ii", $_SESSION['user_id'], $document_id);
            
            if ($stmt->execute()) {
                // Update user verification status
                $update_user = "UPDATE Users 
                              SET verification_status = 'verified' 
                              WHERE user_id = ?";
                $stmt = $conn->prepare($update_user);
                $stmt->bind_param("i", $document['user_id']);
                $stmt->execute();

                // Create notification
                $notification_message = "Your verification status has been updated. Click here to view.";
                $notification_url = "verify_account.php";
                $insert_notification = "INSERT INTO Notifications (user_id, type, message, url) 
                                      VALUES (?, 'verification_status', ?, ?)";
                $stmt = $conn->prepare($insert_notification);
                $stmt->bind_param("iss", $document['user_id'], $notification_message, $notification_url);
                $stmt->execute();

                header("Location: verify_account.php?success=1");
                exit();
            }
        } elseif ($action === 'reject') {
            // Update document status with rejection reason
            $update_query = "UPDATE Verification_Documents 
                           SET status = 'not approved', 
                               rejection_reason = ?,
                               reviewed_by = ?, 
                               reviewed_at = CURRENT_TIMESTAMP 
                           WHERE document_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sii", $rejection_reason, $_SESSION['user_id'], $document_id);
            
            if ($stmt->execute()) {
                // Update user verification status
                $update_user = "UPDATE Users 
                              SET verification_status = 'not approved' 
                              WHERE user_id = ?";
                $stmt = $conn->prepare($update_user);
                $stmt->bind_param("i", $document['user_id']);
                $stmt->execute();

                // Create notification
                $notification_message = "Your verification status has been updated. Click here to view.";
                $notification_url = "verify_account.php";
                $insert_notification = "INSERT INTO Notifications (user_id, type, message, url) 
                                      VALUES (?, 'verification_status', ?, ?)";
                $stmt = $conn->prepare($insert_notification);
                $stmt->bind_param("iss", $document['user_id'], $notification_message, $notification_url);
                $stmt->execute();

                header("Location: verify_account.php?success=1");
                exit();
            }
        }
    }
}

// Redirect back to verify_account.php
header("Location: verify_account.php" . ($message ? "?message=" . urlencode($message) : "") . ($error ? "?error=" . urlencode($error) : ""));
exit; 