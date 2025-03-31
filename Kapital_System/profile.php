<?php
session_start();
include('navbar.php');
include('db_connection.php');

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

// Determine which user's profile to show
$viewing_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];
$is_own_profile = $viewing_user_id == $_SESSION['user_id'];

// Retrieve user details from the database
$stmt = $conn->prepare("SELECT * FROM Users WHERE user_id = ?");
$stmt->bind_param("i", $viewing_user_id);
$stmt->execute();
$result_user = $stmt->get_result();

if ($result_user && $result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    die("User not found in the database.");
}

// Handle profile updates - only if it's the user's own profile
if ($is_own_profile && isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email already exists for another user
    $check_email = $conn->prepare("SELECT user_id FROM Users WHERE email = ? AND user_id != ?");
    $check_email->bind_param("si", $email, $viewing_user_id);
    $check_email->execute();
    $email_result = $check_email->get_result();
    
    if ($email_result->num_rows > 0) {
        $error_message = "This email is already in use by another account.";
    } else {
        $query_update = "UPDATE Users SET name = ?, email = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($query_update);
        $update_stmt->bind_param("ssi", $name, $email, $viewing_user_id);
        
        if ($update_stmt->execute()) {
            $success_message = "Profile updated successfully!";
            // Update session variables
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            header("Refresh:2"); // Refresh after 2 seconds
        } else {
            $error_message = "Error updating profile: " . $conn->error;
        }
    }
}

// Handle password change - only if it's the user's own profile
if ($is_own_profile && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (password_verify($current_password, $user['password'])) {
        if (strlen($new_password) < 8) {
            $error_message = "New password must be at least 8 characters long.";
        } elseif ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query_password = "UPDATE Users SET password = ? WHERE user_id = ?";
            $pwd_stmt = $conn->prepare($query_password);
            $pwd_stmt->bind_param("si", $hashed_password, $viewing_user_id);
            
            if ($pwd_stmt->execute()) {
                $success_message = "Password changed successfully!";
                header("Refresh:2"); // Refresh after 2 seconds
            } else {
                $error_message = "Error changing password: " . $conn->error;
            }
        } else {
            $error_message = "New password and confirmation do not match.";
        }
    } else {
        $error_message = "Current password is incorrect.";
    }
}

// Fetch role-specific data
if ($user['role'] === 'investor') {
    $query_startups = "
        SELECT s.* 
        FROM Startups s
        JOIN Matches m ON s.startup_id = m.startup_id
        WHERE m.investor_id = ? AND s.approval_status = 'approved'";
    $stmt = $conn->prepare($query_startups);
    $stmt->bind_param("i", $viewing_user_id);
    $stmt->execute();
    $result_startups = $stmt->get_result();
    $startups = $result_startups->fetch_all(MYSQLI_ASSOC);
}

if ($user['role'] === 'job_seeker') {
    $query_applications = "
        SELECT j.*, s.name AS startup_name, a.status, a.created_at
        FROM Jobs j
        JOIN Applications a ON j.job_id = a.job_id
        JOIN Startups s ON j.startup_id = s.startup_id
        WHERE a.job_seeker_id = ?
        ORDER BY a.created_at DESC";
    $stmt = $conn->prepare($query_applications);
    $stmt->bind_param("i", $viewing_user_id);
    $stmt->execute();
    $result_applications = $stmt->get_result();
    $applications = $result_applications->fetch_all(MYSQLI_ASSOC);
}

if ($user['role'] === 'entrepreneur') {
    $query_listed_startups = "
        SELECT s.*, 
               COUNT(DISTINCT m.investor_id) as match_count,
               COUNT(DISTINCT j.job_id) as job_count,
               COUNT(DISTINCT a.application_id) as application_count
        FROM Startups s
        LEFT JOIN Matches m ON s.startup_id = m.startup_id
        LEFT JOIN Jobs j ON s.startup_id = j.startup_id
        LEFT JOIN Applications a ON j.job_id = a.job_id
        WHERE s.entrepreneur_id = ?
        GROUP BY s.startup_id
        ORDER BY s.created_at DESC";
    $stmt = $conn->prepare($query_listed_startups);
    $stmt->bind_param("i", $viewing_user_id);
    $stmt->execute();
    $result_listed_startups = $stmt->get_result();
    $listed_startups = $result_listed_startups->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_own_profile ? "Your Profile" : htmlspecialchars($user['name']) . "'s Profile"; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #1e1e1e;
            color: #fff;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .profile-header {
            background: linear-gradient(45deg, rgba(243, 192, 0, 0.1), rgba(0, 0, 0, 0.2));
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(243, 192, 0, 0.2);
        }

        h1 {
            font-size: 2.5em;
            margin: 0 0 10px 0;
            color: #f3c000;
        }

        .role-badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: rgba(243, 192, 0, 0.2);
            color: #f3c000;
            border-radius: 20px;
            font-size: 0.9em;
            margin-top: 10px;
        }

        .verification-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-top: 10px;
            margin-left: 10px;
        }

        .verify-link {
            color: inherit;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9em;
            transition: opacity 0.3s ease;
        }

        .verify-link:hover {
            opacity: 0.8;
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

        .profile-actions {
            margin-top: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .action-button {
            background: linear-gradient(45deg, #f3c000, #ffab00);
            color: #000;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 192, 0, 0.3);
        }

        .action-button i {
            font-size: 1.1em;
        }

        .profile-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .profile-section h2 {
            color: #f3c000;
            margin-top: 0;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #f3c000;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(243, 192, 0, 0.3);
            border-radius: 8px;
            color: #fff;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #f3c000;
            box-shadow: 0 0 0 2px rgba(243, 192, 0, 0.2);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease;
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

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .grid-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 20px;
            transition: transform 0.3s ease;
            border: 1px solid rgba(243, 192, 0, 0.2);
        }

        .grid-item:hover {
            transform: translateY(-5px);
        }

        .grid-item h3 {
            color: #f3c000;
            margin-top: 0;
        }

        .grid-item p {
            margin: 10px 0;
            color: rgba(255, 255, 255, 0.8);
        }

        .stats {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .stat-item {
            background: rgba(243, 192, 0, 0.1);
            padding: 8px 15px;
            border-radius: 15px;
            font-size: 0.9em;
        }

        .view-more {
            color: #f3c000;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 10px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .view-more:hover {
            color: #ffab00;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px auto;
            }

            .profile-header {
                padding: 20px;
            }

            h1 {
                font-size: 2em;
            }

            .grid-container {
                grid-template-columns: 1fr;
            }
        }

        .delete-button {
            background: linear-gradient(45deg, #dc3545, #c82333) !important;
            margin-left: 10px;
        }

        .cancel-button {
            background: linear-gradient(45deg, #6c757d, #5a6268) !important;
            margin-left: 10px;
        }

        .resume-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        #updateResumeForm {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border: 1px solid rgba(243, 192, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="profile-header">
            <h1><?php echo $is_own_profile ? "Your Profile" : htmlspecialchars($user['name']) . "'s Profile"; ?></h1>
            <div class="role-badge">
                <i class="fas <?php
                    switch($user['role']) {
                        case 'entrepreneur':
                            echo 'fa-lightbulb';
                            break;
                        case 'investor':
                            echo 'fa-chart-line';
                            break;
                        case 'job_seeker':
                            echo 'fa-briefcase';
                            break;
                        default:
                            echo 'fa-user';
                    }
                ?>"></i>
                <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
            </div>
            
            <div class="verification-badge status-<?php echo strtolower($user['verification_status']); ?>">
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
                <?php if ($is_own_profile): ?>
                    <a href="verify_account.php" class="verify-link">
                        <i class="fas fa-arrow-right"></i> Verify Account
                    </a>
                <?php endif; ?>
            </div>
            
            <?php if ($is_own_profile): ?>
            <div class="profile-actions">
                <button class="action-button" onclick="toggleSection('editProfile')">
                    <i class="fas fa-user-edit"></i> Edit Profile
                </button>
                <button class="action-button" onclick="toggleSection('changePassword')">
                    <i class="fas fa-key"></i> Change Password
                </button>
                <?php if ($user['role'] === 'entrepreneur'): ?>
                <a href="startup_ai_advisor.php" class="action-button">
                    <i class="fas fa-robot"></i> AI Startup Advisor
                </a>
                <?php endif; ?>
                <?php if ($user['role'] === 'job_seeker'): ?>
                <a href="resume_builder.php" class="action-button">
                    <i class="fas fa-file-alt"></i> Resume Builder
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

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

        <?php if ($is_own_profile): ?>
            <div id="editProfile" class="profile-section" style="display: none;">
                <h2><i class="fas fa-user-edit"></i> Edit Profile</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="action-button">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>
            </div>

            <div id="changePassword" class="profile-section" style="display: none;">
                <h2><i class="fas fa-key"></i> Change Password</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required 
                               pattern=".{8,}" title="Password must be at least 8 characters long">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" name="change_password" class="action-button">
                        <i class="fas fa-check"></i> Update Password
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($user['role'] === 'investor'): ?>
            <div class="profile-section">
                <h2><i class="fas fa-handshake"></i> Matched Startups</h2>
                <div class="grid-container">
                    <?php if (!empty($startups)): ?>
                        <?php foreach ($startups as $startup): ?>
                            <div class="grid-item">
                                <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                                <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                                <p><?php echo htmlspecialchars(substr($startup['description'], 0, 100)) . '...'; ?></p>
                                <a href="startup_detail.php?startup_id=<?php echo $startup['startup_id']; ?>" class="view-more">
                                    View Details <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No matched startups yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($user['role'] === 'job_seeker'): ?>
            <div class="profile-section">
                <h2><i class="fas fa-file-alt"></i> Resume Management</h2>
                
                <?php
                // Fetch current active resume
                $resume_stmt = $conn->prepare("SELECT * FROM Resumes WHERE job_seeker_id = ? AND is_active = TRUE");
                $resume_stmt->bind_param("i", $viewing_user_id);
                $resume_stmt->execute();
                $resume_result = $resume_stmt->get_result();
                $current_resume = $resume_result->fetch_assoc();
                ?>

                <?php if ($current_resume): ?>
                    <div class="current-resume">
                        <h3>Current Resume</h3>
                        <p><strong>File Name:</strong> <?php echo htmlspecialchars($current_resume['file_name']); ?></p>
                        <p><strong>Uploaded:</strong> <?php echo date('F j, Y g:i A', strtotime($current_resume['uploaded_at'])); ?></p>
                        <div class="resume-actions">
                            <a href="download_resume.php?job_seeker_id=<?php echo $viewing_user_id; ?>" class="action-button">
                                <i class="fas fa-download"></i> Download Resume
                            </a>
                            <?php if ($is_own_profile): ?>
                                <form method="POST" action="delete_resume.php" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this resume?');">
                                    <input type="hidden" name="resume_id" value="<?php echo $current_resume['resume_id']; ?>">
                                    <button type="submit" class="action-button delete-button">
                                        <i class="fas fa-trash"></i> Delete Resume
                                    </button>
                                </form>
                                
                                <button type="button" class="action-button" onclick="toggleUpdateForm()">
                                    <i class="fas fa-edit"></i> Update Resume
                                </button>

                                <div id="updateResumeForm" style="display: none; margin-top: 20px;">
                                    <form method="POST" action="upload_resume.php" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="resume">Select New Resume</label>
                                            <input type="file" name="resume" accept=".pdf,.doc,.docx" required>
                                            <small>Supported formats: PDF, DOC, DOCX (Max size: 5MB)</small>
                                        </div>
                                        <button type="submit" class="action-button">
                                            <i class="fas fa-upload"></i> Update Resume
                                        </button>
                                        <button type="button" class="action-button cancel-button" onclick="toggleUpdateForm()">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php elseif ($is_own_profile): ?>
                    <div class="upload-resume">
                        <p>You haven't uploaded a resume yet.</p>
                        <form method="POST" action="upload_resume.php" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="resume">Upload Resume</label>
                                <input type="file" name="resume" accept=".pdf,.doc,.docx" required>
                                <small>Supported formats: PDF, DOC, DOCX (Max size: 5MB)</small>
                            </div>
                            <button type="submit" class="action-button">
                                <i class="fas fa-upload"></i> Upload Resume
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <p>No resume available.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($user['role'] === 'job_seeker' && $is_own_profile): ?>
            <div class="profile-section">
                <h2><i class="fas fa-briefcase"></i> Job Applications</h2>
                <div class="grid-container">
                    <?php if (!empty($applications)): ?>
                        <?php foreach ($applications as $application): ?>
                            <div class="grid-item">
                                <h3><?php echo htmlspecialchars($application['role']); ?></h3>
                                <p><strong>Company:</strong> <?php echo htmlspecialchars($application['startup_name']); ?></p>
                                <p><strong>Status:</strong> 
                                    <span class="role-badge">
                                        <?php echo ucfirst(htmlspecialchars($application['status'])); ?>
                                    </span>
                                </p>
                                <p><strong>Applied:</strong> 
                                    <?php echo date('M d, Y', strtotime($application['created_at'])); ?>
                                </p>
                                <a href="job-details.php?job_id=<?php echo $application['job_id']; ?>" class="view-more">
                                    View Job Details <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No job applications yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($user['role'] === 'entrepreneur'): ?>
            <div class="profile-section">
                <h2><i class="fas fa-building"></i> Listed Startups</h2>
                <div class="grid-container">
                    <?php if (!empty($listed_startups)): ?>
                        <?php foreach ($listed_startups as $startup): ?>
                            <div class="grid-item">
                                <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                                <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                                <p><?php echo htmlspecialchars(substr($startup['description'], 0, 100)) . '...'; ?></p>
                                <div class="stats">
                                    <span class="stat-item">
                                        <i class="fas fa-handshake"></i> <?php echo $startup['match_count']; ?> matches
                                    </span>
                                    <span class="stat-item">
                                        <i class="fas fa-briefcase"></i> <?php echo $startup['job_count']; ?> jobs
                                    </span>
                                    <span class="stat-item">
                                        <i class="fas fa-users"></i> <?php echo $startup['application_count']; ?> applications
                                    </span>
                                </div>
                                <a href="startup_detail.php?startup_id=<?php echo $startup['startup_id']; ?>" class="view-more">
                                    View Details <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No startups listed yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const isHidden = section.style.display === "none" || section.style.display === "";
            
            // Only hide editable sections (Edit Profile and Change Password)
            const editableSections = ['editProfile', 'changePassword'];
            editableSections.forEach(id => {
                const editableSection = document.getElementById(id);
                if (editableSection) {
                    editableSection.style.display = 'none';
                }
            });
            
            // Then show the selected section if it was hidden
            if (isHidden) {
                section.style.display = "block";
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Password confirmation validation
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');

        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== newPasswordInput.value) {
                    this.setCustomValidity('Passwords do not match');
                } else {
                    this.setCustomValidity('');
                }
            });

            newPasswordInput.addEventListener('input', function() {
                if (confirmPasswordInput.value !== '') {
                    if (this.value !== confirmPasswordInput.value) {
                        confirmPasswordInput.setCustomValidity('Passwords do not match');
                    } else {
                        confirmPasswordInput.setCustomValidity('');
                    }
                }
            });
        }

        // Show sections if there were validation errors
        <?php if (isset($_POST['update_profile'])): ?>
            toggleSection('editProfile');
        <?php endif; ?>
        
        <?php if (isset($_POST['change_password'])): ?>
            toggleSection('changePassword');
        <?php endif; ?>

        function toggleUpdateForm() {
            const form = document.getElementById('updateResumeForm');
            if (form.style.display === 'none') {
                form.style.display = 'block';
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</body>
</html>
