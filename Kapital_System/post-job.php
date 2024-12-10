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
$query = "SELECT startup_id FROM Startups WHERE entrepreneur_id = '$user_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$startup_id = $row['startup_id']; // Use this startup_id in the job posting form

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the form
    $role = $_POST['role'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $location = $_POST['location'];
    $salary_range_min = $_POST['salary_range_min'];
    $salary_range_max = $_POST['salary_range_max'];

    // Insert the new job into the database
    $query = "INSERT INTO Jobs (startup_id, role, description, requirements, location, salary_range_min, salary_range_max)
              VALUES ('$startup_id', '$role', '$description', '$requirements', '$location', '$salary_range_min', '$salary_range_max')";

    if (mysqli_query($conn, $query)) {
        // Redirect to entrepreneurs.php after successfully posting the job
        header('Location: entrepreneurs.php'); 
        exit(); // Ensure no further code is executed
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
