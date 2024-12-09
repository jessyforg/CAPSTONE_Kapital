<?php
// Start session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('db_connection.php'); // Assuming you include your database connection

// Check if startup_id is provided
if (isset($_GET['startup_id'])) {
    $startup_id = $_GET['startup_id'];

    // Perform the delete operation
    $sql = "DELETE FROM startups WHERE startup_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $startup_id);

    if ($stmt->execute()) {
        // Delete successful, show a success message via JavaScript
        echo "<script>
                alert('Startup deleted successfully!');
                window.location.href = 'profile.php'; // Redirect back to profile page
              </script>";
    } else {
        // If deletion fails, show an error message
        echo "<script>
                alert('Error deleting startup. Please try again.');
                window.location.href = 'profile.php'; // Redirect back to profile page
              </script>";
    }
}
?>