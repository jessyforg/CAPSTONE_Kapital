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

    if ($action === 'approve') {
        $query = "UPDATE Startups 
                  SET approval_status = 'approved', approved_by = '$admin_id' 
                  WHERE startup_id = '$startup_id'";
        $message = "Startup approved successfully.";
    } elseif ($action === 'reject') {
        $query = "UPDATE Startups 
                  SET approval_status = 'rejected', approved_by = '$admin_id' 
                  WHERE startup_id = '$startup_id'";
        $message = "Startup rejected successfully.";
    } else {
        header("Location: admin-panel.php");
        exit;
    }

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = $message;
    } else {
        $_SESSION['error'] = "Error updating startup.";
    }

    header("Location: admin-panel.php");
    exit;
}
?>
