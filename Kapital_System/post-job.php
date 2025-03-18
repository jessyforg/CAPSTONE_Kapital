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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #2C2F33;
            color: #f9f9f9;
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: #23272A;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: 1px solid #40444B;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 600;
            color: #7289DA;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
        }

        form label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #7289DA;
            font-size: 1rem;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 12px;
            background-color: #2C2F33;
            border: 1px solid #40444B;
            border-radius: 8px;
            color: #f9f9f9;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        form input:focus,
        form textarea:focus {
            outline: none;
            border-color: #7289DA;
            box-shadow: 0 0 0 2px rgba(114, 137, 218, 0.1);
        }

        form textarea {
            min-height: 120px;
            resize: vertical;
        }

        form input[type="number"] {
            -moz-appearance: textfield;
        }

        form input[type="number"]::-webkit-outer-spin-button,
        form input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .salary-range {
            display: flex;
            gap: 20px;
        }

        .salary-range .form-group {
            flex: 1;
        }

        form button {
            background-color: #7289DA;
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
        }

        form button:hover {
            background-color: #5b6eae;
            transform: translateY(-2px);
        }

        form button i {
            font-size: 1.1rem;
        }

        .error-message {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 20px;
            }

            .section-title {
                font-size: 1.6rem;
            }

            .salary-range {
                flex-direction: column;
                gap: 10px;
            }

            form button {
                padding: 12px 20px;
            }
        }
    </style>
</head>

<body>
    <?php include('navbar.php'); ?>
    
    <div class="container">
        <h1 class="section-title">Post a Job</h1>

        <form method="POST">
            <div class="form-group">
                <label for="role"><i class="fas fa-briefcase"></i> Job Role</label>
                <input type="text" id="role" name="role" placeholder="Enter the job role" required>
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-align-left"></i> Job Description</label>
                <textarea id="description" name="description" placeholder="Describe the job responsibilities and expectations" required></textarea>
            </div>

            <div class="form-group">
                <label for="requirements"><i class="fas fa-list-ul"></i> Job Requirements</label>
                <textarea id="requirements" name="requirements" placeholder="List the required skills, experience, and qualifications" required></textarea>
            </div>

            <div class="form-group">
                <label for="location"><i class="fas fa-map-marker-alt"></i> Location</label>
                <input type="text" id="location" name="location" placeholder="Enter job location" required>
            </div>

            <div class="salary-range">
                <div class="form-group">
                    <label for="salary_range_min"><i class="fas fa-dollar-sign"></i> Minimum Salary</label>
                    <input type="number" id="salary_range_min" name="salary_range_min" placeholder="Enter minimum salary" required>
                </div>

                <div class="form-group">
                    <label for="salary_range_max"><i class="fas fa-dollar-sign"></i> Maximum Salary</label>
                    <input type="number" id="salary_range_max" name="salary_range_max" placeholder="Enter maximum salary" required>
                </div>
            </div>

            <button type="submit">
                <i class="fas fa-paper-plane"></i> Post Job
            </button>
        </form>
    </div>
</body>

</html>
