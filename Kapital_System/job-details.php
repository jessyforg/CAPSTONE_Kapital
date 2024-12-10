<?php
// Include the database connection file
include 'db_connection.php';

// Start the session
session_start();

// Check if the user is logged in and has the job seeker role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'job_seeker') {
    header('Location: sign_in.php'); // Redirect to login page if not logged in
    exit;
}

// Check if a job ID is passed in the URL
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Fetch the job details from the Jobs table
    $query = "SELECT * FROM Jobs WHERE job_id = '$job_id'";
    $result = mysqli_query($conn, $query);

    // If the job exists, fetch and display the details
    if (mysqli_num_rows($result) > 0) {
        $job = mysqli_fetch_assoc($result);
    } else {
        echo "Job not found.";
        exit;
    }
} else {
    echo "Job ID is missing.";
    exit;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the application data from the form
    $user_id = $_SESSION['user_id']; // This is the job seeker's ID
    $cover_letter = mysqli_real_escape_string($conn, $_POST['cover_letter']);
    
    // Handle file upload (resume)
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $resume_name = $_FILES['resume']['name'];
        $resume_tmp_name = $_FILES['resume']['tmp_name'];
        $resume_size = $_FILES['resume']['size'];
        $resume_type = $_FILES['resume']['type'];

        // Define allowed file types
        $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $max_file_size = 10 * 1024 * 1024; // 10MB limit

        if (in_array($resume_type, $allowed_types) && $resume_size <= $max_file_size) {
            // Ensure the uploads/resumes directory exists
            $upload_dir = 'uploads/resumes/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true); // Create the directory if it doesn't exist
            }

            $resume_path = $upload_dir . basename($resume_name);

            // Check if the file is uploaded successfully
            if (move_uploaded_file($resume_tmp_name, $resume_path)) {
                // Insert the job application into the database
                $query = "INSERT INTO Applications (job_id, job_seeker_id, status) 
                          VALUES ('$job_id', '$user_id', 'applied')";

                if (mysqli_query($conn, $query)) {
                    // Redirect to job-seekers.php after successful application
                    header('Location: job-seekers.php');
                    exit;
                } else {
                    echo "Error submitting application: " . mysqli_error($conn);
                }
            } else {
                echo "Error uploading resume. Please try again.";
            }
        } else {
            echo "Invalid file type or file size exceeds the limit.";
        }
    } else {
        echo "Please upload your resume.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 2em;
            font-weight: 600;
            color: #000;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
        }

        form input,
        form textarea,
        form button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1em;
        }

        form button {
            background-color: #f3c000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            margin-top: 15px;
        }

        form button:hover {
            background-color: #ffab00;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="section-title">Apply for <?php echo $job['role']; ?></h1>

        <form method="POST" enctype="multipart/form-data">
            <label for="cover_letter">Cover Letter:</label>
            <textarea name="cover_letter" rows="5" required></textarea><br>

            <label for="resume">Resume (PDF, DOC, DOCX):</label>
            <input type="file" name="resume" accept=".pdf, .doc, .docx" required><br>

            <button type="submit">Submit Application</button>
        </form>
    </div>
</body>

</html>
