<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

$firebase = (new Factory)
    ->withServiceAccount('C:\xampp\htdocs\CAPSTONE_Kapital\Kapital_System\config\kapital-a798a-firebase-adminsdk-fbsvc-f02430e5fa.json');

$auth = $firebase->createAuth();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StartupConnect";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);
$firebase_token = $data['firebase_token'];

try {
    // Verify Firebase token
    $verifiedIdToken = $auth->verifyIdToken($firebase_token);
    $uid = $verifiedIdToken->claims()->get('sub');

    // Get user from MySQL
    $sql = "SELECT user_id, role FROM Users WHERE firebase_uid = '$uid'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row) {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['role'] = $row['role'];

        // Redirect based on role
        switch ($row['role']) {
            case 'entrepreneur':
                echo json_encode(["success" => true, "redirect" => "entrepreneurs.php"]);
                break;
            case 'investor':
                echo json_encode(["success" => true, "redirect" => "investors.php"]);
                break;
            case 'job_seeker':
                echo json_encode(["success" => true, "redirect" => "job-seekers.php"]);
                break;
            case 'admin':
                echo json_encode(["success" => true, "redirect" => "admin-panel.php"]);
                break;
            default:
                echo json_encode(["error" => "Invalid role."]);
        }
    } else {
        echo json_encode(["error" => "User not found"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Firebase Authentication Failed: " . $e->getMessage()]);
}

$conn->close();
?>
