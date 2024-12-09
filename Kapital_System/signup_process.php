<?php
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
$name = $conn->real_escape_string($_POST['name']);
$email = $conn->real_escape_string($_POST['email']);
$password = $_POST['password'];
$retype_password = $_POST['retype_password'];
$role = $conn->real_escape_string($_POST['role']);

// Check if password and retype password match
if ($password !== $retype_password) {
    echo "Passwords do not match!";
    exit;
}

// Hash the password before saving to the database
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert the new user into the Users table
$sql = "INSERT INTO Users (name, email, password, role) VALUES ('$name', '$email', '$password_hash', '$role')";

if ($conn->query($sql) === TRUE) {
    // Get the user_id of the newly inserted user
    $user_id = $conn->insert_id;

    // Redirect user to their specific page based on the role
    switch ($role) {
        case 'entrepreneur':
            header("Location: entrepreneurs.php?user_id=$user_id");
            break;

        case 'investor':
            header("Location: investors.php?user_id=$user_id");
            break;

        case 'job_seeker':
            header("Location: job-seekers.php?user_id=$user_id");
            break;

        default:
            echo "Invalid role.";
            break;
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>