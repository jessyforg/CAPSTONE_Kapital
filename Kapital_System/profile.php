<?php
session_start();
include('navbar.php');
include('db_connection.php');

// Redirect if the user is not logged in or does not have the entrepreneur role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

$user_id = $_SESSION['user_id'];

// Retrieve entrepreneur details from the database
$query = "SELECT * FROM Entrepreneurs WHERE entrepreneur_id = '$user_id'";
$result = mysqli_query($conn, $query);
$entrepreneur = mysqli_fetch_assoc($result);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $startup_name = mysqli_real_escape_string($conn, $_POST['startup_name']);
    $industry = mysqli_real_escape_string($conn, $_POST['industry']);
    $funding_stage = mysqli_real_escape_string($conn, $_POST['funding_stage']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);

    $query_update_profile = "
        UPDATE Entrepreneurs SET
        startup_name = '$startup_name',
        industry = '$industry',
        funding_stage = '$funding_stage',
        description = '$description',
        location = '$location',
        website = '$website'
        WHERE entrepreneur_id = '$user_id'
    ";

    if (mysqli_query($conn, $query_update_profile)) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}

?>

<div class="container">
    <h1>Edit Profile</h1>
    <form method="POST">
        <div class="form-group">
            <label for="startup_name">Startup Name</label>
            <input type="text" id="startup_name" name="startup_name"
                value="<?php echo $entrepreneur['startup_name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="industry">Industry</label>
            <input type="text" id="industry" name="industry" value="<?php echo $entrepreneur['industry']; ?>" required>
        </div>
        <div class="form-group">
            <label for="funding_stage">Funding Stage</label>
            <select id="funding_stage" name="funding_stage" required>
                <option value="seed" <?php echo ($entrepreneur['funding_stage'] == 'seed') ? 'selected' : ''; ?>>Seed
                </option>
                <option value="series_a" <?php echo ($entrepreneur['funding_stage'] == 'series_a') ? 'selected' : ''; ?>>
                    Series A</option>
                <option value="series_b" <?php echo ($entrepreneur['funding_stage'] == 'series_b') ? 'selected' : ''; ?>>
                    Series B</option>
                <option value="series_c" <?php echo ($entrepreneur['funding_stage'] == 'series_c') ? 'selected' : ''; ?>>
                    Series C</option>
                <option value="exit" <?php echo ($entrepreneur['funding_stage'] == 'exit') ? 'selected' : ''; ?>>Exit
                </option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description"
                required><?php echo $entrepreneur['description']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" value="<?php echo $entrepreneur['location']; ?>">
        </div>
        <div class="form-group">
            <label for="website">Website</label>
            <input type="text" id="website" name="website" value="<?php echo $entrepreneur['website']; ?>">
        </div>
        <button type="submit">Save Changes</button>
    </form>
</div>