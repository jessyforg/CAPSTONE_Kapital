<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'job_seeker') {
    die("Unauthorized access");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resume_id'])) {
    $resume_id = $_POST['resume_id'];
    $user_id = $_SESSION['user_id'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Get resume details first
        $stmt = $conn->prepare("SELECT file_path FROM Resumes WHERE resume_id = ? AND job_seeker_id = ?");
        $stmt->bind_param("ii", $resume_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Resume not found or unauthorized");
        }

        $resume = $result->fetch_assoc();

        // Delete the physical file
        if (file_exists($resume['file_path'])) {
            unlink($resume['file_path']);
        }

        // Delete from database
        $stmt = $conn->prepare("DELETE FROM Resumes WHERE resume_id = ? AND job_seeker_id = ?");
        $stmt->bind_param("ii", $resume_id, $user_id);
        $stmt->execute();

        $conn->commit();
        $_SESSION['success'] = "Resume deleted successfully";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error deleting resume: " . $e->getMessage();
    }
}

header("Location: profile.php");
exit();
?> 