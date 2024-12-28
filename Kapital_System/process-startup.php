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

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startup_id = intval($_POST['startup_id']);
    $action = $_POST['action'];
    $admin_id = $_SESSION['user_id'];

    // Fetch the entrepreneur's user_id and email for notification
    $entrepreneur_query = "SELECT entrepreneur_id, entrepreneur_email FROM Startups WHERE startup_id = '$startup_id'";
    $entrepreneur_result = mysqli_query($conn, $entrepreneur_query);
    $entrepreneur = mysqli_fetch_assoc($entrepreneur_result);
    $entrepreneur_id = $entrepreneur['entrepreneur_id'];
    $entrepreneur_email = $entrepreneur['entrepreneur_email'];

    // Prepare admin's comment if exists
    $approval_comment = null;
    if (isset($_POST['approval_comment'])) {
        $approval_comment = mysqli_real_escape_string($conn, $_POST['approval_comment']);
    }

    if ($action === 'approve') {
        // Update startup status to approved
        $query = "UPDATE Startups 
                  SET approval_status = 'approved', approved_by = '$admin_id', approval_comment = '$approval_comment' 
                  WHERE startup_id = '$startup_id'";
        $message = "Your startup has been approved.";
        $notification_message = "Your startup has been approved by the admin.";

        // Send notification email to entrepreneur
        mail($entrepreneur_email, "Startup Status Update", $message);

        // Notify matched investors
        $startup_industry_query = "SELECT industry FROM Startups WHERE startup_id = '$startup_id'";
        $startup_industry_result = mysqli_query($conn, $startup_industry_query);
        $startup_industry = mysqli_fetch_assoc($startup_industry_result)['industry'];

        $investors_query = "SELECT email FROM Investors WHERE preferred_industries LIKE '%$startup_industry%'";
        $investors_result = mysqli_query($conn, $investors_query);
        while ($investor = mysqli_fetch_assoc($investors_result)) {
            mail($investor['email'], "New Startup Match", "A new startup matching your preferences has been approved.");
        }
    } elseif ($action === 'reject') {
        // Update startup status to rejected
        $query = "UPDATE Startups 
                  SET approval_status = 'rejected', approved_by = '$admin_id', approval_comment = '$approval_comment' 
                  WHERE startup_id = '$startup_id'";
        $message = "Your startup has been rejected.";
        $notification_message = "Your startup has been rejected by the admin.";
    } else {
        header("Location: admin-panel.php");
        exit;
    }

    // Send notification to entrepreneur about approval/rejection
    $notification_query = "INSERT INTO Notifications (user_id, sender_id, type, message) 
                           VALUES ('$entrepreneur_id', '$admin_id', 'system_alert', '$notification_message')";
    mysqli_query($conn, $notification_query);

    // Execute the startup approval/rejection update
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = $message;
    } else {
        $_SESSION['error'] = "Error updating startup.";
    }

    header("Location: admin-panel.php");
    exit;
}
?>