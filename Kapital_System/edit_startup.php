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

// Get the startup ID from the query string
if (isset($_GET['startup_id'])) {
    $startup_id = $_GET['startup_id'];

    // Fetch the startup details from the database
    $query_startup = "SELECT * FROM Startups WHERE startup_id = '$startup_id' AND entrepreneur_id = (SELECT entrepreneur_id FROM Entrepreneurs WHERE entrepreneur_id = '$user_id')";
    $result_startup = mysqli_query($conn, $query_startup);

    if ($result_startup && mysqli_num_rows($result_startup) > 0) {
        $startup = mysqli_fetch_assoc($result_startup);
    } else {
        die("Startup not found or you don't have permission to edit this startup.");
    }
} else {
    die("No startup ID provided.");
}

// Handle startup update
if (isset($_POST['update_startup'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $industry = mysqli_real_escape_string($conn, $_POST['industry']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query_update = "UPDATE Startups SET name = '$name', industry = '$industry', description = '$description' WHERE startup_id = '$startup_id'";
    if (mysqli_query($conn, $query_update)) {
        $success_message = "Startup updated successfully!";
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(45deg, #343131, #808080);
            color: #fff;
            margin: 0;
            padding: 0;
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
        textarea {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            margin-bottom: 10px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        input:focus,
        textarea:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.3);
        }

        button {
            background: #D8A25E;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
        }

        button:hover {
            background: #D8A25E;
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
        <h1>Edit Startup</h1>
        <?php if (isset($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php elseif (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

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
                <label for="description">Description</label>
                <textarea id="description" name="description"
                    required><?php echo htmlspecialchars($startup['description']); ?></textarea>
            </div>
            <button type="submit" name="update_startup">Save Changes</button>
        </form>
    </div>
</body>

</html>