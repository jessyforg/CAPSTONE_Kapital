<?php
include 'db_connection.php';

if (!isset($_GET['job_id'])) {
    echo "Invalid job offer.";
    exit;
}

$job_id = intval($_GET['job_id']);

$query = "SELECT * FROM Jobs WHERE job_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $job = $result->fetch_assoc();
    echo "Job Title: " . $job['role'];
    echo "Description: " . $job['description'];
} else {
    echo "Job not found.";
}
?>