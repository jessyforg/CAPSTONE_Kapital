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
    $document_number = mysqli_real_escape_string($conn, $_POST['document_number']);
    $issue_date = !empty($_POST['issue_date']) ? $_POST['issue_date'] : NULL;
    $expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : NULL;
    $issuing_authority = mysqli_real_escape_string($conn, $_POST['issuing_authority']);
    $file = $_FILES['document'];
    
    // Validate file type
    $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
    if (!in_array($file['type'], $allowed_types)) {
        $_SESSION['error_message'] = "Invalid file type. Please upload PDF, JPEG, or PNG files only.";
    }
    // Validate file size (5MB max)
    elseif ($file['size'] > 5 * 1024 * 1024) {
        $_SESSION['error_message'] = "File size too large. Maximum size is 5MB.";
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
            $insert_query = "INSERT INTO Verification_Documents (
                user_id, 
                document_type, 
                document_number,
                issue_date,
                expiry_date,
                issuing_authority,
                file_name, 
                file_path, 
                file_type, 
                file_size
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param(
                "issssssssi", 
                $user_id, 
                $document_type, 
                $document_number,
                $issue_date,
                $expiry_date,
                $issuing_authority,
                $file_name, 
                $file_path, 
                $file['type'], 
                $file['size']
            );
            
            if ($stmt->execute()) {
                // After successful document upload, check document counts and update status accordingly
                $check_docs = "SELECT 
                    COUNT(CASE WHEN status = 'not approved' THEN 1 END) as not_approved_count,
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count,
                    COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved_count
                    FROM Verification_Documents WHERE user_id = ?";
                $stmt = $conn->prepare($check_docs);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $counts = $result->fetch_assoc();

                // Update user status based on document counts
                if ($counts['not_approved_count'] > 0) {
                    $new_status = 'not approved';
                } elseif ($counts['pending_count'] > 0) {
                    $new_status = 'pending';
                } elseif ($counts['approved_count'] > 0 && $counts['approved_count'] == ($counts['not_approved_count'] + $counts['pending_count'] + $counts['approved_count'])) {
                    $new_status = 'verified';
                } else {
                    $new_status = 'pending';
                }

                // Update user's verification status
                $update_user = "UPDATE Users SET verification_status = ? WHERE user_id = ?";
                $stmt = $conn->prepare($update_user);
                $stmt->bind_param("si", $new_status, $user_id);
                $stmt->execute();
                
                $_SESSION['success_message'] = "Document uploaded successfully! Your verification request has been submitted and is pending review.";
                header("Location: verify_account.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Error: Unable to save document information. Please try again.";
                header("Location: verify_account.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Error: Unable to upload file. Please try again.";
            header("Location: verify_account.php");
            exit();
        }
    }
}

// Get messages from session
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Clear messages from session after getting them
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Fetch user's current verification status and documents
$user_query = "SELECT u.verification_status, 
    (SELECT COUNT(*) FROM Verification_Documents vd WHERE vd.user_id = u.user_id AND vd.status = 'not approved') as not_approved_count,
    (SELECT COUNT(*) FROM Verification_Documents vd WHERE vd.user_id = u.user_id AND vd.status = 'pending') as pending_count,
    (SELECT COUNT(*) FROM Verification_Documents vd WHERE vd.user_id = u.user_id AND vd.status = 'approved') as approved_count
FROM Users u 
WHERE u.user_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Update user's verification status based on document statuses
if ($user['not_approved_count'] > 0) {
    // If any document is not approved, set user status to not approved
    $update_status = "UPDATE Users SET verification_status = 'not approved' WHERE user_id = ?";
    $stmt = $conn->prepare($update_status);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user['verification_status'] = 'not approved';
} elseif ($user['pending_count'] > 0) {
    // If any document is pending, set user status to pending
    $update_status = "UPDATE Users SET verification_status = 'pending' WHERE user_id = ?";
    $stmt = $conn->prepare($update_status);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user['verification_status'] = 'pending';
} elseif ($user['approved_count'] > 0 && $user['pending_count'] == 0 && $user['not_approved_count'] == 0) {
    // If all documents are approved, set user status to verified
    $update_status = "UPDATE Users SET verification_status = 'verified' WHERE user_id = ?";
    $stmt = $conn->prepare($update_status);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user['verification_status'] = 'verified';
}

// Fetch all documents for this user
$documents_query = "SELECT vd.*, 
                          COALESCE(vd.rejection_reason, 'Your document was not approved. Please re-upload a valid document.') as display_rejection_reason 
                   FROM Verification_Documents vd 
                   WHERE vd.user_id = ? 
                   ORDER BY vd.uploaded_at DESC";
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
            background: #2C2F33;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid rgba(243, 192, 0, 0.2);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .document-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .document-type {
            font-size: 1.1em;
            color: #f3c000;
            margin-bottom: 10px;
            text-transform: capitalize;
        }

        .document-details {
            margin-top: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
        }

        .document-details div {
            margin-bottom: 8px;
            color: #e1e1e1;
        }

        .document-details div:last-child {
            margin-bottom: 0;
        }

        .document-status {
            display: flex;
            gap: 10px;
        }

        .view-btn, .reupload-btn {
            padding: 8px 15px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .view-btn {
            background: #3F51B5;
            color: #fff;
        }

        .reupload-btn {
            background: #f3c000;
            color: #000;
        }

        .view-btn:hover, .reupload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .rejection-reason-box {
            margin-top: 15px;
            padding: 15px;
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 6px;
            color: #dc3545;
        }

        .rejection-reason-box strong {
            display: block;
            color: #dc3545;
            margin-bottom: 8px;
            font-size: 1.05em;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-approved {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .status-not-approved {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
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

        .document-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .view-btn, .btn-edit, .btn-reupload {
            padding: 8px 15px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .view-btn {
            background: #3F51B5;
            color: #fff;
        }

        .btn-edit {
            background: #f3c000;
            color: #000;
        }

        .btn-reupload {
            background: #dc3545;
            color: #fff;
        }

        .view-btn:hover, .btn-edit:hover, .btn-reupload:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .rejection-reason-box {
            margin-top: 15px;
            padding: 15px;
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 6px;
            color: #dc3545;
        }

        .rejection-reason-box strong {
            display: block;
            color: #dc3545;
            margin-bottom: 8px;
            font-size: 1.05em;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-not-approved {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            overflow-y: auto;
        }

        .modal-content {
            background-color: #2C2F33;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .close-modal {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            cursor: pointer;
            color: #f3c000;
            transition: color 0.3s ease;
        }

        .close-modal:hover {
            color: #ffab00;
        }

        .modal h2 {
            color: #f3c000;
            margin-bottom: 20px;
            padding-right: 30px;
        }

        .btn-reupload {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
            background-color: #28a745;
            color: #fff;
        }

        .btn-reupload:hover {
            background-color: #218838;
        }

        #reuploadModal .modal-content {
            background-color: #2C2F33;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #reuploadModal .close-modal {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            cursor: pointer;
            color: #f3c000;
            transition: color 0.3s ease;
        }

        #reuploadModal .close-modal:hover {
            color: #ffab00;
        }

        #reuploadModal h2 {
            color: #f3c000;
            margin-bottom: 20px;
            padding-right: 30px;
        }

        /* Select2 Custom Styles */
        .select2-container--default .select2-selection--single {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(243, 192, 0, 0.3);
            border-radius: 8px;
            color: #fff;
            height: 42px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #fff;
            line-height: 42px;
            padding-left: 15px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .select2-container--default .select2-results__option {
            background-color: #2C2F33;
            color: #fff;
            padding: 10px 15px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #f3c000;
            color: #000;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #2C2F33;
            color: #fff;
            border: 1px solid rgba(243, 192, 0, 0.3);
            border-radius: 4px;
            padding: 8px;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            outline: none;
            border-color: #f3c000;
        }

        .select2-dropdown {
            background-color: #2C2F33;
            border: 1px solid rgba(243, 192, 0, 0.3);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: rgba(243, 192, 0, 0.2);
            color: #f3c000;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #f3c000;
            color: #000;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #f3c000 transparent transparent transparent;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #f3c000 transparent;
        }

        /* Document Details Form Styles */
        .document-details-form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .document-details-form .form-group {
            margin-bottom: 0;
        }

        .document-details-form input {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(243, 192, 0, 0.3);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 14px;
        }

        .document-details-form input:focus {
            outline: none;
            border-color: #f3c000;
            box-shadow: 0 0 0 2px rgba(243, 192, 0, 0.2);
        }

        .document-details-form input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .document-details-form input[type="date"] {
            color-scheme: dark;
        }

        /* Edit Modal Styles */
        .edit-document-details {
            margin-top: 20px;
        }

        .edit-document-details .form-group {
            margin-bottom: 15px;
        }

        .edit-document-details label {
            display: block;
            margin-bottom: 5px;
            color: #f3c000;
        }

        .edit-document-details input {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(243, 192, 0, 0.3);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .edit-document-details input:focus {
            outline: none;
            border-color: #f3c000;
            box-shadow: 0 0 0 2px rgba(243, 192, 0, 0.2);
        }

        .approval-info, .pending-info {
            margin-top: 10px;
            padding: 10px;
            border-radius: 8px;
        }
        
        .approval-info {
            background-color: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #28a745;
        }
        
        .pending-info {
            background-color: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.3);
            color: #ffc107;
        }
        
        .document-details-display {
            margin-top: 10px;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
        }
        
        .detail-item {
            margin-bottom: 5px;
        }
        
        .detail-item strong {
            color: #f3c000;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verification-section">
            <h1>Account Verification</h1>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
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
                <?php 
                    $status_message = '';
                    switch($user['verification_status']) {
                        case 'pending':
                            if ($user['pending_count'] > 0) {
                                $status_message = 'Your verification is pending review';
                            } else {
                                $status_message = 'Please upload verification documents';
                            }
                            break;
                        case 'verified':
                            $status_message = 'Your account is verified';
                            break;
                        case 'not approved':
                            $status_message = 'Your verification was not approved';
                            break;
                    }
                    echo $status_message;
                ?>
            </div>

            <?php
            // Check if user has any pending documents
            $pending_query = "SELECT COUNT(*) as pending_count FROM Verification_Documents WHERE user_id = ? AND status = 'pending'";
            $stmt = $conn->prepare($pending_query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $pending_result = $stmt->get_result();
            $pending_count = $pending_result->fetch_assoc()['pending_count'];
            ?>

            <?php if ($user['verification_status'] !== 'verified' && $pending_count == 0): ?>
                <form method="POST" enctype="multipart/form-data" class="upload-form">
                    <div class="form-group">
                        <label for="document_type">Document Type</label>
                        <select name="document_type" id="document_type" class="form-control" required>
                            <option value="">Select document type</option>
                            <option value="government_id">Government ID</option>
                            <option value="passport">Passport</option>
                            <option value="drivers_license">Driver's License</option>
                            <option value="business_registration">Business Registration</option>
                            <option value="professional_license">Professional License</option>
                            <option value="tax_certificate">Tax Certificate</option>
                            <option value="bank_statement">Bank Statement</option>
                            <option value="utility_bill">Utility Bill</option>
                            <option value="proof_of_address">Proof of Address</option>
                            <option value="employment_certificate">Employment Certificate</option>
                            <option value="educational_certificate">Educational Certificate</option>
                            <option value="other">Other Document</option>
                        </select>
                    </div>

                    <div class="document-details-form">
                        <div class="form-group">
                            <label for="document_number">Document Number</label>
                            <input type="text" name="document_number" id="document_number" class="form-control" placeholder="Enter document number">
                        </div>

                        <div class="form-group">
                            <label for="issue_date">Issue Date</label>
                            <input type="date" name="issue_date" id="issue_date" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="date" name="expiry_date" id="expiry_date" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="issuing_authority">Issuing Authority</label>
                            <input type="text" name="issuing_authority" id="issuing_authority" class="form-control" placeholder="Enter issuing authority">
                        </div>
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
            <?php elseif ($pending_count > 0): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> You already have a pending verification request. Please wait for the review process to complete.
                </div>
            <?php endif; ?>

            <?php if ($documents_result->num_rows > 0): ?>
                <div class="documents-list">
                    <h2>Uploaded Documents</h2>
                    <?php 
                    $processed_documents = array();
                    while ($document = $documents_result->fetch_assoc()): 
                        // Skip if we've already processed this document type
                        if (in_array($document['document_type'], $processed_documents)) {
                            continue;
                        }
                        $processed_documents[] = $document['document_type'];
                    ?>
                        <div class="document-item">
                            <div class="document-info">
                                <div>
                                    <div class="document-type">
                                        <?php echo ucwords(str_replace('_', ' ', $document['document_type'])); ?>
                                    </div>
                                    <span class="status-badge status-<?php echo strtolower($document['status']); ?>">
                                        <i class="fas <?php
                                            switch($document['status']) {
                                                case 'pending':
                                                    echo 'fa-clock';
                                                    break;
                                                case 'approved':
                                                    echo 'fa-check-circle';
                                                    break;
                                                case 'not approved':
                                                    echo 'fa-times-circle';
                                                    break;
                                            }
                                        ?>"></i>
                                        <?php echo ucfirst($document['status']); ?>
                                    </span>
                                </div>
                                <div class="document-actions">
                                    <div class="action-buttons">
                                        <a href="<?php echo htmlspecialchars($document['file_path']); ?>" target="_blank" class="view-btn">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode([
                                            'documentId' => $document['document_id'],
                                            'documentType' => $document['document_type'],
                                            'documentNumber' => $document['document_number'],
                                            'issueDate' => $document['issue_date'],
                                            'expiryDate' => $document['expiry_date'],
                                            'issuingAuthority' => $document['issuing_authority']
                                        ])); ?>)" class="btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <?php if ($document['status'] === 'not approved'): ?>
                                            <button onclick="openReuploadModal(<?php echo $document['document_id']; ?>)" class="btn-reupload">
                                                <i class="fas fa-upload"></i> Re-upload
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <?php if ($document['status'] === 'not approved'): ?>
                                <div class="rejection-reason-box">
                                    <strong><i class="fas fa-exclamation-circle"></i> Rejection Reason:</strong>
                                    <?php echo !empty($document['display_rejection_reason']) ? htmlspecialchars($document['display_rejection_reason']) : 'Your document was not approved. Please re-upload a valid document.'; ?>
                                </div>
                            <?php elseif ($document['status'] === 'approved'): ?>
                                <div class="approval-info">
                                    <i class="fas fa-check-circle"></i> Your document has been verified and approved.
                                </div>
                            <?php elseif ($document['status'] === 'pending'): ?>
                                <div class="pending-info">
                                    <i class="fas fa-clock"></i> Your document is currently under review.
                                </div>
                            <?php endif; ?>

                            <div class="document-details">
                                <?php if (!empty($document['document_number'])): ?>
                                    <div><strong>Document Number:</strong> <?php echo htmlspecialchars($document['document_number']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($document['issue_date'])): ?>
                                    <div><strong>Issue Date:</strong> <?php echo date('F j, Y', strtotime($document['issue_date'])); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($document['expiry_date'])): ?>
                                    <div><strong>Expiry Date:</strong> <?php echo date('F j, Y', strtotime($document['expiry_date'])); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($document['issuing_authority'])): ?>
                                    <div><strong>Issuing Authority:</strong> <?php echo htmlspecialchars($document['issuing_authority']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Edit Document Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
            <h2>Edit Document Details</h2>
            <form action="manage_verification.php" method="post">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="document_id" id="edit_document_id">
                <div class="edit-document-details">
                    <div class="form-group">
                        <label for="edit_document_type">Document Type</label>
                        <select name="document_type" id="edit_document_type" class="form-control" required>
                            <option value="government_id">Government ID</option>
                            <option value="passport">Passport</option>
                            <option value="drivers_license">Driver's License</option>
                            <option value="business_registration">Business Registration</option>
                            <option value="professional_license">Professional License</option>
                            <option value="tax_certificate">Tax Certificate</option>
                            <option value="bank_statement">Bank Statement</option>
                            <option value="utility_bill">Utility Bill</option>
                            <option value="proof_of_address">Proof of Address</option>
                            <option value="employment_certificate">Employment Certificate</option>
                            <option value="educational_certificate">Educational Certificate</option>
                            <option value="other">Other Document</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_document_number">Document Number</label>
                        <input type="text" name="document_number" id="edit_document_number" class="form-control" placeholder="Enter document number">
                    </div>

                    <div class="form-group">
                        <label for="edit_issue_date">Issue Date</label>
                        <input type="date" name="issue_date" id="edit_issue_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="edit_expiry_date">Expiry Date</label>
                        <input type="date" name="expiry_date" id="edit_expiry_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="edit_issuing_authority">Issuing Authority</label>
                        <input type="text" name="issuing_authority" id="edit_issuing_authority" class="form-control" placeholder="Enter issuing authority">
                    </div>
                </div>
                <button type="submit" class="submit-btn">Update Document Details</button>
            </form>
        </div>
    </div>

    <!-- Re-upload Document Modal -->
    <div id="reuploadModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeReuploadModal()">&times;</span>
            <h2>Re-upload Document</h2>
            <form action="manage_verification.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="reupload">
                <input type="hidden" name="document_id" id="reupload_document_id">
                <div class="form-group">
                    <label for="new_document">Upload New Document</label>
                    <input type="file" name="new_document" id="new_document" required accept=".pdf,.jpg,.jpeg,.png">
                    <small>Supported formats: PDF, JPEG, PNG (Max size: 5MB)</small>
                </div>
                <button type="submit" class="submit-btn">
                    <i class="fas fa-upload"></i> Upload New Document
                </button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 only on location dropdown
            $('#location').select2({
                theme: 'default',
                width: '100%',
                placeholder: 'Search or select a location',
                allowClear: true,
                minimumInputLength: 1
            });
        });

        function openEditModal(documentData) {
            const data = typeof documentData === 'string' ? JSON.parse(documentData) : documentData;
            document.getElementById('editModal').style.display = 'block';
            document.getElementById('edit_document_id').value = data.documentId;
            document.getElementById('edit_document_type').value = data.documentType;
            document.getElementById('edit_document_number').value = data.documentNumber || '';
            document.getElementById('edit_issue_date').value = data.issueDate || '';
            document.getElementById('edit_expiry_date').value = data.expiryDate || '';
            document.getElementById('edit_issuing_authority').value = data.issuingAuthority || '';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function openReuploadModal(documentId) {
            document.getElementById('reuploadModal').style.display = 'block';
            document.getElementById('reupload_document_id').value = documentId;
        }

        function closeReuploadModal() {
            document.getElementById('reuploadModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const editModal = document.getElementById('editModal');
            const reuploadModal = document.getElementById('reuploadModal');
            if (event.target == editModal) {
                editModal.style.display = 'none';
            }
            if (event.target == reuploadModal) {
                reuploadModal.style.display = 'none';
            }
        }
    </script>
</body>
</html> 