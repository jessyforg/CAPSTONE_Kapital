<?php
session_start();
include('db_connection.php');

// Function to validate file
function validateFile($file) {
    $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if ($file['size'] > $maxSize) {
        return "File size must be less than 5MB";
    }

    if (!in_array($file['type'], $allowedTypes)) {
        return "Only PDF, DOC, and DOCX files are allowed";
    }

    return null;
}

// Function to generate unique filename
function generateUniqueFilename($originalName, $fileExtension) {
    return uniqid('resume_', true) . '.' . $fileExtension;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'job_seeker') {
        die("Unauthorized access");
    }

    $job_seeker_id = $_SESSION['user_id'];
    $upload_dir = 'uploads/resumes/';

    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        // Validate file
        $error = validateFile($_FILES['resume']);
        if ($error) {
            $_SESSION['error'] = $error;
            header("Location: profile.php");
            exit();
        }

        // Get file extension
        $fileExtension = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
        
        // Generate unique filename
        $newFilename = generateUniqueFilename($_FILES['resume']['name'], $fileExtension);
        $filePath = $upload_dir . $newFilename;

        // Set all existing resumes to inactive
        $stmt = $conn->prepare("UPDATE Resumes SET is_active = FALSE WHERE job_seeker_id = ?");
        $stmt->bind_param("i", $job_seeker_id);
        $stmt->execute();

        // Move uploaded file
        if (move_uploaded_file($_FILES['resume']['tmp_name'], $filePath)) {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO Resumes (job_seeker_id, file_name, file_path, file_type, file_size, is_active) VALUES (?, ?, ?, ?, ?, TRUE)");
            $stmt->bind_param("isssi", 
                $job_seeker_id,
                $_FILES['resume']['name'],
                $filePath,
                $_FILES['resume']['type'],
                $_FILES['resume']['size']
            );

            if ($stmt->execute()) {
                $_SESSION['success'] = "Resume uploaded successfully!";
            } else {
                $_SESSION['error'] = "Error saving resume information to database.";
                unlink($filePath); // Delete the uploaded file
            }
        } else {
            $_SESSION['error'] = "Error uploading file.";
        }
    } else {
        $_SESSION['error'] = "No file uploaded or error in upload.";
    }

    header("Location: profile.php");
    exit();
}
?> 