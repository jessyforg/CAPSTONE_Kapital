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
    $funding_stage = mysqli_real_escape_string($conn, $_POST['funding_stage']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $pitch_deck_url = mysqli_real_escape_string($conn, $_POST['pitch_deck_url']);
    $business_plan_url = mysqli_real_escape_string($conn, $_POST['business_plan_url']);

    $query_update = "
        UPDATE Startups 
        SET 
            name = '$name',
            industry = '$industry',
            funding_stage = '$funding_stage',
            description = '$description',
            location = '$location',
            website = '$website',
            pitch_deck_url = '$pitch_deck_url',
            business_plan_url = '$business_plan_url'
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Startup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 1rem;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
            height: 150px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Edit Startup</h1>
        <?php if (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        } ?>
        <form method="POST">
            <div class="form-group">
                <label for="name">Startup Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($startup['name']); ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="industry">Industry</label>
                <input type="text" id="industry" name="industry"
                    value="<?php echo htmlspecialchars($startup['industry']); ?>" required>
            </div>
            <div class="form-group">
                <label for="funding_stage">Funding Stage</label>
                <select id="funding_stage" name="funding_stage" required>
                    <option value="seed" <?php echo $startup['funding_stage'] == 'seed' ? 'selected' : ''; ?>>Seed
                    </option>
                    <option value="series_a" <?php echo $startup['funding_stage'] == 'series_a' ? 'selected' : ''; ?>>
                        Series A</option>
                    <option value="series_b" <?php echo $startup['funding_stage'] == 'series_b' ? 'selected' : ''; ?>>
                        Series B</option>
                    <option value="series_c" <?php echo $startup['funding_stage'] == 'series_c' ? 'selected' : ''; ?>>
                        Series C</option>
                    <option value="exit" <?php echo $startup['funding_stage'] == 'exit' ? 'selected' : ''; ?>>Exit
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"
                    required><?php echo htmlspecialchars($startup['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location"
                    value="<?php echo htmlspecialchars($startup['location']); ?>">
            </div>
            <div class="form-group">
                <label for="website">Website</label>
                <input type="text" id="website" name="website"
                    value="<?php echo htmlspecialchars($startup['website']); ?>">
            </div>
            <div class="form-group">
                <label for="pitch_deck_url">Pitch Deck URL</label>
                <input type="text" id="pitch_deck_url" name="pitch_deck_url"
                    value="<?php echo htmlspecialchars($startup['pitch_deck_url']); ?>">
            </div>
            <div class="form-group">
                <label for="business_plan_url">Business Plan URL</label>
                <input type="text" id="business_plan_url" name="business_plan_url"
                    value="<?php echo htmlspecialchars($startup['business_plan_url']); ?>">
            </div>
            <button type="submit" name="update_startup">Save Changes</button>
        </form>
    </div>
</body>

</html>
<?php ob_end_flush(); ?>