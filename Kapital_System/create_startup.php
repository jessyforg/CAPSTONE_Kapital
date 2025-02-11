<?php
session_start(); // Start the session
include('navbar.php');
include('db_connection.php'); // Assuming a separate file for database connection

// Redirect if the user is not logged in or does not have the entrepreneur role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

// Retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

// Check if the entrepreneur exists in the Entrepreneurs table
$query = "SELECT entrepreneur_id FROM Entrepreneurs WHERE entrepreneur_id = '$user_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    die("Entrepreneur profile not found. Please ensure you have registered as an entrepreneur.");
}

// Function to handle file uploads
function uploadFile($file, $upload_dir, $allowed_types) {
    if (!empty($file["name"])) {
        $file_name = basename($file["name"]);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $new_file_name = uniqid() . "_" . $file_name;
        $target_file = $upload_dir . $new_file_name;

        if (!in_array($file_ext, $allowed_types)) {
            return ["success" => false, "message" => "Invalid file type: " . $file_ext];
        }

        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return ["success" => true, "path" => $target_file];
        } else {
            return ["success" => false, "message" => "File upload failed."];
        }
    }
    return ["success" => true, "path" => ""]; // No file uploaded
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve the form data
    $startup_name = mysqli_real_escape_string($conn, $_POST['startup_name']);
    $industry = mysqli_real_escape_string($conn, $_POST['industry']);
    $funding_stage = mysqli_real_escape_string($conn, $_POST['funding_stage']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $pitch_deck_url = mysqli_real_escape_string($conn, $_POST['pitch_deck_url']);
    $business_plan_url = mysqli_real_escape_string($conn, $_POST['business_plan_url']);
    
    // Ensure the startup name is unique
    $check_query = "SELECT * FROM Startups WHERE name = '$startup_name'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Startup name already exists. Please choose a different name.');</script>";
    } else {
        // Handle logo upload
        $logo_upload = uploadFile($_FILES["logo"], "uploads/logos/", ["jpg", "jpeg", "png"]);
        if (!$logo_upload["success"]) {
            echo "<script>alert('" . $logo_upload["message"] . "');</script>";
        }
        $logo_path = $logo_upload["path"];

        // Handle file upload (Video Pitch / General File)
        $file_upload = uploadFile($_FILES["file"], "uploads/files/", ["mp4", "avi", "mov", "pdf", "docx", "pptx"]);
        if (!$file_upload["success"]) {
            echo "<script>alert('" . $file_upload["message"] . "');</script>";
        }
        $file_path = $file_upload["path"];

        // Insert a new record into Startups table
        $query_insert_startup = "
            INSERT INTO Startups (
                entrepreneur_id, 
                name, 
                industry, 
                funding_stage, 
                description, 
                location, 
                website, 
                pitch_deck_url, 
                business_plan_url,
                logo_url,
                video_url
            ) VALUES (
                '$user_id', 
                '$startup_name', 
                '$industry', 
                '$funding_stage', 
                '$description', 
                '$location', 
                '$website', 
                '$pitch_deck_url', 
                '$business_plan_url',
                '$logo_path',
                '$file_path'
            )
        ";
        $result_insert_startup = mysqli_query($conn, $query_insert_startup);

        if ($result_insert_startup) {
            echo "<script>alert('Startup profile created successfully!');</script>";
        } else {
            echo "<script>alert('Error creating startup profile: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Startup Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(45deg, #343131, #808080);
            color: #fff;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative; /* Added for absolute positioning of logo upload */
        }

        h1 {
            color: #f4f4f4;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #ddd;
        }

        input,
        textarea,
        select,
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        input,
        textarea,
        select {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 16px;
        }

        input::placeholder,
        textarea::placeholder {
            color: #bbb;
            font-size: 16px;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.3);
        }

        button {
            background: #D8A25E;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
            border-radius: 4px;
        }

        button:hover {
            background: #D8A25E;
        }

        textarea {
            resize: vertical;
            height: 150px;
        }

        select {
            padding: 12px 10px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        select option {
            background-color: #333;
            color: white;
        }

        .success {
            color: #4caf50;
        }

        .error {
            color: #f44336;
        }

        .logo-upload {
            position: absolute;
            top: 20px; /* Adjusted for better visibility */
            right: 20px; /* Adjusted for better visibility */
            cursor: pointer;
            text-align: center; /* Center the label text */
        }

        .logo-upload img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            padding: 5px;
        }

        .logo-upload input {
            display: none;
        }

        .logo-label {
            font-size: 14px; /* Adjust font size */
            color: #ddd; /* Label color */
            margin-top: 5px; /* Space between icon and label */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Create Your Startup Profile</h1>
        <div class="logo-upload">
            <label for="logo">
                <img src="assets/upload_icon.png" alt="Upload Logo">
            </label>
            <span class="logo-label">Upload Startup Logo</span>
            <input type="file" id="logo" name="logo" accept="image/png, image/jpeg">
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="startup_name">Startup Name</label>
                <input type="text" id="startup_name" name="startup_name" placeholder="Enter your startup's name" required>
            </div>

            <div class="form-group">
                <label for="industry">Industry</label>
                <input type="text" id="industry" name="industry" placeholder="Enter your industry (e.g., Technology, Health)" required>
            </div>

            <div class="form-group">
                <label for="funding_stage">Funding Stage</label>
                <select id="funding_stage" name="funding_stage" required>
                    <option value="seed">Seed</option>
                    <option value="series_a">Series A</option>
                    <option value="series_b">Series B</option>
                    <option value="series_c">Series C</option>
                    <option value="exit">Exit</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Startup Description</label>
                <textarea id="description" name="description" placeholder="Provide a brief description of your startup" required></textarea>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" placeholder="Enter your location">
            </div>

            <div class="form-group">
                <label for="file">Video Pitch / File Upload</label>
                <input type="file" id="file" name="file" accept="video/mp4, video/avi, video/mov, application/pdf, application/msword, application/vnd.ms-powerpoint">
            </div>

            <div class="form-group">
                <label for="website">Website</label>
                <input type="text" id="website" name="website" placeholder="Enter your website URL">
            </div>

            <div class="form-group">
                <label for="pitch_deck_url">Pitch Deck URL</label>
                <input type="text" id="pitch_deck_url" name="pitch_deck_url" placeholder="Enter the URL to your pitch deck">
            </div>

            <div class="form-group">
                <label for="business_plan_url">Business Plan URL</label>
                <input type="text" id="business_plan_url" name="business_plan_url" placeholder="Enter the URL to your business plan">
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>

</html>