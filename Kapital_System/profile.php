<?php
session_start();
include('navbar.php');
include('db_connection.php');

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

$user_id = $_SESSION['user_id'];

// Retrieve user details from the database
$query_user = "SELECT * FROM Users WHERE user_id = '$user_id'";
$result_user = mysqli_query($conn, $query_user);

if ($result_user && mysqli_num_rows($result_user) > 0) {
    $user = mysqli_fetch_assoc($result_user);
} else {
    die("User not found in the database.");
}

// Handle profile updates
if (isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query_update = "UPDATE Users SET name = '$name', email = '$email' WHERE user_id = '$user_id'";
    if (mysqli_query($conn, $query_update)) {
        $success_message = "Profile updated successfully!";
        header("Refresh:0");
    } else {
        $error_message = "Error updating profile: " . mysqli_error($conn);
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query_password = "UPDATE Users SET password = '$hashed_password' WHERE user_id = '$user_id'";
            if (mysqli_query($conn, $query_password)) {
                $success_message = "Password changed successfully!";
            } else {
                $error_message = "Error changing password: " . mysqli_error($conn);
            }
        } else {
            $error_message = "New password and confirmation do not match.";
        }
    } else {
        $error_message = "Current password is incorrect.";
    }
}

// Fetch investor's matched startups (if investor)
if ($user['role'] === 'investor') {
    $query_startups = "
        SELECT s.* 
        FROM Startups s
        JOIN Matches m ON s.startup_id = m.startup_id
        WHERE m.investor_id = '$user_id'";
    $result_startups = mysqli_query($conn, $query_startups);
    $startups = mysqli_fetch_all($result_startups, MYSQLI_ASSOC);
}

// Fetch job applications (if job seeker)
if ($user['role'] === 'job_seeker') {
    $query_applications = "
        SELECT j.*, s.name AS startup_name, a.status 
        FROM Jobs j
        JOIN Applications a ON j.job_id = a.job_id
        JOIN Startups s ON j.startup_id = s.startup_id
        WHERE a.job_seeker_id = '$user_id'";
    $result_applications = mysqli_query($conn, $query_applications);
    $applications = mysqli_fetch_all($result_applications, MYSQLI_ASSOC);
}

// Fetch entrepreneur's listed startups (if entrepreneur)
if ($user['role'] === 'entrepreneur') {
    // Entrepreneurs table has entrepreneur_id, not linked directly to Users
    $query_listed_startups = "
        SELECT s.* 
        FROM Startups s
        JOIN Entrepreneurs e ON s.entrepreneur_id = e.entrepreneur_id
        WHERE e.entrepreneur_id = '$user_id'";
    $result_listed_startups = mysqli_query($conn, $query_listed_startups);
    $listed_startups = mysqli_fetch_all($result_listed_startups, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile and Manage Startups</title>
    <script>
        function toggleSection(sectionId) {
            var section = document.getElementById(sectionId);
            if (section.style.display === "none" || section.style.display === "") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Container and layout */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1, h2 {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: 600;
            color: #fff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .success, .error {
            font-size: 16px;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }

        .error {
            background-color: #f2dede;
            color: #a94442;
        }

        /* Listed Startups Section (for Entrepreneurs) */
        .listed-startup-list {
            margin-top: 30px;
        }

        .listed-startup-item {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .listed-startup-item:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .listed-startup-item h3 {
            font-size: 22px;
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
        }

        .listed-startup-item p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }

        .listed-startup-item .startup-actions {
            margin-top: 15px;
        }

        .listed-startup-item .startup-actions a {
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
            font-weight: 500;
        }

        .listed-startup-item .startup-actions a:hover {
            text-decoration: underline;
        }

        /* Additional styles */
        .startup-list, .job-application-list {
            margin-top: 30px;
        }

        .startup-item, .job-application-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .startup-actions, .job-actions {
            margin-top: 10px;
        }

        .startup-actions a, .job-actions a {
            text-decoration: none;
            color: #007bff;
        }

        .startup-actions a:hover, .job-actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>User Profile</h1>
        <?php if (isset($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php elseif (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <button onclick="toggleSection('editProfile')">Edit Profile</button>
        <div id="editProfile" style="display: none;">
            <form method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <button type="submit" name="update_profile">Save Changes</button>
            </form>
        </div>

        <button onclick="toggleSection('changePassword')">Change Password</button>
        <div id="changePassword" style="display: none;">
            <form method="POST">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" name="change_password">Change Password</button>
            </form>
        </div>


        <!-- Display Matched Startups (for Investors) -->
        <?php if ($user['role'] === 'investor'): ?>
            <div class="startup-list">
                <h2>Your Matched Startups</h2>
                <?php if (!empty($startups)): ?>
                    <?php foreach ($startups as $startup): ?>
                        <div class="startup-item">
                            <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                            <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>
                            <div class="startup-actions">
                                <a href="startup_detail.php?startup_id=<?php echo $startup['startup_id']; ?>">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No matched startups.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Display Job Applications (for Job Seekers) -->
        <?php if ($user['role'] === 'job_seeker'): ?>
            <h2>Upload Resume</h2>
            <form method="POST" enctype="multipart/form-data" action="upload_resume.php">
                <input type="file" name="resume" accept=".pdf,.doc,.docx" required>
                <button type="submit" name="upload_resume">Upload Resume</button>
            </form>
            <div class="job-application-list">
                <h2>Your Job Applications</h2>
                <?php if (!empty($applications)): ?>
                    <?php foreach ($applications as $application): ?>
                        <div class="job-application-item">
                            <h3><?php echo htmlspecialchars($application['role']); ?></h3>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($application['status']); ?></p>
                            <p><strong>Company:</strong> <?php echo htmlspecialchars($application['startup_name']); ?></p>
                            <div class="job-actions">
                                <a href="job-details.php?job_id=<?php echo $application['job_id']; ?>">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No job applications.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Display Listed Startups (for Entrepreneurs) -->
        <?php if ($user['role'] === 'entrepreneur'): ?>
            <div class="listed-startup-list">
                <h2>Your Listed Startups</h2>
                <?php if (!empty($listed_startups)): ?>
                    <?php foreach ($listed_startups as $startup): ?>
                        <div class="listed-startup-item">
                            <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                            <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>
                            <div class="startup-actions">
                                <a href="startup_detail.php?startup_id=<?php echo $startup['startup_id']; ?>">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No listed startups.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</body>

</html>
