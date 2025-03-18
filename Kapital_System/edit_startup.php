<?php
ob_start(); // Start output buffering
session_start();
include('navbar.php'); // Include navbar
include('db_connection.php'); // Include database connection

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

$user_id = $_SESSION['user_id'];

// Function to handle file uploads
function uploadFile($file, $upload_dir) {
    if (!empty($file["name"])) {
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = basename($file["name"]);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $new_file_name = uniqid() . "_" . $file_name;
        $target_file = $upload_dir . $new_file_name;

        // Check file type
        $allowed_types = ["jpg", "jpeg", "png"];
        if (!in_array($file_ext, $allowed_types)) {
            return ["success" => false, "message" => "Only JPG, JPEG & PNG files are allowed."];
        }

        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return ["success" => true, "path" => $target_file];
        } else {
            return ["success" => false, "message" => "Failed to upload file."];
        }
    }
    return ["success" => true, "path" => ""];
}

// Get the startup ID from the query string
if (isset($_GET['startup_id'])) {
    $startup_id = $_GET['startup_id'];

    // Fetch the startup details
    $query_startup = "
        SELECT * 
        FROM Startups 
        WHERE startup_id = '$startup_id' 
        AND entrepreneur_id = (SELECT entrepreneur_id FROM Entrepreneurs WHERE entrepreneur_id = '$user_id')
    ";
    $result_startup = mysqli_query($conn, $query_startup);

    if ($result_startup && mysqli_num_rows($result_startup) > 0) {
        $startup = mysqli_fetch_assoc($result_startup);
    } else {
        die("Startup not found or you don't have permission to edit this startup.");
    }
} else {
    die("No startup ID provided.");
}

// Handle form submission for updating the startup
if (isset($_POST['update_startup'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $industry = mysqli_real_escape_string($conn, $_POST['industry']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $pitch_deck_url = mysqli_real_escape_string($conn, $_POST['pitch_deck_url']);
    $business_plan_url = mysqli_real_escape_string($conn, $_POST['business_plan_url']);

    // Check and add missing columns
    $required_columns = [
        'funding_stage' => 'VARCHAR(50) DEFAULT "seed"',
        'website' => 'VARCHAR(255) DEFAULT NULL',
        'pitch_deck_url' => 'VARCHAR(255) DEFAULT NULL',
        'business_plan_url' => 'VARCHAR(255) DEFAULT NULL',
        'logo_url' => 'VARCHAR(255) DEFAULT NULL'
    ];

    foreach ($required_columns as $column => $definition) {
        $check_column = "SHOW COLUMNS FROM Startups LIKE '$column'";
        $column_exists = mysqli_query($conn, $check_column);
        
        if (mysqli_num_rows($column_exists) == 0) {
            // Add the column if it doesn't exist
            $add_column = "ALTER TABLE Startups ADD COLUMN $column $definition";
            if (!mysqli_query($conn, $add_column)) {
                die("Error adding column $column: " . mysqli_error($conn));
            }
        }
    }

    // Handle logo upload
    $logo_path = $startup['logo_url'] ?? ''; // Keep existing logo by default
    if (isset($_FILES["logo"]) && $_FILES["logo"]["error"] === UPLOAD_ERR_OK) {
        $logo_upload = uploadFile($_FILES["logo"], "uploads/logos/");
        if ($logo_upload["success"]) {
            // If upload successful, update logo path
            $logo_path = $logo_upload["path"];
            // Delete old logo if it exists
            if (!empty($startup['logo_url']) && file_exists($startup['logo_url'])) {
                unlink($startup['logo_url']);
            }
        } else {
            $error_message = "Error uploading logo: " . $logo_upload["message"];
        }
    }

    if (!isset($error_message)) {
        // Now include funding_stage in the update
        $funding_stage = mysqli_real_escape_string($conn, $_POST['funding_stage']);
        
        $query_update = "
            UPDATE Startups 
            SET 
                name = '$name',
                industry = '$industry',
                description = '$description',
                location = '$location',
                website = '$website',
                pitch_deck_url = '$pitch_deck_url',
                business_plan_url = '$business_plan_url',
                logo_url = '$logo_path',
                funding_stage = '$funding_stage'
            WHERE startup_id = '$startup_id'
        ";

        if (mysqli_query($conn, $query_update)) {
            // Redirect to entrepreneurs.php after successful update
            header("Location: entrepreneurs.php");
            exit;
        } else {
            $error_message = "Error updating startup: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Startup</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #2C2F33;
            color: #f9f9f9;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: #23272A;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: 1px solid #40444B;
        }

        h1 {
            font-size: 2rem;
            color: #7289DA;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-size: 1rem;
            color: #7289DA;
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            background-color: #2C2F33;
            border: 1px solid #40444B;
            border-radius: 8px;
            color: #f9f9f9;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #7289DA;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }

        button {
            background-color: #7289DA;
            color: white;
            padding: 14px 28px;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        button:hover {
            background-color: #5b6eae;
            transform: translateY(-2px);
        }

        .error-message {
            color: #f04747;
            background: rgba(240, 71, 71, 0.1);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Logo Upload Styles */
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
            background: rgba(114, 137, 218, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px dashed #7289DA;
            transition: all 0.3s ease;
        }

        .logo-preview:hover {
            border-color: #5b6eae;
            background: rgba(114, 137, 218, 0.15);
        }

        .logo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
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

        @media (max-width: 768px) {
            .container {
                width: 95%;
                margin: 20px auto;
                padding: 20px;
            }

            .logo-upload {
                width: 120px;
                height: 120px;
                margin-bottom: 20px;
            }

            .logo-preview {
                width: 120px;
                height: 120px;
            }

            h1 {
                font-size: 1.6rem;
            }

            .form-group label {
                font-size: 0.95rem;
            }

            button {
                padding: 12px 24px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Edit Startup</h1>
        <?php if (isset($error_message)) {
            echo "<div class='error-message'><i class='fas fa-exclamation-circle'></i> $error_message</div>";
        } ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="logo-upload">
                <label for="logo">
                    <div class="logo-preview" id="logoPreview">
                        <?php if (!empty($startup['logo_url']) && file_exists($startup['logo_url'])): ?>
                            <img src="<?php echo htmlspecialchars($startup['logo_url']); ?>" alt="Current Logo">
                        <?php else: ?>
                            <i class="fas fa-building default-logo-icon"></i>
                        <?php endif; ?>
                    </div>
                </label>
                <span class="logo-label">Click to Change Logo</span>
                <input type="file" id="logo" name="logo" accept="image/png, image/jpeg, image/jpg" onchange="previewLogo(this);">
            </div>
            <div class="form-group">
                <label for="name"><i class="fas fa-building"></i> Startup Name</label>
                <input type="text" id="name" name="name" value="<?php echo isset($startup['name']) ? htmlspecialchars($startup['name']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="industry"><i class="fas fa-industry"></i> Industry</label>
                <input type="text" id="industry" name="industry" value="<?php echo isset($startup['industry']) ? htmlspecialchars($startup['industry']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="funding_stage"><i class="fas fa-chart-line"></i> Funding Stage</label>
                <select id="funding_stage" name="funding_stage" required>
                    <?php
                    $current_stage = isset($startup['funding_stage']) ? $startup['funding_stage'] : '';
                    $funding_stages = [
                        'pre_seed' => 'Pre-Seed',
                        'seed' => 'Seed',
                        'series_a' => 'Series A',
                        'series_b' => 'Series B',
                        'series_c' => 'Series C',
                        'late_stage' => 'Late Stage',
                        'exit' => 'Exit'
                    ];
                    foreach ($funding_stages as $value => $label) {
                        $selected = ($current_stage == $value) ? 'selected' : '';
                        echo "<option value=\"" . htmlspecialchars($value) . "\" $selected>" . htmlspecialchars($label) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description"><i class="fas fa-align-left"></i> Description</label>
                <textarea id="description" name="description" required><?php echo isset($startup['description']) ? htmlspecialchars($startup['description']) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="location"><i class="fas fa-map-marker-alt"></i> Location</label>
                <input type="text" id="location" name="location" value="<?php echo isset($startup['location']) ? htmlspecialchars($startup['location']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="website"><i class="fas fa-globe"></i> Website</label>
                <input type="url" id="website" name="website" value="<?php echo isset($startup['website']) && $startup['website'] !== null ? htmlspecialchars($startup['website']) : ''; ?>" placeholder="https://">
            </div>
            <div class="form-group">
                <label for="pitch_deck_url"><i class="fas fa-file-powerpoint"></i> Pitch Deck URL</label>
                <input type="url" id="pitch_deck_url" name="pitch_deck_url" value="<?php echo isset($startup['pitch_deck_url']) && $startup['pitch_deck_url'] !== null ? htmlspecialchars($startup['pitch_deck_url']) : ''; ?>" placeholder="https://">
            </div>
            <div class="form-group">
                <label for="business_plan_url"><i class="fas fa-file-pdf"></i> Business Plan URL</label>
                <input type="url" id="business_plan_url" name="business_plan_url" value="<?php echo isset($startup['business_plan_url']) && $startup['business_plan_url'] !== null ? htmlspecialchars($startup['business_plan_url']) : ''; ?>" placeholder="https://">
            </div>
            <button type="submit" name="update_startup">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>
    </div>

    <script>
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
<?php ob_end_flush(); ?>