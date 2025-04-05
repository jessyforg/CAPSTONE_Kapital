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
$stmt = $conn->prepare("SELECT u.*, usl.* FROM Users u LEFT JOIN User_Social_Links usl ON u.user_id = usl.user_id WHERE u.user_id = ?");
$stmt->bind_param("i", $viewing_user_id);
$stmt->execute();
$result_user = $stmt->get_result();

if ($result_user && $result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    die("User not found in the database.");
}

// Define Philippine regions and cities
$locations = [
    'National Capital Region (NCR)' => [
        'Manila',
        'Quezon City',
        'Caloocan',
        'Las Piñas',
        'Makati',
        'Malabon',
        'Mandaluyong',
        'Marikina',
        'Muntinlupa',
        'Navotas',
        'Parañaque',
        'Pasay',
        'Pasig',
        'Pateros',
        'San Juan',
        'Taguig',
        'Valenzuela',
        'Pateros'
    ],
    'Cordillera Administrative Region (CAR)' => [
        'Baguio City',
        'Tabuk City',
        'La Trinidad',
        'Bangued',
        'Lagawe',
        'Bontoc'
    ],
    'Ilocos Region (Region I)' => [
        'San Fernando City',
        'Laoag City',
        'Vigan City',
        'Dagupan City',
        'San Carlos City',
        'Urdaneta City'
    ],
    'Cagayan Valley (Region II)' => [
        'Tuguegarao City',
        'Cauayan City',
        'Santiago City',
        'Ilagan City'
    ],
    'Central Luzon (Region III)' => [
        'San Fernando City',
        'Angeles City',
        'Olongapo City',
        'Malolos City',
        'Cabanatuan City',
        'San Jose City',
        'Science City of Muñoz',
        'Palayan City'
    ],
    'CALABARZON (Region IV-A)' => [
        'Calamba City',
        'San Pablo City',
        'Antipolo City',
        'Batangas City',
        'Cavite City',
        'Lipa City',
        'San Pedro',
        'Santa Rosa',
        'Tagaytay City',
        'Trece Martires City'
    ],
    'MIMAROPA (Region IV-B)' => [
        'Calapan City',
        'Puerto Princesa City',
        'San Jose',
        'Romblon'
    ],
    'Bicol Region (Region V)' => [
        'Naga City',
        'Legazpi City',
        'Iriga City',
        'Ligao City',
        'Tabaco City',
        'Sorsogon City'
    ],
    'Western Visayas (Region VI)' => [
        'Iloilo City',
        'Bacolod City',
        'Roxas City',
        'Passi City',
        'Silay City',
        'Talisay City',
        'Escalante City',
        'Sagay City',
        'Cadiz City',
        'Bago City',
        'La Carlota City',
        'Kabankalan City',
        'San Carlos City',
        'Sipalay City',
        'Himamaylan City'
    ],
    'Central Visayas (Region VII)' => [
        'Cebu City',
        'Mandaue City',
        'Lapu-Lapu City',
        'Talisay City',
        'Toledo City',
        'Dumaguete City',
        'Bais City',
        'Bayawan City',
        'Canlaon City',
        'Guihulngan City',
        'Tanjay City'
    ],
    'Eastern Visayas (Region VIII)' => [
        'Tacloban City',
        'Ormoc City',
        'Calbayog City',
        'Catbalogan City',
        'Maasin City',
        'Baybay City',
        'Borongan City'
    ],
    'Zamboanga Peninsula (Region IX)' => [
        'Zamboanga City',
        'Dipolog City',
        'Dapitan City',
        'Isabela City',
        'Pagadian City'
    ],
    'Northern Mindanao (Region X)' => [
        'Cagayan de Oro City',
        'Iligan City',
        'Oroquieta City',
        'Ozamiz City',
        'Tangub City',
        'Gingoog City',
        'El Salvador',
        'Malaybalay City',
        'Valencia City'
    ],
    'Davao Region (Region XI)' => [
        'Davao City',
        'Digos City',
        'Mati City',
        'Panabo City',
        'Samal City',
        'Tagum City'
    ],
    'SOCCSKSARGEN (Region XII)' => [
        'Koronadal City',
        'Cotabato City',
        'General Santos City',
        'Kidapawan City',
        'Tacurong City'
    ],
    'Caraga (Region XIII)' => [
        'Butuan City',
        'Surigao City',
        'Bislig City',
        'Tandag City',
        'Bayugan City',
        'Cabadbaran City'
    ],
    'Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)' => [
        'Cotabato City',
        'Marawi City',
        'Lamitan City'
    ]
];

// Define industries array
$industries = [
    'Technology' => [
        'Software Development',
        'Artificial Intelligence',
        'Mobile App Development',
        'Cloud Computing',
        'Cybersecurity',
        'E-commerce',
        'Fintech',
        'Internet of Things (IoT)',
        'Blockchain',
        'Big Data'
    ],
    'Healthcare' => [
        'Medical Devices',
        'Healthcare IT',
        'Biotechnology',
        'Pharmaceuticals',
        'Telemedicine',
        'Mental Health',
        'Healthcare Services',
        'Medical Research',
        'Digital Health',
        'Wellness & Fitness'
    ],
    'Education' => [
        'EdTech',
        'Online Learning',
        'Educational Services',
        'Professional Training',
        'Language Learning',
        'Educational Content',
        'Learning Management Systems',
        'Educational Apps',
        'STEM Education',
        'Early Childhood Education'
    ],
    'Financial Services' => [
        'Banking',
        'Insurance',
        'Investment Management',
        'Payment Processing',
        'Cryptocurrency',
        'Personal Finance',
        'Lending',
        'Financial Advisory',
        'Asset Management',
        'Risk Management'
    ],
    'Retail & E-commerce' => [
        'Online Retail',
        'Mobile Commerce',
        'Retail Technology',
        'Fashion & Apparel',
        'Consumer Goods',
        'Marketplace Platforms',
        'Subscription Services',
        'Retail Analytics',
        'Supply Chain Management',
        'Customer Experience'
    ],
    'Manufacturing' => [
        'Advanced Manufacturing',
        'Industrial Automation',
        '3D Printing',
        'Smart Manufacturing',
        'Electronics Manufacturing',
        'Food Processing',
        'Textile Manufacturing',
        'Automotive Manufacturing',
        'Chemical Manufacturing',
        'Green Manufacturing'
    ],
    'Energy & Sustainability' => [
        'Renewable Energy',
        'Clean Technology',
        'Energy Efficiency',
        'Solar Power',
        'Wind Energy',
        'Energy Storage',
        'Green Building',
        'Waste Management',
        'Environmental Services',
        'Sustainable Transportation'
    ],
    'Agriculture' => [
        'AgTech',
        'Smart Farming',
        'Organic Farming',
        'Precision Agriculture',
        'Aquaculture',
        'Vertical Farming',
        'Agricultural Biotechnology',
        'Farm Management',
        'Agricultural Supply Chain',
        'Food Technology'
    ],
    'Transportation & Logistics' => [
        'Logistics Technology',
        'Fleet Management',
        'Last-Mile Delivery',
        'Transportation Services',
        'Autonomous Vehicles',
        'Shipping & Freight',
        'Urban Mobility',
        'Warehouse Management',
        'Supply Chain Solutions',
        'Delivery Optimization'
    ],
    'Real Estate & Construction' => [
        'PropTech',
        'Construction Technology',
        'Real Estate Services',
        'Property Management',
        'Smart Buildings',
        'Construction Management',
        'Architecture & Design',
        'Building Materials',
        'Real Estate Investment',
        'Facility Management'
    ]
];

// Handle profile updates - only if it's the user's own profile
if ($is_own_profile && isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $public_email = !empty($_POST['public_email']) ? mysqli_real_escape_string($conn, $_POST['public_email']) : $email;
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $industry = mysqli_real_escape_string($conn, $_POST['industry']);
    $introduction = mysqli_real_escape_string($conn, $_POST['introduction']);
    $accomplishments = mysqli_real_escape_string($conn, $_POST['accomplishments']);
    $education = mysqli_real_escape_string($conn, $_POST['education']);
    $employment = mysqli_real_escape_string($conn, $_POST['employment']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
    
    // Handle profile picture upload
    $profile_picture_url = $user['profile_picture_url'];
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        $file_type = $_FILES['profile_picture']['type'];
        $file_size = $_FILES['profile_picture']['size'];
        
        if (!in_array($file_type, $allowed_types)) {
            $error_message = "Invalid file type. Please upload a JPEG, PNG, or GIF image.";
        } elseif ($file_size > $max_size) {
            $error_message = "File is too large. Maximum size is 5MB.";
        } else {
            $upload_dir = 'uploads/profile_pictures/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
            $new_filename = uniqid('profile_') . '.' . $file_extension;
            $target_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_path)) {
                // Delete old profile picture if exists
                if ($profile_picture_url && file_exists($profile_picture_url)) {
                    unlink($profile_picture_url);
                }
                $profile_picture_url = $target_path;
            } else {
                $error_message = "Failed to upload profile picture. Please try again.";
            }
        }
    }
    
    // Check if email already exists for another user
    $check_email = $conn->prepare("SELECT user_id FROM Users WHERE email = ? AND user_id != ?");
    $check_email->bind_param("si", $email, $viewing_user_id);
    $check_email->execute();
    $email_result = $check_email->get_result();
    
    if ($email_result->num_rows > 0) {
        $error_message = "This email is already in use by another account.";
    } else {
        // Update user profile
        $query_update = "UPDATE Users SET name = ?, email = ?, public_email = ?, contact_number = ?, location = ?, 
                        industry = ?, introduction = ?, accomplishments = ?, education = ?, employment = ?, gender = ?, 
                        birthdate = ?, profile_picture_url = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($query_update);
        $update_stmt->bind_param("sssssssssssssi", $name, $email, $public_email, $contact_number, $location, 
                               $industry, $introduction, $accomplishments, $education, $employment, $gender, 
                               $birthdate, $profile_picture_url, $viewing_user_id);
        
        if ($update_stmt->execute()) {
            // Update social media links
            $facebook_url = mysqli_real_escape_string($conn, $_POST['facebook_url']);
            $twitter_url = mysqli_real_escape_string($conn, $_POST['twitter_url']);
            $instagram_url = mysqli_real_escape_string($conn, $_POST['instagram_url']);
            $linkedin_url = mysqli_real_escape_string($conn, $_POST['linkedin_url']);
            
            $query_social = "UPDATE User_Social_Links SET 
                           facebook_url = ?, twitter_url = ?, 
                           instagram_url = ?, linkedin_url = ? 
                           WHERE user_id = ?";
            $social_stmt = $conn->prepare($query_social);
            $social_stmt->bind_param("ssssi", $facebook_url, $twitter_url, 
                                   $instagram_url, $linkedin_url, $viewing_user_id);
            
            if ($social_stmt->execute()) {
            $success_message = "Profile updated successfully!";
            // Update session variables
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            header("Refresh:2"); // Refresh after 2 seconds
            } else {
                $error_message = "Error updating social media links: " . $conn->error;
            }
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

// Handle profile picture upload
if ($is_own_profile && isset($_POST['update_profile_picture']) && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file['type'], $allowed_types)) {
            $error_message = "Invalid file type. Please upload a JPEG, PNG, or GIF image.";
        } elseif ($file['size'] > $max_size) {
            $error_message = "File is too large. Maximum size is 5MB.";
        } else {
            $upload_dir = 'uploads/profile_pictures/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $new_filename = 'profile_' . $viewing_user_id . '_' . uniqid() . '.' . $file_extension;
            $target_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                // Delete old profile picture if exists
                if ($user['profile_picture_url'] && file_exists($user['profile_picture_url'])) {
                    unlink($user['profile_picture_url']);
                }
                
                // Update database with new profile picture URL
                $update_query = "UPDATE Users SET profile_picture_url = ? WHERE user_id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("si", $target_path, $viewing_user_id);
                
                if ($stmt->execute()) {
                    $success_message = "Profile picture updated successfully!";
                    $user['profile_picture_url'] = $target_path; // Update current user data
                    header("Refresh:2"); // Refresh after 2 seconds
                } else {
                    $error_message = "Failed to update profile picture in database.";
                }
            } else {
                $error_message = "Failed to upload profile picture. Please try again.";
            }
        }
    } else {
        $error_message = "Error uploading file. Please try again.";
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
            display: flex;
            align-items: flex-start;
            gap: 30px;
            background: linear-gradient(45deg, rgba(243, 192, 0, 0.1), rgba(0, 0, 0, 0.2));
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(243, 192, 0, 0.2);
        }

        .profile-header-content {
            flex: 1;
            padding-top: 10px;
        }

        .profile-picture-container {
            flex-shrink: 0;
            text-align: center;
            width: 200px;
        }

        .profile-picture {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f3c000;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-picture-placeholder {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(243, 192, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border: 3px solid #f3c000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-picture-placeholder i {
            font-size: 5em;
            color: #f3c000;
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

        .profile-actions .action-button {
            min-width: 160px;
            justify-content: center;
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
            text-decoration: none;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 192, 0, 0.3);
        }

        .profile-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 40px;
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

        .profile-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }

        .info-section {
            background: rgba(255, 255, 255, 0.05);
            padding: 25px;
            border-radius: 10px;
            border: 1px solid rgba(243, 192, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .info-section:hover {
            transform: translateY(-5px);
        }

        .info-section h3 {
            color: #f3c000;
            margin-top: 0;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-section p {
            margin: 10px 0;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .grid-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 25px;
            transition: transform 0.3s ease;
            border: 1px solid rgba(243, 192, 0, 0.2);
        }

        .grid-item:hover {
            transform: translateY(-5px);
        }

        .grid-item h3 {
            color: #f3c000;
            margin-top: 0;
            margin-bottom: 15px;
        }

        .grid-item p {
            margin: 10px 0;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        .stats {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .stat-item {
            background: rgba(243, 192, 0, 0.1);
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .view-more {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            color: #f3c000;
            text-decoration: none;
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
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 20px;
            }

            .profile-picture-container {
                margin-bottom: 20px;
            }

            .profile-actions {
                justify-content: center;
            }

            .profile-actions .action-button,
            #profilePictureForm .action-button {
                width: 100%;
                min-width: unset;
            }

            #profilePictureForm {
                width: 100%;
            }

            h1 {
                font-size: 2em;
            }

            .grid-container {
                grid-template-columns: 1fr;
            }

            .profile-info {
                gap: 20px;
            }
            
            .grid-container {
                gap: 20px;
            }
            
            .info-section, .grid-item {
                padding: 20px;
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

        .social-links {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }

        .social-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(243, 192, 0, 0.1);
            border-radius: 20px;
            color: #f3c000;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: rgba(243, 192, 0, 0.2);
            transform: translateY(-2px);
        }

        textarea {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(243, 192, 0, 0.3);
            border-radius: 8px;
            color: #fff;
            font-size: 1em;
            transition: all 0.3s ease;
            resize: vertical;
        }

        textarea:focus {
            outline: none;
            border-color: #f3c000;
            box-shadow: 0 0 0 2px rgba(243, 192, 0, 0.2);
        }

        select {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(243, 192, 0, 0.3);
            border-radius: 8px;
            color: #fff;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        select:focus {
            outline: none;
            border-color: #f3c000;
            box-shadow: 0 0 0 2px rgba(243, 192, 0, 0.2);
        }

        .form-control {
            background: #23272A;
            border: 1px solid #40444B;
            color: #FFFFFF;
            padding: 14px;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        .form-control:focus {
            border-color: #7289DA;
            outline: none;
            box-shadow: 0 0 0 2px rgba(114, 137, 218, 0.2);
            background: #2C2F33;
        }

        .form-control::placeholder {
            color: #72767D;
        }

        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%237289DA' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: calc(100% - 15px) center;
            padding-right: 40px;
        }

        select.form-control:hover {
            border-color: #7289DA;
        }

        .form-group label {
            color: #B9BBBE;
            font-size: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .form-group label i {
            color: #7289DA;
            font-size: 1.1rem;
        }

        .startup-header, .application-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .startup-logo {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        .startup-details, .application-details {
            margin-bottom: 20px;
        }

        .startup-details p, .application-details p {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }

        .startup-description, .cover-letter-preview {
            margin-top: 15px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
        }

        .startup-actions, .application-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: 500;
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }

        .status-approved {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .status-rejected {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            border: 1px dashed rgba(255, 255, 255, 0.1);
        }

        .empty-state i {
            font-size: 3em;
            color: #f3c000;
            margin-bottom: 20px;
        }

        .empty-state p {
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.7);
        }

        .resume-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .resume-header i {
            font-size: 2em;
            color: #f3c000;
        }

        .resume-details {
            margin-bottom: 20px;
        }

        .resume-details p {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }

        .resume-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        /* Select2 Custom Styles */
        .select2-container--default .select2-selection--single {
            background-color: #2C2F33 !important;
            border: 1px solid #f3c000 !important;
            border-radius: 8px !important;
            height: 42px !important;
            color: #FFFFFF !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #FFFFFF !important;
            line-height: 42px !important;
            padding-left: 15px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }

        .select2-dropdown {
            background-color: #2C2F33 !important;
            border: 1px solid #f3c000 !important;
            border-radius: 8px !important;
        }

        .select2-container--default .select2-results__option {
            background-color: #2C2F33 !important;
            color: #FFFFFF !important;
            padding: 10px 15px !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #f3c000 !important;
            color: #000000 !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #2C2F33 !important;
            color: #FFFFFF !important;
            border: 1px solid #f3c000 !important;
            padding: 8px !important;
        }

        .select2-container--default .select2-results__group {
            background-color: #202225 !important;
            color: #f3c000 !important;
            font-weight: 600 !important;
            padding: 10px 15px !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: rgba(243, 192, 0, 0.2) !important;
            color: #f3c000 !important;
        }

        .select2-container--open .select2-dropdown {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #f3c000 transparent transparent transparent !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #f3c000 transparent !important;
        }

        /* Initialize both location and industry dropdowns with Select2 */
        $(document).ready(function() {
            $('#location, #industry').select2({
                theme: 'default',
                width: '100%',
                placeholder: 'Search or select',
                allowClear: true,
                minimumInputLength: 0
            });
        });
    </style>
</head>

<body>
    <div class="container">
        <div class="profile-header">
            <div class="profile-picture-container">
                <?php if ($user['profile_picture_url']): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_picture_url']); ?>" alt="Profile Picture" class="profile-picture">
                <?php else: ?>
                    <div class="profile-picture-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                <?php endif; ?>
                
                <?php if ($is_own_profile): ?>
                    <form method="POST" enctype="multipart/form-data" id="profilePictureForm">
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" style="display: none;">
                        <button type="button" class="action-button" onclick="document.getElementById('profile_picture').click()">
                            <i class="fas fa-camera"></i> Change Picture
                        </button>
                        <button type="submit" name="update_profile_picture" class="action-button" id="uploadButton" style="display: none;">
                            <i class="fas fa-upload"></i> Upload Picture
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="profile-header-content">
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
                <?php else: ?>
                <div class="profile-actions">
                    <a href="messages.php?recipient_id=<?php echo $viewing_user_id; ?>" class="action-button">
                        <i class="fas fa-comments"></i> Send Message
                    </a>
                </div>
            <?php endif; ?>
            </div>
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
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="public_email">Public Email</label>
                        <input type="email" id="public_email" name="public_email" value="<?php echo htmlspecialchars($user['public_email'] ?? ''); ?>" placeholder="Email address to show publicly">
                        <small>Leave blank to use your primary email</small>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="tel" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>" placeholder="Enter your contact number">
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <select id="location" name="location" class="select2" required>
                            <option value="">Select Location</option>
                            <?php foreach ($locations as $region => $cities): ?>
                                <optgroup label="<?php echo htmlspecialchars($region); ?>">
                                    <?php foreach ($cities as $city): ?>
                                        <option value="<?php echo htmlspecialchars($city); ?>" <?php echo isset($user['location']) && $user['location'] == $city ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($city); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="industry">Industry</label>
                        <select id="industry" name="industry" class="select2" required>
                            <option value="">Select Industry</option>
                            <?php foreach ($industries as $category => $subcategories): ?>
                                <optgroup label="<?php echo htmlspecialchars($category); ?>">
                                    <?php foreach ($subcategories as $industry): ?>
                                        <option value="<?php echo htmlspecialchars($industry); ?>" <?php echo isset($user['industry']) && $user['industry'] == $industry ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($industry); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="introduction">Introduction</label>
                        <textarea id="introduction" name="introduction" rows="4"><?php echo htmlspecialchars($user['introduction'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="accomplishments">Accomplishments</label>
                        <textarea id="accomplishments" name="accomplishments" rows="4"><?php echo htmlspecialchars($user['accomplishments'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="education">Education</label>
                        <textarea id="education" name="education" rows="4"><?php echo htmlspecialchars($user['education'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="employment">Employment</label>
                        <textarea id="employment" name="employment" rows="4"><?php echo htmlspecialchars($user['employment'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" <?php echo ($user['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo ($user['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Female</option>
                            <option value="other" <?php echo ($user['gender'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                            <option value="prefer_not_to_say" <?php echo ($user['gender'] ?? '') === 'prefer_not_to_say' ? 'selected' : ''; ?>>Prefer not to say</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="birthdate">Birthdate</label>
                        <input type="date" id="birthdate" name="birthdate" value="<?php echo $user['birthdate'] ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="facebook_url">Facebook URL</label>
                        <input type="url" id="facebook_url" name="facebook_url" value="<?php echo htmlspecialchars($user['facebook_url'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="twitter_url">Twitter URL</label>
                        <input type="url" id="twitter_url" name="twitter_url" value="<?php echo htmlspecialchars($user['twitter_url'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="instagram_url">Instagram URL</label>
                        <input type="url" id="instagram_url" name="instagram_url" value="<?php echo htmlspecialchars($user['instagram_url'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="linkedin_url">LinkedIn URL</label>
                        <input type="url" id="linkedin_url" name="linkedin_url" value="<?php echo htmlspecialchars($user['linkedin_url'] ?? ''); ?>">
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

        <div class="profile-info">
            <div class="info-section">
                <h3><i class="fas fa-map-marker-alt"></i> Location</h3>
                <p><?php echo $user['location'] ? htmlspecialchars($user['location']) : 'Not specified'; ?></p>
            </div>

            <div class="info-section">
                <h3><i class="fas fa-industry"></i> Industry</h3>
                <p><?php echo $user['industry'] ? htmlspecialchars($user['industry']) : 'Not specified'; ?></p>
            </div>

            <div class="info-section">
                <h3><i class="fas fa-user-circle"></i> About Me</h3>
                <p><?php echo $user['introduction'] ? nl2br(htmlspecialchars($user['introduction'])) : 'No introduction provided.'; ?></p>
            </div>

            <div class="info-section">
                <h3><i class="fas fa-trophy"></i> Accomplishments</h3>
                <p><?php echo $user['accomplishments'] ? nl2br(htmlspecialchars($user['accomplishments'])) : 'No accomplishments listed.'; ?></p>
            </div>

            <div class="info-section">
                <h3><i class="fas fa-graduation-cap"></i> Education</h3>
                <p><?php echo $user['education'] ? nl2br(htmlspecialchars($user['education'])) : 'No education information provided.'; ?></p>
            </div>

            <div class="info-section">
                <h3><i class="fas fa-briefcase"></i> Employment</h3>
                <p><?php echo $user['employment'] ? nl2br(htmlspecialchars($user['employment'])) : 'No employment history provided.'; ?></p>
            </div>

            <div class="info-section">
                <h3><i class="fas fa-user"></i> Personal Information</h3>
                <p><strong>Gender:</strong> <?php echo $user['gender'] ? ucfirst(htmlspecialchars($user['gender'])) : 'Not specified'; ?></p>
                <p><strong>Birthdate:</strong> <?php echo $user['birthdate'] ? date('F j, Y', strtotime($user['birthdate'])) : 'Not specified'; ?></p>
            </div>

            <div class="info-section">
                <h3><i class="fas fa-address-card"></i> Contact Information</h3>
                <?php if ($user['public_email']): ?>
                    <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo htmlspecialchars($user['public_email']); ?></p>
                <?php endif; ?>
                <?php if ($user['contact_number']): ?>
                    <p><i class="fas fa-phone"></i> <strong>Contact:</strong> <?php echo htmlspecialchars($user['contact_number']); ?></p>
                <?php endif; ?>
                <?php if (empty($user['public_email']) && empty($user['contact_number'])): ?>
                    <p>No contact information provided.</p>
                <?php endif; ?>
            </div>

            <div class="info-section">
                <h3><i class="fas fa-share-alt"></i> Social Media</h3>
                <div class="social-links">
                    <?php if (!empty($user['facebook_url'])): ?>
                        <a href="<?php echo htmlspecialchars($user['facebook_url']); ?>" target="_blank" class="social-link">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($user['twitter_url'])): ?>
                        <a href="<?php echo htmlspecialchars($user['twitter_url']); ?>" target="_blank" class="social-link">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($user['instagram_url'])): ?>
                        <a href="<?php echo htmlspecialchars($user['instagram_url']); ?>" target="_blank" class="social-link">
                            <i class="fab fa-instagram"></i> Instagram
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($user['linkedin_url'])): ?>
                        <a href="<?php echo htmlspecialchars($user['linkedin_url']); ?>" target="_blank" class="social-link">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                    <?php endif; ?>
                    <?php if (empty($user['facebook_url']) && empty($user['twitter_url']) && empty($user['instagram_url']) && empty($user['linkedin_url'])): ?>
                        <p>No social media links provided.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($user['role'] === 'investor'): ?>
            <div class="profile-section">
                <h2><i class="fas fa-handshake"></i> Matched Startups</h2>
                <div class="grid-container">
                    <?php if (!empty($startups)): ?>
                        <?php foreach ($startups as $startup): ?>
                            <div class="grid-item">
                                <div class="startup-header">
                                <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                                </div>
                                <div class="startup-details">
                                    <p><i class="fas fa-industry"></i> <strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                                    <p><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
                                </div>
                                <div class="startup-actions">
                                <a href="startup_detail.php?startup_id=<?php echo $startup['startup_id']; ?>" class="view-more">
                                        <i class="fas fa-eye"></i> View Details
                                </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-handshake"></i>
                        <p>No matched startups yet.</p>
                        </div>
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
                        <div class="resume-header">
                            <i class="fas fa-file-pdf"></i>
                        <h3>Current Resume</h3>
                        </div>
                        <div class="resume-details">
                            <p><i class="fas fa-file-name"></i> <strong>File Name:</strong> <?php echo htmlspecialchars($current_resume['file_name']); ?></p>
                            <p><i class="fas fa-clock"></i> <strong>Uploaded:</strong> <?php echo date('F j, Y g:i A', strtotime($current_resume['uploaded_at'])); ?></p>
                        </div>
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
                            <?php endif; ?>
                        </div>
                    </div>
                <?php elseif ($is_own_profile): ?>
                    <div class="empty-state">
                        <i class="fas fa-file-upload"></i>
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
                    <div class="empty-state">
                        <i class="fas fa-file-alt"></i>
                    <p>No resume available.</p>
                    </div>
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
                                <div class="application-header">
                                <h3><?php echo htmlspecialchars($application['role']); ?></h3>
                                    <span class="status-badge status-<?php echo strtolower($application['status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($application['status'])); ?>
                                    </span>
                                </div>
                                <div class="application-details">
                                    <p><i class="fas fa-building"></i> <strong>Company:</strong> <?php echo htmlspecialchars($application['startup_name']); ?></p>
                                    <p><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> <?php echo htmlspecialchars($application['location']); ?></p>
                                    <p><i class="fas fa-money-bill-wave"></i> <strong>Salary Range:</strong> ₱<?php echo number_format($application['salary_range_min'], 2); ?> - ₱<?php echo number_format($application['salary_range_max'], 2); ?></p>
                                </div>
                                <div class="application-actions">
                                <a href="job-details.php?job_id=<?php echo $application['job_id']; ?>" class="view-more">
                                        <i class="fas fa-eye"></i> View Details
                                </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-briefcase"></i>
                        <p>No job applications yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($user['role'] === 'entrepreneur'): ?>
            <div class="profile-section">
                <h2><i class="fas fa-building"></i> Listed Startups</h2>
                <div class="grid-container">
                    <?php 
                    // Filter startups based on whether it's the owner viewing
                    $display_startups = $is_own_profile ? $listed_startups : array_filter($listed_startups, function($startup) {
                        return $startup['approval_status'] === 'approved';
                    });
                    ?>
                    <?php if (!empty($display_startups)): ?>
                        <?php foreach ($display_startups as $startup): ?>
                            <div class="grid-item">
                                <div class="startup-header">
                                <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                                    <?php if ($is_own_profile): ?>
                                    <span class="status-badge status-<?php echo strtolower($startup['approval_status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($startup['approval_status'])); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <div class="startup-details">
                                    <p><i class="fas fa-industry"></i> <strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                                    <p><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
                                </div>
                                <div class="startup-actions">
                                <a href="startup_detail.php?startup_id=<?php echo $startup['startup_id']; ?>" class="view-more">
                                        <i class="fas fa-eye"></i> View Details
                                </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-lightbulb"></i>
                        <p>No startups listed yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

        // Profile picture handling
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                const maxSize = 5 * 1024 * 1024; // 5MB

                if (!allowedTypes.includes(file.type)) {
                    alert('Please upload a JPEG, PNG, or GIF image.');
                    this.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    alert('File is too large. Maximum size is 5MB.');
                    this.value = '';
                    return;
                }

                // Show upload button
                document.getElementById('uploadButton').style.display = 'inline-block';

                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.querySelector('.profile-picture-container');
                    const existingImage = container.querySelector('.profile-picture');
                    const placeholder = container.querySelector('.profile-picture-placeholder');

                    if (existingImage) {
                        existingImage.src = e.target.result;
                    } else if (placeholder) {
                        placeholder.innerHTML = `<img src="${e.target.result}" alt="Profile Picture" class="profile-picture">`;
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle form submission
        document.getElementById('profilePictureForm').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('profile_picture');
            if (!fileInput.files || !fileInput.files[0]) {
                e.preventDefault();
                alert('Please select a file first.');
            }
        });

        $(document).ready(function() {
            // Initialize Select2 on location dropdown
            $('#location').select2({
                theme: 'default',
                width: '100%',
                placeholder: 'Search or select',
                allowClear: true,
                minimumInputLength: 0
            });
        });
    </script>
</body>
</html>
