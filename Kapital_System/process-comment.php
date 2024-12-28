<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['startup_id']) && isset($_POST['approval_comment'])) {
    $startup_id = $_POST['startup_id'];
    $approval_comment = mysqli_real_escape_string($conn, $_POST['approval_comment']);
    $admin_id = $_SESSION['user_id'];

    // Fetch the entrepreneur's user_id for notification
    $entrepreneur_query = "SELECT entrepreneur_id FROM Startups WHERE startup_id = '$startup_id'";
    $entrepreneur_result = mysqli_query($conn, $entrepreneur_query);
    $entrepreneur = mysqli_fetch_assoc($entrepreneur_result);
    $entrepreneur_id = $entrepreneur['entrepreneur_id'];

    // Update the startup with the admin's comment
    $query = "UPDATE Startups SET approval_comment = '$approval_comment', approved_by = $admin_id WHERE startup_id = $startup_id";
    if (mysqli_query($conn, $query)) {
        // Send notification to the entrepreneur
        $notification_message = "Your startup has received a comment from the admin: $approval_comment";
        $notification_query = "INSERT INTO Notifications (user_id, sender_id, type, message) 
                               VALUES ('$entrepreneur_id', '$admin_id', 'system_alert', '$notification_message')";
        mysqli_query($conn, $notification_query);

        header("Location: admin-panel.php?msg=Comment added successfully");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>