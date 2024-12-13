<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['startup_id']) && isset($_POST['approval_comment'])) {
    $startup_id = $_POST['startup_id'];
    $approval_comment = mysqli_real_escape_string($conn, $_POST['approval_comment']);
    $admin_id = $_SESSION['user_id'];

    $query = "UPDATE Startups SET approval_comment = '$approval_comment', approved_by = $admin_id WHERE startup_id = $startup_id";
    if (mysqli_query($conn, $query)) {
        header("Location: admin-panel.php?msg=Comment added successfully");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
