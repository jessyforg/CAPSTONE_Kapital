<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

// Firebase Admin SDK Path
$serviceAccountPath = __DIR__ . '/config/kapital-a798a-firebase-adminsdk-fbsvc-f02430e5fa.json';

// Check if Firebase Credentials File Exists
if (!file_exists($serviceAccountPath)) {
    die("Firebase credentials file not found");
}

// Initialize Firebase
$firebase = (new Factory)->withServiceAccount($serviceAccountPath);
$auth = $firebase->createAuth();

// Connect to MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StartupConnect";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle POST Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    $firebase_token = $_POST['firebase_token'] ?? null; // Ensure token is received

    // ✅ Check if Firebase Token is Provided
    if (!$firebase_token) {
        die("Firebase token is missing.");
    }

    try {
        // ✅ Verify Firebase Token
        $verifiedIdToken = $auth->verifyIdToken($firebase_token);
        $firebase_uid = $verifiedIdToken->claims()->get('sub');

        // ✅ Check if User Already Exists
        $check_user = $conn->query("SELECT * FROM Users WHERE email='$email'");
        if ($check_user->num_rows > 0) {
            die("User already exists!");
        }

        // ✅ Insert User into MySQL
        $sql_user = "INSERT INTO Users (firebase_uid, name, email, role) VALUES ('$firebase_uid', '$name', '$email', '$role')";
        if ($conn->query($sql_user) === TRUE) {
            $user_id = $conn->insert_id;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;

            // ✅ Insert into Role-Specific Tables
            switch ($role) {
                case 'entrepreneur':
                    $conn->query("INSERT INTO Entrepreneurs (entrepreneur_id) VALUES ('$user_id')");
                    break;
                case 'investor':
                    $investment_range_min = $conn->real_escape_string($_POST['investment_range_min'] ?? 0);
                    $investment_range_max = $conn->real_escape_string($_POST['investment_range_max'] ?? 0);
                    $conn->query("INSERT INTO Investors (investor_id, investment_range_min, investment_range_max) 
                                  VALUES ('$user_id', '$investment_range_min', '$investment_range_max')");
                    break;
                case 'job_seeker':
                    $experience_level = $conn->real_escape_string($_POST['experience_level'] ?? null);
                    $conn->query("INSERT INTO Job_Seekers (job_seeker_id, experience_level) 
                                  VALUES ('$user_id', " . ($experience_level ? "'$experience_level'" : "NULL") . ")");
                    break;
                default:
                    die("Invalid role.");
            }

            // ✅ Redirect the user to index.php
            header("Location: index.php");
            exit(); // Ensure script stops execution after redirect
        } else {
            die("Error inserting user: " . $conn->error);
        }
    } catch (Exception $e) {
        die("Firebase Authentication Failed: " . $e->getMessage());
    }
}

$conn->close();
?>
