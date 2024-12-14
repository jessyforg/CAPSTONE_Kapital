<?php
// Include the database connection file
include 'db_connection.php';

// Start the session
session_start();

// Check if the user is an entrepreneur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'entrepreneur') {
    header('Location: index.php'); // Redirect if not an entrepreneur
    exit;
}

// Fetch the startup_id of the logged-in entrepreneur
$user_id = $_SESSION['user_id'];
$query = "SELECT startup_id FROM Startups WHERE entrepreneur_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $startup_id = $row['startup_id'];
} else {
    echo "Error: Startup not found for this entrepreneur.";
    exit;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and trim form inputs
    $role = trim($_POST['role']);
    $description = trim($_POST['description']);
    $requirements = trim($_POST['requirements']);
    $location = trim($_POST['location']);
    $salary_range_min = (float)$_POST['salary_range_min'];
    $salary_range_max = (float)$_POST['salary_range_max'];

    // Validate salary range
    if ($salary_range_min > $salary_range_max) {
        echo "Error: Minimum salary cannot exceed maximum salary.";
        exit;
    }

    // Insert the new job into the database
    $query = "INSERT INTO Jobs (startup_id, role, description, requirements, location, salary_range_min, salary_range_max) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "issssdd", $startup_id, $role, $description, $requirements, $location, $salary_range_min, $salary_range_max);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: entrepreneurs.php'); // Redirect after posting the job
        exit;
    } else {
        echo "Error posting job: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.8em;
            font-weight: 600;
            color: #000;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1em;
        }

        form button {
            background-color: #f3c000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            margin-top: 15px;
        }

        form button:hover {
            background-color: #ffab00;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="section-title">Post a Job</h1>

        <form method="POST">
            <label for="role">Job Role:</label>
            <input type="text" name="role" required><br>

            <label for="description">Job Description:</label>
            <textarea name="description" required></textarea><br>

            <label for="requirements">Job Requirements:</label>
            <textarea name="requirements" required></textarea><br>

            <label for="location">Location:</label>
            <input type="text" name="location" required><br>

            <label for="salary_range_min">Salary Range Min:</label>
            <input type="number" name="salary_range_min" required><br>

            <label for="salary_range_max">Salary Range Max:</label>
            <input type="number" name="salary_range_max" required><br>

            <button type="submit">Post Job</button>
        </form>
    </div>
</body>

</html>
