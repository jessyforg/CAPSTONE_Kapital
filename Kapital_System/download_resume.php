<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['job_seeker_id'])) {
    die("Unauthorized access");
}

$job_seeker_id = $_GET['job_seeker_id'];

// Get the active resume
$stmt = $conn->prepare("SELECT * FROM Resumes WHERE job_seeker_id = ? AND is_active = TRUE");
$stmt->bind_param("i", $job_seeker_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No resume found");
}

$resume = $result->fetch_assoc();

// Verify file exists
if (!file_exists($resume['file_path'])) {
    die("Resume file not found");
}

// Set headers for download
header('Content-Type: ' . $resume['file_type']);
header('Content-Disposition: attachment; filename="' . $resume['file_name'] . '"');
header('Content-Length: ' . $resume['file_size']);

// Output file
readfile($resume['file_path']);
exit();
?> 