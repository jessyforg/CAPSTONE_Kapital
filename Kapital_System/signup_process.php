<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StartupConnect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $retype_password = $_POST['retype_password'];
    $role = $conn->real_escape_string($_POST['role']);

    if ($password !== $retype_password) {
        echo "Passwords do not match!";
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql_user = "INSERT INTO Users (name, email, password, role) VALUES ('$name', '$email', '$password_hash', '$role')";
    if ($conn->query($sql_user) === TRUE) {
        $user_id = $conn->insert_id;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;

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
                $investment_range_min = $conn->real_escape_string($_POST['investment_range_min']);
                $investment_range_max = $conn->real_escape_string($_POST['investment_range_max']);

                $sql_investor = "INSERT INTO Investors (investor_id)
                                 VALUES ('$user_id')";
                if ($conn->query($sql_investor) === TRUE) {
                    header("Location: investors.php");
                } else {
                    echo "Error inserting into Investors table: " . $conn->error;
                }
                break;

            case 'job_seeker':
                $experience_level = $conn->real_escape_string($_POST['experience_level'] ?? null);

                $sql_job_seeker = "INSERT INTO Job_Seekers (job_seeker_id)
                                   VALUES ('$user_id')";
                if ($conn->query($sql_job_seeker) === TRUE) {
                    header("Location: job-seekers.php");
                } else {
                    echo "Error inserting into Job Seekers table: " . $conn->error;
                }
                break;

            case 'admin':
                header("Location: admin-panel.php");
                break;

            default:
                echo "Invalid role.";
        }
    } else {
        echo "Error inserting into Users table: " . $conn->error;
    }
}

$conn->close();
?>