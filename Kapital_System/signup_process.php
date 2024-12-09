<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
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

    // Start the session and set session variables
    $_SESSION['user_id'] = $user_id;
    $_SESSION['role'] = $role;

    // Based on the role, insert into the appropriate table
    switch ($role) {
        case 'entrepreneur':
            $sql_entrepreneur = "INSERT INTO Entrepreneurs (entrepreneur_id) VALUES ('$user_id')";
            if ($conn->query($sql_entrepreneur) === TRUE) {
                header("Location: entrepreneurs.php");
            } else {
                echo "Error inserting into Entrepreneurs table: " . $conn->error;
            }
            break;

        case 'investor':
            $sql_investor = "INSERT INTO Investors (investor_id) VALUES ('$user_id')";
            if ($conn->query($sql_investor) === TRUE) {
                header("Location: investors.php");
            } else {
                echo "Error inserting into Investors table: " . $conn->error;
            }
            break;

        case 'job_seeker':
            $sql_job_seeker = "INSERT INTO Job_Seekers (job_seeker_id) VALUES ('$user_id')";
            if ($conn->query($sql_job_seeker) === TRUE) {
                header("Location: job-seekers.php");
            } else {
                echo "Error inserting into Job Seekers table: " . $conn->error;
            }
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