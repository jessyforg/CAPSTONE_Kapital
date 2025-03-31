<?php
// Include database connection
include 'db_connection.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['verification_doc'])) {
    $file = $_FILES['verification_doc'];
    $document_type = $_POST['document_type'];
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
    if (!in_array($file['type'], $allowed_types)) {
        $error = "Invalid file type. Please upload a JPG, PNG, or PDF file.";
    }
    
    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        $error = "File size too large. Maximum size is 5MB.";
    }
    
    if (empty($error)) {
        // Create upload directory if it doesn't exist
        $upload_dir = 'uploads/verification_docs/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Generate unique filename
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . $user_id . '.' . $file_extension;
        $filepath = $upload_dir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Insert into database
            $query = "INSERT INTO Verification_Documents (user_id, document_type, file_name, file_path, file_type, file_size) 
                     VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("issssi", 
                $user_id, 
                $document_type, 
                $file['name'], 
                $filepath, 
                $file['type'], 
                $file['size']
            );
            
            if ($stmt->execute()) {
                $message = "Document uploaded successfully. Please wait for admin verification.";
            } else {
                $error = "Error uploading document. Please try again.";
            }
        } else {
            $error = "Error moving uploaded file. Please try again.";
        }
    }
}

// Get user's current verification status
$status_query = "SELECT verification_status FROM Users WHERE user_id = ?";
$stmt = $conn->prepare($status_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_status = $result->fetch_assoc();

// Get user's uploaded documents
$docs_query = "SELECT * FROM Verification_Documents WHERE user_id = ? ORDER BY uploaded_at DESC";
$stmt = $conn->prepare($docs_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$documents = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Verification Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #2C2F33;
            color: #f9f9f9;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #23272A;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #7289DA;
            margin-bottom: 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .status-pending {
            background-color: #FFA726;
            color: #fff;
        }

        .status-verified {
            background-color: #4CAF50;
            color: #fff;
        }

        .status-rejected {
            background-color: #F44336;
            color: #fff;
        }

        .upload-form {
            background: #2C2F33;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #7289DA;
        }

        select, input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #40444B;
            border-radius: 4px;
            background: #40444B;
            color: #fff;
            font-family: inherit;
        }

        .btn-upload {
            background-color: #7289DA;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-upload:hover {
            background-color: #5b6eae;
        }

        .message {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .success {
            background-color: #4CAF50;
            color: white;
        }

        .error {
            background-color: #F44336;
            color: white;
        }

        .documents-list {
            margin-top: 30px;
        }

        .document-item {
            background: #2C2F33;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .document-info {
            flex-grow: 1;
        }

        .document-status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #FFA726;
            color: #fff;
        }

        .status-approved {
            background-color: #4CAF50;
            color: #fff;
        }

        .status-rejected {
            background-color: #F44336;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Verification Document Upload</h1>
        
        <div class="status-badge status-<?php echo $user_status['verification_status']; ?>">
            Current Status: <?php echo ucfirst($user_status['verification_status']); ?>
        </div>

        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="upload-form">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="document_type">Document Type:</label>
                    <select name="document_type" id="document_type" required>
                        <option value="government_id">Government ID</option>
                        <option value="business_registration">Business Registration</option>
                        <option value="professional_license">Professional License</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="verification_doc">Upload Document:</label>
                    <input type="file" name="verification_doc" id="verification_doc" required 
                           accept=".jpg,.jpeg,.png,.pdf">
                    <small style="color: #888;">Accepted formats: JPG, PNG, PDF. Max size: 5MB</small>
                </div>

                <button type="submit" class="btn-upload">Upload Document</button>
            </form>
        </div>

        <div class="documents-list">
            <h2>Uploaded Documents</h2>
            <?php if ($documents->num_rows > 0): ?>
                <?php while ($doc = $documents->fetch_assoc()): ?>
                    <div class="document-item">
                        <div class="document-info">
                            <strong><?php echo htmlspecialchars($doc['document_type']); ?></strong>
                            <br>
                            <small>Uploaded: <?php echo date('M j, Y', strtotime($doc['uploaded_at'])); ?></small>
                        </div>
                        <span class="document-status status-<?php echo $doc['status']; ?>">
                            <?php echo ucfirst($doc['status']); ?>
                        </span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No documents uploaded yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 