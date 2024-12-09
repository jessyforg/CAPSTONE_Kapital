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

// Fetch entrepreneur's startups
$query_startups = "SELECT * FROM Startups WHERE entrepreneur_id = (SELECT entrepreneur_id FROM Entrepreneurs WHERE entrepreneur_id = '$user_id')";
$result_startups = mysqli_query($conn, $query_startups);
$startups = mysqli_fetch_all($result_startups, MYSQLI_ASSOC);

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile and Manage Startups</title>
    <!-- Include Poppins font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            /* Apply Poppins font */
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
        }

        h1,
        h2 {
            color: #f4f4f4;
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
        }

        button:hover {
            background: #D8A25E;
        }

        .startup-list {
            margin-top: 30px;
        }

        .startup-item {
            background: rgba(0, 0, 0, 0.3);
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .startup-item h3 {
            margin: 0 0 10px;
        }

        .startup-actions {
            margin-top: 10px;
        }

        .startup-actions a {
            color: #dcdcdc;
            text-decoration: none;
            margin-right: 10px;
            font-weight: bold;
        }

        .startup-actions a:hover {
            text-decoration: underline;
        }

        .success {
            color: #4caf50;
        }

        .error {
            color: #f44336;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <?php if (isset($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php elseif (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                    required>
            </div>
            <button type="submit" name="update_profile">Save Changes</button>
        </form>

        <h2>Change Password</h2>
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

        <div class="startup-list">
            <h2>Your Startups</h2>
            <?php if (!empty($startups)): ?>
                <?php foreach ($startups as $startup): ?>
                    <div class="startup-item">
                        <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                        <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>
                        <div class="startup-actions">
                            <a href="edit_startup.php?startup_id=<?php echo $startup['startup_id']; ?>">Edit</a>
                            <a href="delete_startup.php?startup_id=<?php echo $startup['startup_id']; ?>">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No startups found. <a href="create_startup.php">Create a new startup</a>.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>