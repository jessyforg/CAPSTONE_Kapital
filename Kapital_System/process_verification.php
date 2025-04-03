<?php
// Include database connection
include 'db_connection.php';

// Start session
session_start();

// Check if the logged-in user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['document_id']) && isset($_POST['action'])) {
    $document_id = intval($_POST['document_id']);
    $action = $_POST['action'];
    $admin_id = $_SESSION['user_id'];

    // Get the document details
    $query = "SELECT vd.*, u.user_id, u.verification_status 
              FROM Verification_Documents vd 
              JOIN Users u ON vd.user_id = u.user_id 
              WHERE vd.document_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $document_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $document = $result->fetch_assoc();

    if ($document) {
        if ($action === 'approve') {
            // Update document status
            $update_doc = "UPDATE Verification_Documents 
                          SET status = 'approved', 
                              reviewed_by = ?, 
                              reviewed_at = CURRENT_TIMESTAMP 
                          WHERE document_id = ?";
            $stmt = $conn->prepare($update_doc);
            $stmt->bind_param("ii", $admin_id, $document_id);
            $stmt->execute();

            // Update user verification status
            $update_user = "UPDATE Users 
                          SET verification_status = 'verified' 
                          WHERE user_id = ?";
            $stmt = $conn->prepare($update_user);
            $stmt->bind_param("i", $document['user_id']);
            $stmt->execute();

            // Create notification
            $notification_message = "Your verification document has been approved. Your account is now verified.";
            $notification_query = "INSERT INTO Notifications (user_id, sender_id, type, message, status) 
                                 VALUES (?, ?, 'system_alert', ?, 'unread')";
            $stmt = $conn->prepare($notification_query);
            $stmt->bind_param("iis", $document['user_id'], $admin_id, $notification_message);
            $stmt->execute();

        } elseif ($action === 'reject') {
            $rejection_reason = isset($_POST['rejection_reason']) ? $_POST['rejection_reason'] : '';
            
            // Update document status
            $update_doc = "UPDATE Verification_Documents 
                          SET status = 'not approved', 
                              reviewed_by = ?, 
                              reviewed_at = CURRENT_TIMESTAMP,
                              rejection_reason = ?
                          WHERE document_id = ?";
            $stmt = $conn->prepare($update_doc);
            $stmt->bind_param("isi", $admin_id, $rejection_reason, $document_id);
            $stmt->execute();

            // Update user verification status
            $update_user = "UPDATE Users 
                          SET verification_status = 'not approved' 
                          WHERE user_id = ?";
            $stmt = $conn->prepare($update_user);
            $stmt->bind_param("i", $document['user_id']);
            $stmt->execute();

            // Create notification
            $notification_message = "Your verification document was not approved." . 
                                  (!empty($rejection_reason) ? " Reason: " . $rejection_reason : " Please upload a valid document.");
            $notification_query = "INSERT INTO Notifications (user_id, sender_id, type, message, status, url) 
                                 VALUES (?, ?, 'system_alert', ?, 'unread', 'verify_account.php')";
            $stmt = $conn->prepare($notification_query);
            $stmt->bind_param("iis", $document['user_id'], $admin_id, $notification_message);
            $stmt->execute();
        }
    }
}

// Redirect back to admin panel
header("Location: admin-panel.php");
exit; 