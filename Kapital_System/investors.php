<?php
ob_start();
session_start();
include('db_connection.php');
include('verification_check.php');

// Redirect if the user is not logged in or does not have the investor role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'investor') {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}
include('navbar.php');

$user_id = $_SESSION['user_id'];

// Check verification status
$verification_status = checkVerification(false);

// Fetch saved startups for the investor, ensuring the startup is approved
$saved_startups_query = "
    SELECT Startups.* 
    FROM Matches
    JOIN Startups ON Matches.startup_id = Startups.startup_id
    WHERE Matches.investor_id = '$user_id'
    AND Startups.approval_status = 'approved' 
    ORDER BY Matches.created_at DESC";
$saved_startups_result = mysqli_query($conn, $saved_startups_query);

// Define industries
$industries = [
    'Technology' => [
        'Software Development',
        'E-commerce',
        'FinTech',
        'EdTech',
        'HealthTech',
        'AI/ML',
        'Cybersecurity',
        'Cloud Computing',
        'Digital Marketing',
        'Mobile Apps'
    ],
    'Healthcare' => [
        'Medical Services',
        'Healthcare Technology',
        'Wellness & Fitness',
        'Mental Health',
        'Telemedicine',
        'Medical Devices',
        'Healthcare Analytics'
    ],
    'Finance' => [
        'Banking',
        'Insurance',
        'Investment',
        'Financial Services',
        'Payment Solutions',
        'Cryptocurrency',
        'Financial Planning'
    ],
    'Education' => [
        'Online Learning',
        'Educational Technology',
        'Skills Training',
        'Language Learning',
        'Professional Development',
        'Educational Content'
    ],
    'Retail' => [
        'E-commerce',
        'Fashion',
        'Food & Beverage',
        'Consumer Goods',
        'Marketplace',
        'Retail Technology'
    ],
    'Manufacturing' => [
        'Industrial Manufacturing',
        'Clean Technology',
        '3D Printing',
        'Supply Chain',
        'Smart Manufacturing'
    ],
    'Agriculture' => [
        'AgTech',
        'Organic Farming',
        'Food Processing',
        'Agricultural Services',
        'Sustainable Agriculture'
    ],
    'Transportation' => [
        'Logistics',
        'Ride-sharing',
        'Delivery Services',
        'Transportation Technology',
        'Smart Mobility'
    ],
    'Real Estate' => [
        'Property Technology',
        'Real Estate Services',
        'Property Management',
        'Real Estate Investment',
        'Smart Homes'
    ],
    'Other' => [
        'Social Impact',
        'Environmental',
        'Creative Industries',
        'Sports & Entertainment',
        'Other Services'
    ]
];

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

// Fetch all startups by default (without filters)
$filter_conditions = "";
if (isset($_GET['industry']) && $_GET['industry'] != "") {
    $industry = mysqli_real_escape_string($conn, $_GET['industry']);
    $filter_conditions .= " AND Startups.industry LIKE '%$industry%'";
}
if (isset($_GET['location']) && $_GET['location'] != "") {
    $location = mysqli_real_escape_string($conn, $_GET['location']);
    $filter_conditions .= " AND Startups.location LIKE '%$location%'";
}
if (isset($_GET['funding_stage']) && $_GET['funding_stage'] != "") {
    $funding_stage = mysqli_real_escape_string($conn, $_GET['funding_stage']);
    $filter_conditions .= " AND Startups.funding_stage = '$funding_stage'";
}

// Query to fetch all startups that match the filters or no filters
$startups_query = "
    SELECT * 
    FROM Startups
    WHERE approval_status = 'approved' 
    AND startup_id NOT IN (
        SELECT startup_id 
        FROM Matches 
        WHERE investor_id = '$user_id'
    )
    $filter_conditions
    ORDER BY created_at DESC";
$startups_result = mysqli_query($conn, $startups_query);

// Handle the match action (button click)
if (isset($_POST['match_startup_id'])) {
    $startup_id = mysqli_real_escape_string($conn, $_POST['match_startup_id']);

    // Check if this match already exists
    $check_match_query = "SELECT * FROM Matches WHERE investor_id = '$user_id' AND startup_id = '$startup_id'";
    $check_match_result = mysqli_query($conn, $check_match_query);

    if (mysqli_num_rows($check_match_result) == 0) {
        // Insert the match into the Matches table
        $insert_match_query = "
            INSERT INTO Matches (investor_id, startup_id, created_at) 
            VALUES ('$user_id', '$startup_id', NOW())";
        mysqli_query($conn, $insert_match_query);

        // Get the last inserted match_id
        $match_id = mysqli_insert_id($conn); // Get the match_id from the Matches table

        // Fetch the entrepreneur's user_id and email for the notification
        $entrepreneur_query = "
            SELECT Users.email, Users.user_id
            FROM Startups
            JOIN Users ON Startups.entrepreneur_id = Users.user_id
            WHERE Startups.startup_id = '$startup_id'";
        $entrepreneur_result = mysqli_query($conn, $entrepreneur_query);
        $entrepreneur = mysqli_fetch_assoc($entrepreneur_result);

        // Insert the notification for the entrepreneur
        $notification_message = "Your startup has been matched with an investor!";
        $notification_url = "match_details.php?match_id=$match_id"; // Use the match_id here
        $insert_notification_query = "
            INSERT INTO Notifications (user_id, sender_id, type, message, url, match_id) 
            VALUES ('" . $entrepreneur['user_id'] . "', '$user_id', 'investment_match', '$notification_message', '$notification_url', '$match_id')";
        mysqli_query($conn, $insert_notification_query);

        // Fetch startup details for the investor notification
        $startup_query = "SELECT name FROM Startups WHERE startup_id = '$startup_id'";
        $startup_result = mysqli_query($conn, $startup_query);
        $startup = mysqli_fetch_assoc($startup_result);

        // Insert the notification for the investor
        $notification_message_investor = "You have successfully matched with the startup: " . htmlspecialchars($startup['name']);
        $insert_notification_investor_query = "
            INSERT INTO Notifications (user_id, sender_id, type, message, match_id) 
            VALUES ('$user_id', NULL, 'investment_match', '$notification_message_investor', '$match_id')";
        mysqli_query($conn, $insert_notification_investor_query);
    }

    // After the match is processed, redirect to avoid resubmission
    header("Location: investors.php");  // Redirect to the same page
    exit();  // Stop the script to avoid any further execution
}

// Handle unmatch action (delete match)
if (isset($_POST['unmatch_startup_id'])) {
    $startup_id = mysqli_real_escape_string($conn, $_POST['unmatch_startup_id']);

    // Delete the match from the Matches table
    $delete_match_query = "DELETE FROM Matches WHERE investor_id = '$user_id' AND startup_id = '$startup_id'";
    mysqli_query($conn, $delete_match_query);

    // After unmatch is processed, redirect to avoid resubmission
    header("Location: investors.php");  // Redirect to the same page
    exit();  // Stop the script to avoid any further execution
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            color: #D8A25E; /* Updated color */
        }

        h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #D8A25E; /* Updated color */
            margin-bottom: 20px;
            text-align: center;
        }

        .investor-name {
            color: #17a2b8;
        }

        h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #17a2b8;
            margin-bottom: 20px;
            text-align: center;
        }

        .startup-post {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
            overflow: hidden;
        }

        .startup-post:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .startup-post h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 15px;
        }

        .startup-post p {
            font-size: 1rem;
            color: #555;
            margin: 5px 0;
        }

        .startup-post .btn {
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
        }

        .startup-post .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .startup-post .btn-info:hover {
            background-color: #138496;
        }

        .startup-post .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .startup-post .btn-primary:hover {
            background-color: #0056b3;
        }

        .startup-post .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .startup-post .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-secondary {
            background-color: #6c757d; /* Remains the same */
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-control {
            margin-bottom: 10px;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
        }

        .form-control[type="text"] {
            font-size: 1rem;
        }

        .startup-header {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .startup-logo {
            width: 100px;
            height: 100px;
            flex-shrink: 0;
            border-radius: 8px;
            overflow: hidden;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .startup-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .startup-logo i {
            font-size: 40px;
            color: #17a2b8;
        }

        .startup-info {
            flex-grow: 1;
        }

        .startup-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .startup-post {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
        }

        .startup-post:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .startup-post h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
            margin-top: 0;
        }

        .startup-post p {
            font-size: 1rem;
            color: #555;
            margin: 5px 0;
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            .startup-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .startup-logo {
                width: 80px;
                height: 80px;
            }

            .startup-actions {
                justify-content: center;
            }
        }

        .search-section {
            background: #23272A;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 1px solid #40444B;
        }

        .search-section h2 {
            color: #7289DA;
            font-size: 1.5rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #2C2F33;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #40444B;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group:hover {
            border-color: #7289DA;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-group label {
            color: #B9BBBE;
            font-size: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            color: #7289DA;
            font-size: 1.1rem;
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

        .search-button {
            background: #7289DA;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-button:hover {
            background: #5b6eae;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .search-button i {
            font-size: 1.1rem;
        }

        .clear-filters {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #B9BBBE;
            text-decoration: none;
            margin-left: 20px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 6px;
        }

        .clear-filters:hover {
            color: #FFFFFF;
            background: rgba(114, 137, 218, 0.1);
        }

        .clear-filters i {
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .search-section {
                padding: 20px;
                margin: 15px;
            }

            .search-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-group {
                padding: 12px;
            }

            .search-button {
                width: 100%;
                justify-content: center;
                padding: 12px;
            }

            .clear-filters {
                display: flex;
                justify-content: center;
                margin: 15px 0 0 0;
                width: 100%;
                padding: 12px;
                background: rgba(114, 137, 218, 0.05);
            }
        }

        /* Add a universal box-sizing rule */
        *, *:before, *:after {
            box-sizing: border-box;
        }

        .verification-notice {
            background: #23272A;
            border: 1px solid #40444B;
            color: #FFFFFF;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .verification-notice h3 {
            color: #7289DA;
            font-size: 1.5rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .verification-notice h3 i {
            color: #7289DA;
            font-size: 1.8rem;
        }

        .verification-notice p {
            color: #B9BBBE;
            margin-bottom: 15px;
        }

        .verification-notice ul {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
        }

        .verification-notice ul li {
            color: #B9BBBE;
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .verification-notice ul li:before {
            content: "•";
            color: #7289DA;
            font-size: 1.2rem;
        }

        .verification-notice .btn-warning {
            background: #7289DA;
            color: #FFFFFF;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .verification-notice .btn-warning:hover {
            background: #5b6eae;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .verification-notice {
                padding: 20px;
                margin: 15px;
            }

            .verification-notice h3 {
                font-size: 1.3rem;
            }
        }

        /* Select2 Custom Styles */
        .select2-container--default .select2-selection--single {
            background-color: #2C2F33;
            border: 1px solid #40444B;
            border-radius: 6px;
            color: #FFFFFF;
            height: 42px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #FFFFFF;
            line-height: 42px;
            padding-left: 15px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .select2-container--default .select2-results__option {
            background-color: #2C2F33;
            color: #FFFFFF;
            padding: 10px 15px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #7289DA;
            color: #FFFFFF;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #2C2F33;
            color: #FFFFFF;
            border: 1px solid #40444B;
            border-radius: 4px;
            padding: 8px;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            outline: none;
            border-color: #7289DA;
        }

        .select2-dropdown {
            background-color: #2C2F33;
            border: 1px solid #40444B;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: rgba(114, 137, 218, 0.2);
            color: #7289DA;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #B9BBBE;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #7289DA transparent transparent transparent;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #7289DA transparent;
        }

        /* Style for optgroups */
        .select2-results__group {
            background-color: #23272A;
            color: #7289DA;
            font-weight: bold;
            padding: 8px 10px;
        }

        /* Style for options within optgroups */
        .select2-results__option {
            padding-left: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Welcome, <span class="investor-name">Investor!</span></h1>

        <?php if ($verification_status !== 'verified'): ?>
            <div class="verification-notice">
                <h3><i class="fas fa-exclamation-triangle"></i> Account Verification Required</h3>
                <p>Your account needs to be verified to access the following features:</p>
                <ul>
                    <li>Matching with startups</li>
                    <li>Viewing startup details</li>
                    <li>Communicating with entrepreneurs</li>
                    <li>Accessing investment opportunities</li>
                </ul>
                <a href="verify_account.php" class="btn btn-warning">Verify Your Account</a>
            </div>
        <?php endif; ?>

        <div class="search-section">
            <h2><i class="fas fa-search"></i> Search & Filter Startups</h2>
            <form id="search-filter-form" method="GET" action="investors.php">
                <div class="search-grid">
                    <div class="form-group">
                        <label for="industry"><i class="fas fa-industry"></i> Industry</label>
                        <select id="industry" name="industry" class="select2">
                            <option value="">All Industries</option>
                            <?php foreach ($industries as $category => $subcategories): ?>
                                <optgroup label="<?php echo htmlspecialchars($category); ?>">
                                    <?php foreach ($subcategories as $subcategory): ?>
                                        <option value="<?php echo htmlspecialchars($subcategory); ?>" <?php echo isset($_GET['industry']) && $_GET['industry'] == $subcategory ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($subcategory); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="location"><i class="fas fa-map-marker-alt"></i> Location</label>
                        <select id="location" name="location" class="select2">
                            <option value="">All Locations</option>
                            <?php foreach ($locations as $region => $cities): ?>
                                <optgroup label="<?php echo htmlspecialchars($region); ?>">
                                    <?php foreach ($cities as $city): ?>
                                        <option value="<?php echo htmlspecialchars($city); ?>" <?php echo isset($_GET['location']) && $_GET['location'] == $city ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($city); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="funding_stage"><i class="fas fa-chart-line"></i> Funding Stage</label>
                        <select id="funding_stage" name="funding_stage" class="form-control">
                            <option value="">All Stages</option>
                            <option value="startup" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'startup' ? 'selected' : ''; ?>>Startup Stage</option>
                            <option value="seed" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'seed' ? 'selected' : ''; ?>>Seed</option>
                            <option value="series_a" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'series_a' ? 'selected' : ''; ?>>Series A</option>
                            <option value="series_b" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'series_b' ? 'selected' : ''; ?>>Series B</option>
                            <option value="series_c" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'series_c' ? 'selected' : ''; ?>>Series C</option>
                            <option value="exit" <?php echo isset($_GET['funding_stage']) && $_GET['funding_stage'] == 'exit' ? 'selected' : ''; ?>>Exit</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="search-button">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>

                <?php if (isset($_GET['industry']) || isset($_GET['location']) || isset($_GET['funding_stage'])): ?>
                    <a href="investors.php" class="clear-filters">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <h2>Matched Startups</h2>
        <?php if (mysqli_num_rows($saved_startups_result) > 0): ?>
            <?php while ($startup = mysqli_fetch_assoc($saved_startups_result)): ?>
                <div class="startup-post">
                    <div class="startup-header">
                        <div class="startup-logo">
                            <?php if (!empty($startup['logo_url']) && file_exists($startup['logo_url'])): ?>
                                <img src="<?php echo htmlspecialchars($startup['logo_url']); ?>" alt="<?php echo htmlspecialchars($startup['name']); ?> logo">
                            <?php else: ?>
                                <i class="fas fa-building"></i>
                            <?php endif; ?>
                        </div>
                        <div class="startup-info">
                            <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                            <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>
                        </div>
                    </div>

                    <div class="startup-actions">
                        <a href="startup_detail.php?startup_id=<?php echo htmlspecialchars($startup['startup_id']); ?>" class="btn btn-info">View Details</a>
                        <?php if ($verification_status === 'verified'): ?>
                            <form method="POST" action="investors.php" style="display:inline;" class="match-form" data-startup-id="<?php echo htmlspecialchars($startup['startup_id']); ?>">
                                <input type="hidden" name="unmatch_startup_id" value="<?php echo htmlspecialchars($startup['startup_id']); ?>">
                                <button type="submit" class="btn btn-danger">Unmatch</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No saved startups found or no approved startups available.</p>
        <?php endif; ?>

        <h2>Explore Startups</h2>
        <?php if (mysqli_num_rows($startups_result) > 0): ?>
            <?php while ($startup = mysqli_fetch_assoc($startups_result)): ?>
                <div class="startup-post">
                    <div class="startup-header">
                        <div class="startup-logo">
                            <?php if (!empty($startup['logo_url']) && file_exists($startup['logo_url'])): ?>
                                <img src="<?php echo htmlspecialchars($startup['logo_url']); ?>" alt="<?php echo htmlspecialchars($startup['name']); ?> logo">
                            <?php else: ?>
                                <i class="fas fa-building"></i>
                            <?php endif; ?>
                        </div>
                        <div class="startup-info">
                            <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                            <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>
                        </div>
                    </div>

                    <div class="startup-actions">
                        <?php if ($verification_status === 'verified'): ?>
                            <form method="POST" action="investors.php" style="display:inline;" class="match-form" data-startup-id="<?php echo htmlspecialchars($startup['startup_id']); ?>">
                                <input type="hidden" name="match_startup_id" value="<?php echo htmlspecialchars($startup['startup_id']); ?>">
                                <button type="submit" class="btn btn-primary">Match</button>
                            </form>
                        <?php endif; ?>
                        <a href="startup_detail.php?startup_id=<?php echo htmlspecialchars($startup['startup_id']); ?>" class="btn btn-info">View Details</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No approved startups found with the current filter.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 on industry and location dropdowns
            $('#industry, #location').select2({
                theme: 'default',
                width: '100%',
                placeholder: 'Search or select an option',
                allowClear: true,
                minimumInputLength: 1
            });
        });
    </script>
</body>

</html>
