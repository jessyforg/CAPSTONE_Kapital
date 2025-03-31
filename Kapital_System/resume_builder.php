<?php
session_start();
include('db_connection.php');
include('navbar.php');

// Check if user is logged in and is a job seeker
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'job_seeker') {
    header("Location: sign_in.php");
    exit();
}

// Initialize variables
$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['generate_resume'])) {
        // Store the form data in session for processing
        $_SESSION['resume_data'] = [
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'work_experience' => $_POST['work_experience'],
            'education' => $_POST['education'],
            'skills' => $_POST['skills'],
            'achievements' => $_POST['achievements'],
            'desired_role' => $_POST['desired_role']
        ];
        
        // Redirect to resume generation page
        header("Location: generate_resume.php");
        exit();
    }
}

// Fetch user's existing data
$query = "SELECT u.name, u.email, js.skills, js.desired_role, js.experience_level 
          FROM Users u 
          JOIN Job_Seekers js ON u.user_id = js.job_seeker_id 
          WHERE u.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Resume Builder</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #1a1a1a;
            color: #fff;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid rgba(243, 192, 0, 0.2);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        h1 {
            color: #f3c000;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #f3c000;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(243, 192, 0, 0.3);
            border-radius: 8px;
            font-size: 16px;
            margin-top: 5px;
            color: #fff;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        textarea:focus {
            outline: none;
            border-color: #f3c000;
            box-shadow: 0 0 0 2px rgba(243, 192, 0, 0.2);
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        .btn-submit {
            background: linear-gradient(45deg, #f3c000, #ffab00);
            color: #000;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 30px auto 0;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 192, 0, 0.3);
        }

        .features-list {
            margin: 20px 0;
            padding: 20px;
            background: rgba(243, 192, 0, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(243, 192, 0, 0.2);
        }

        .features-list h3 {
            color: #f3c000;
            margin-bottom: 15px;
            font-size: 1.2em;
        }

        .features-list ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .features-list li {
            margin-bottom: 12px;
            padding-left: 25px;
            position: relative;
            color: rgba(255, 255, 255, 0.9);
        }

        .features-list li:before {
            content: "•";
            color: #f3c000;
            font-weight: bold;
            position: absolute;
            left: 0;
            font-size: 1.2em;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background-color: rgba(46, 125, 50, 0.2);
            color: #4caf50;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }

        .alert-error {
            background-color: rgba(211, 47, 47, 0.2);
            color: #ff5252;
            border: 1px solid rgba(255, 82, 82, 0.3);
        }

        ::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-file-alt"></i> AI-Powered Resume Builder</h1>
        
        <div class="features-list">
            <h3><i class="fas fa-star"></i> Features:</h3>
            <ul>
                <li>AI-powered content suggestions for each section</li>
                <li>Industry-specific keyword optimization</li>
                <li>Professional formatting and layout</li>
                <li>ATS (Applicant Tracking System) friendly templates</li>
                <li>Customized suggestions based on your target role</li>
            </ul>
        </div>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="full_name"><i class="fas fa-user"></i> Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone"><i class="fas fa-phone"></i> Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
            </div>

            <div class="form-group">
                <label for="work_experience"><i class="fas fa-briefcase"></i> Work Experience</label>
                <textarea id="work_experience" name="work_experience" placeholder="Enter your work experience (Company, Role, Duration, Key Responsibilities)" required></textarea>
            </div>

            <div class="form-group">
                <label for="education"><i class="fas fa-graduation-cap"></i> Education</label>
                <textarea id="education" name="education" placeholder="Enter your educational background (Institution, Degree, Year)" required></textarea>
            </div>

            <div class="form-group">
                <label for="skills"><i class="fas fa-tools"></i> Skills</label>
                <textarea id="skills" name="skills" placeholder="Enter your skills (separated by commas)" required><?php echo htmlspecialchars($user_data['skills'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="achievements"><i class="fas fa-trophy"></i> Achievements & Certifications</label>
                <textarea id="achievements" name="achievements" placeholder="Enter your achievements and certifications"></textarea>
            </div>

            <div class="form-group">
                <label for="desired_role"><i class="fas fa-bullseye"></i> Target Job Role</label>
                <input type="text" id="desired_role" name="desired_role" value="<?php echo htmlspecialchars($user_data['desired_role'] ?? ''); ?>" placeholder="Enter your target job role" required>
            </div>

            <button type="submit" name="generate_resume" class="btn-submit">
                <i class="fas fa-magic"></i> Generate AI-Enhanced Resume
            </button>
        </form>
    </div>
</body>
</html> 