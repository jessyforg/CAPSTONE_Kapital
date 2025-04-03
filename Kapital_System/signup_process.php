<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $retype_password = $_POST['retype_password'];
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Validate passwords match
    if ($password !== $retype_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: sign_up.php");
        exit();
    }

    // Check if email already exists
    $check_email = $conn->prepare("SELECT user_id FROM Users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    if ($check_email->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Email already exists!";
        header("Location: sign_up.php");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into Users table
        $stmt = $conn->prepare("INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
        $stmt->execute();
        $user_id = $conn->insert_id;

        // Handle role-specific data
        if ($role === 'job_seeker') {
            // Insert into Job_Seekers table
            $skills = isset($_POST['skills']) ? json_encode(array_map('trim', explode(',', $_POST['skills']))) : NULL;
            $experience_level = mysqli_real_escape_string($conn, $_POST['experience_level']);
            $desired_role = isset($_POST['desired_role']) ? mysqli_real_escape_string($conn, $_POST['desired_role']) : NULL;
            $location_preference = isset($_POST['location_preference']) ? mysqli_real_escape_string($conn, $_POST['location_preference']) : NULL;

            $stmt = $conn->prepare("INSERT INTO Job_Seekers (job_seeker_id, skills, desired_role, experience_level, location_preference) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $user_id, $skills, $desired_role, $experience_level, $location_preference);
            $stmt->execute();

            // Handle resume upload if provided
            if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/resumes/';
                
                // Create directory if it doesn't exist
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Validate file
                $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                $maxSize = 5 * 1024 * 1024; // 5MB

                if ($_FILES['resume']['size'] > $maxSize) {
                    throw new Exception("Resume file size must be less than 5MB");
                }

                if (!in_array($_FILES['resume']['type'], $allowedTypes)) {
                    throw new Exception("Only PDF, DOC, and DOCX files are allowed for resume");
                }

                // Generate unique filename
                $fileExtension = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
                $newFilename = 'resume_' . uniqid() . '.' . $fileExtension;
                $filePath = $upload_dir . $newFilename;

                // Move uploaded file
                if (move_uploaded_file($_FILES['resume']['tmp_name'], $filePath)) {
                    // Insert into Resumes table
                    $stmt = $conn->prepare("INSERT INTO Resumes (job_seeker_id, file_name, file_path, file_type, file_size, is_active) VALUES (?, ?, ?, ?, ?, TRUE)");
                    $stmt->bind_param("isssi", 
                        $user_id,
                        $_FILES['resume']['name'],
                        $filePath,
                        $_FILES['resume']['type'],
                        $_FILES['resume']['size']
                    );
                    $stmt->execute();
                } else {
                    throw new Exception("Error uploading resume file");
                }
            }
        } elseif ($role === 'entrepreneur') {
            // Insert into Entrepreneurs table
            $stmt = $conn->prepare("INSERT INTO Entrepreneurs (entrepreneur_id) VALUES (?)");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        } elseif ($role === 'investor') {
            // Insert into Investors table with default values
            $stmt = $conn->prepare("INSERT INTO Investors (investor_id, investment_range_min, investment_range_max) VALUES (?, 0, 0)");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }

        // Commit transaction
        $conn->commit();

        // Set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;
        
        header("Location: profile.php");
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $_SESSION['error'] = "Error during registration: " . $e->getMessage();
        header("Location: sign_up.php");
        exit();
    }
}
?>