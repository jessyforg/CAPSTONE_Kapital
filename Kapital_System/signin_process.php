<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "StartupConnect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize and get the form data
$email = $conn->real_escape_string($_POST['email']);
$password = $_POST['password'];

// Check if the user exists in the Users table
$sql = "SELECT user_id, password, role FROM Users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, now verify password
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        // Password is correct, start session
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['role'] = $row['role'];

        // Redirect based on role
        switch ($_SESSION['role']) {
            case 'entrepreneur':
                header("Location: index.php");
                break;
            case 'investor':
                header("Location: index.php");
                break;
            case 'job_seeker':
                header("Location: index.php");
                break;
            case 'admin': // Admin role
                header("Location: admin-panel.php");
                break;
            default:
                echo "Invalid role.";
        }
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "No user found with that email.";
}

$conn->close();
?>
