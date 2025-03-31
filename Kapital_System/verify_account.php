<?php
session_start();
include('db_connection.php');
include('navbar.php');

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

$user_id = $_SESSION['user_id'];

// Handle document upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document'])) {
    $document_type = mysqli_real_escape_string($conn, $_POST['document_type']);
    $file = $_FILES['document'];
    
    // Validate file type
    $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
    if (!in_array($file['type'], $allowed_types)) {
        $error_message = "Invalid file type. Please upload PDF, JPEG, or PNG files only.";
    }
    // Validate file size (5MB max)
    elseif ($file['size'] > 5 * 1024 * 1024) {
        $error_message = "File size too large. Maximum size is 5MB.";
    }
    else {
        // Create upload directory if it doesn't exist
        $upload_dir = 'uploads/verification_documents/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Generate unique filename
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '_' . $user_id . '.' . $file_extension;
        $file_path = $upload_dir . $file_name;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Insert document record into database
            $insert_query = "INSERT INTO Verification_Documents (user_id, document_type, file_name, file_path, file_type, file_size) 
                           VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("issssi", $user_id, $document_type, $file_name, $file_path, $file['type'], $file['size']);
            
            if ($stmt->execute()) {
                // Update user's verification status to pending
                $update_user = "UPDATE Users SET verification_status = 'pending' WHERE user_id = ?";
                $stmt = $conn->prepare($update_user);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                
                $success_message = "Document uploaded successfully! Your verification is pending review.";
            } else {
                $error_message = "Error saving document information to database.";
            }
        } else {
            $error_message = "Error uploading file.";
        }
    }
}

// Fetch user's current verification status and documents
$user_query = "SELECT verification_status FROM Users WHERE user_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

$documents_query = "SELECT * FROM Verification_Documents WHERE user_id = ? ORDER BY uploaded_at DESC";
$stmt = $conn->prepare($documents_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$documents_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .verification-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(243, 192, 0, 0.2);
        }

        h1 {
            color: #f3c000;
            margin-bottom: 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-bottom: 20px;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }

        .status-verified {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .status-not-approved {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .upload-form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #f3c000;
        }

        select, input[type="file"] {
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(243, 192, 0, 0.3);
            border-radius: 8px;
            color: #fff;
            margin-bottom: 10px;
        }

        select {
            background-color: #2C2F33;
            color: #fff;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23f3c000' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: calc(100% - 15px) center;
            padding-right: 40px;
        }

        select option {
            background-color: #2C2F33;
            color: #fff;
            padding: 10px;
        }

        select:focus {
            outline: none;
            border-color: #f3c000;
            box-shadow: 0 0 0 2px rgba(243, 192, 0, 0.2);
        }

        select:hover {
            border-color: #f3c000;
        }

        .submit-btn {
            background: linear-gradient(45deg, #f3c000, #ffab00);
            color: #000;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 192, 0, 0.3);
        }

        .documents-list {
            margin-top: 30px;
        }

        .document-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .document-info {
            flex-grow: 1;
        }

        .document-status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid #28a745;
            color: #28a745;
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid #dc3545;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verification-section">
            <h1>Account Verification</h1>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="status-badge status-<?php echo strtolower($user['verification_status']); ?>">
                <i class="fas <?php
                    switch($user['verification_status']) {
                        case 'pending':
                            echo 'fa-clock';
                            break;
                        case 'verified':
                            echo 'fa-check-circle';
                            break;
                        case 'not approved':
                            echo 'fa-times-circle';
                            break;
                    }
                ?>"></i>
                <?php echo ucfirst($user['verification_status']); ?>
            </div>

            <?php if ($user['verification_status'] !== 'verified'): ?>
                <form method="POST" enctype="multipart/form-data" class="upload-form">
                    <div class="form-group">
                        <label for="document_type">Document Type</label>
                        <select name="document_type" id="document_type" required>
                            <option value="">Select document type</option>
                            <option value="government_id">Government ID</option>
                            <option value="business_registration">Business Registration</option>
                            <option value="professional_license">Professional License</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="document">Upload Document</label>
                        <input type="file" name="document" id="document" required accept=".pdf,.jpg,.jpeg,.png">
                        <small>Supported formats: PDF, JPEG, PNG (Max size: 5MB)</small>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-upload"></i> Upload Document
                    </button>
                </form>
            <?php endif; ?>

            <?php if ($documents_result->num_rows > 0): ?>
                <div class="documents-list">
                    <h2>Uploaded Documents</h2>
                    <?php while ($document = $documents_result->fetch_assoc()): ?>
                        <div class="document-item">
                            <div class="document-info">
                                <strong><?php echo ucwords(str_replace('_', ' ', $document['document_type'])); ?></strong>
                                <br>
                                <small>Uploaded: <?php echo date('F j, Y g:i A', strtotime($document['uploaded_at'])); ?></small>
                            </div>
                            <div class="document-status status-<?php echo strtolower($document['status']); ?>">
                                <?php echo ucfirst($document['status']); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 