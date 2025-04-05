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
function uploadFile($file, $upload_dir, $allowed_types)
{
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
        if (isset($_FILES["logo"]) && $_FILES["logo"]["error"] === UPLOAD_ERR_OK) {
            $logo_upload = uploadFile($_FILES["logo"], "uploads/logos/", ["jpg", "jpeg", "png"]);
        } else {
            $logo_upload = ["success" => true, "path" => ""]; // No file uploaded or error occurred
        }

        if (!$logo_upload["success"]) {
            echo "<script>alert('" . $logo_upload["message"] . "');</script>";
        }
        $logo_path = $logo_upload["path"];
        // Handle file upload (Video Pitch / General File)
        if (isset($_FILES["file"]) && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {
            $file_upload = uploadFile($_FILES["file"], "uploads/files/", ["mp4", "avi", "mov", "pdf", "docx", "pptx"]);
        } else {
            $file_upload = ["success" => true, "path" => ""]; // No file uploaded or error occurred
        }

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
                description, 
                location,
                pitch_deck_url, 
                business_plan_url,
                logo_url,
                video_url
            ) VALUES (
                '$user_id', 
                '$startup_name', 
                '$industry', 
                '$description', 
                '$location', 
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
            position: relative;
            /* Added for absolute positioning of logo upload */
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
            width: 100%;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23f3c000' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: calc(100% - 15px) center;
            padding-right: 40px;
            transition: all 0.3s ease;
            box-sizing: border-box; /* Ensure padding and border are included in the element's total width and height */
        }

        select option {
            background-color: #333;
            color: #fff;
            padding: 10px;
        }

        select:focus {
            outline: none;
            border-color: #f3c000;
            box-shadow: 0 0 0 2px rgba(243, 192, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.25);
        }

        select:hover {
            border-color: #f3c000;
            background-color: rgba(255, 255, 255, 0.25);
        }

        /* Style for optgroups */
        select optgroup {
            background-color: #2a2a2a;
            color: #f3c000;
            font-weight: bold;
            padding: 5px 0;
        }

        /* Style for options within optgroups */
        select optgroup option {
            background-color: #333;
            color: #fff;
            font-weight: normal;
            padding: 8px 10px;
            margin-left: 10px;
        }

        /* Hover effect for options */
        select option:hover {
            background-color: #f3c000;
            color: #333;
        }

        /* Style for disabled options */
        select option:disabled {
            background-color: #444;
            color: #888;
        }

        /* Custom scrollbar for dropdowns */
        select::-webkit-scrollbar {
            width: 8px;
        }

        select::-webkit-scrollbar-track {
            background: #333;
            border-radius: 4px;
        }

        select::-webkit-scrollbar-thumb {
            background: #f3c000;
            border-radius: 4px;
        }

        select::-webkit-scrollbar-thumb:hover {
            background: #d8a25e;
        }

        /* Mobile optimization */
        @media (max-width: 768px) {
            select {
                font-size: 16px; /* Prevent zoom on iOS */
                padding: 12px 35px 12px 12px; /* Adjust padding for mobile */
            }

            select option {
                padding: 12px;
                font-size: 16px;
            }
        }

        .success {
            color: #4caf50;
        }

        .error {
            color: #f44336;
        }

        .logo-upload {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 30px;
            cursor: pointer;
            text-align: center;
        }

        .logo-preview {
            width: 150px;
            height: 150px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px dashed #7289DA;
            transition: all 0.3s ease;
        }

        .logo-preview:hover {
            border-color: #5b6eae;
            background: rgba(255, 255, 255, 0.15);
        }

        .logo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-upload input {
            display: none;
        }

        .logo-label {
            font-size: 14px;
            color: #7289DA;
            margin-top: 10px;
            display: block;
            text-align: center;
        }

        .default-logo-icon {
            font-size: 50px;
            color: #7289DA;
        }

        /* Add this at the end of your existing CSS */
        @media (max-width: 768px) {
            .logo-upload {
                width: 120px;
                height: 120px;
                margin-bottom: 20px;
            }

            .logo-preview {
                width: 120px;
                height: 120px;
            }
        }

        /* Select2 Custom Styles */
        .select2-container--default .select2-selection--single {
            background-color: #2C2F33;
            border: 1px solid #40444B;
            border-radius: 6px;
            color: #FFFFFF;
            height: 42px;
            overflow: hidden;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #FFFFFF;
            line-height: 42px;
            padding-left: 15px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .select2-container--default .select2-results__option {
            background-color: #2C2F33;
            color: #FFFFFF;
            padding: 10px 15px;
            text-align: left;
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
            overflow: auto;
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
            color: #f3c000;
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
        <h1>Create Your Startup Profile</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="logo-upload">
                <label for="logo">
                    <div class="logo-preview" id="logoPreview">
                        <i class="fas fa-building default-logo-icon"></i>
                    </div>
                </label>
                <span class="logo-label">Click to Upload Logo</span>
                <input type="file" id="logo" name="logo" accept="image/png, image/jpeg, image/jpg" onchange="previewLogo(this);">
            </div>
            <div class="form-group">
                <label for="startup_name">Startup Name</label>
                <input type="text" id="startup_name" name="startup_name" placeholder="Enter your startup's name"
                    required>
            </div>

            <div class="form-group">
                <label for="industry">Industry</label>
                <select id="industry" name="industry" class="select2" required>
                    <option value="">Select Industry</option>
                    <?php foreach ($industries as $category => $subcategories): ?>
                        <optgroup label="<?php echo htmlspecialchars($category); ?>">
                            <?php foreach ($subcategories as $subcategory): ?>
                                <option value="<?php echo htmlspecialchars($subcategory); ?>">
                                    <?php echo htmlspecialchars($subcategory); ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="funding_stage">Funding Stage</label>
                <select id="funding_stage" name="funding_stage" required>
                    <option value="startup">Startup Stage</option>
                    <option value="seed">Seed</option>
                    <option value="series_a">Series A</option>
                    <option value="series_b">Series B</option>
                    <option value="series_c">Series C</option>
                    <option value="exit">Exit</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Startup Description</label>
                <textarea id="description" name="description" placeholder="Provide a brief description of your startup"
                    required></textarea>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <select id="location" name="location" class="select2" required>
                    <option value="">Select Location</option>
                    <?php foreach ($locations as $region => $cities): ?>
                        <optgroup label="<?php echo htmlspecialchars($region); ?>">
                            <?php foreach ($cities as $city): ?>
                                <option value="<?php echo htmlspecialchars($city); ?>">
                                    <?php echo htmlspecialchars($city); ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="file">Video Pitch / File Upload</label>
                <input type="file" id="file" name="file"
                    accept="video/mp4, video/avi, video/mov, application/pdf, application/msword, application/vnd.ms-powerpoint">
            </div>

            <div class="form-group">
                <label for="website">Website</label>
                <input type="text" id="website" name="website" placeholder="Enter your website URL">
            </div>

            <div class="form-group">
                <label for="pitch_deck_url">Pitch Deck URL</label>
                <input type="text" id="pitch_deck_url" name="pitch_deck_url"
                    placeholder="Enter the URL to your pitch deck">
            </div>

            <div class="form-group">
                <label for="business_plan_url">Business Plan URL</label>
                <input type="text" id="business_plan_url" name="business_plan_url"
                    placeholder="Enter the URL to your business plan">
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Reinitialize Select2 on industry and location dropdowns
            $('#industry, #location').select2('destroy').select2({
                theme: 'default',
                width: '100%',
                placeholder: 'Search or select an option',
                allowClear: true,
                minimumInputLength: 0, // Allow search with no minimum input
                dropdownAutoWidth: true, // Adjust dropdown width automatically
            });
        });

        function previewLogo(input) {
            const preview = document.getElementById('logoPreview');
            const defaultIcon = preview.querySelector('.default-logo-icon');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (defaultIcon) {
                        defaultIcon.style.display = 'none';
                    }
                    
                    let img = preview.querySelector('img');
                    if (!img) {
                        img = document.createElement('img');
                        preview.appendChild(img);
                    }
                    img.src = e.target.result;
                }
                
                reader.readAsDataURL(input.files[0]);
            } else if (defaultIcon) {
                defaultIcon.style.display = 'block';
                const img = preview.querySelector('img');
                if (img) {
                    img.remove();
                }
            }
        }
    </script>
</body>

</html>