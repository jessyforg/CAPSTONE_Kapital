<?php
session_start();
// Include necessary files
include('navbar.php');
include('db_connection.php'); // Assuming a separate file for database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User is not logged in. Please log in to create a startup.");
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve the form data
    $startup_name = mysqli_real_escape_string($conn, $_POST['startup_name']);
    $industry = mysqli_real_escape_string($conn, $_POST['industry']);
    $funding_stage = mysqli_real_escape_string($conn, $_POST['funding_stage']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $team_members = mysqli_real_escape_string($conn, $_POST['team_members']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    // Handle file uploads (pitch deck, business plan)
    $pitch_deck = '';
    $business_plan = '';

    if (isset($_FILES['pitch_deck']) && $_FILES['pitch_deck']['error'] == 0) {
        $pitch_deck = 'uploads/' . basename($_FILES['pitch_deck']['name']);
        move_uploaded_file($_FILES['pitch_deck']['tmp_name'], $pitch_deck);
    }

    if (isset($_FILES['business_plan']) && $_FILES['business_plan']['error'] == 0) {
        $business_plan = 'uploads/' . basename($_FILES['business_plan']['name']);
        move_uploaded_file($_FILES['business_plan']['tmp_name'], $business_plan);
    }

    // Insert the startup details into the database
    $user_id = $_SESSION['user_id']; // Assuming the user is logged in and their user_id is stored in the session
    $query = "INSERT INTO Startups (entrepreneur_id, name, industry, description, location, pitch_deck_url, business_plan_url) 
              VALUES ('$user_id', '$startup_name', '$industry', '$description', '$location', '$pitch_deck', '$business_plan')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "Startup created successfully!";
    } else {
        echo "Error creating startup: " . mysqli_error($conn);
    }
}
?>

<!-- Embedded CSS -->
<style>
    /* General Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f7fb;
        color: #333;
        line-height: 1.6;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 960px;
        margin: 50px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h1 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: #3b3f5c;
        text-align: center;
        font-weight: 600;
    }

    form {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    /* Form Elements */
    label {
        font-size: 1rem;
        color: #444;
        margin-bottom: 8px;
    }

    input[type="text"],
    input[type="file"],
    textarea,
    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
        background-color: #fafafa;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    textarea:focus,
    select:focus,
    input[type="file"]:focus {
        border-color: #0056b3;
        outline: none;
        background-color: #fff;
    }

    textarea {
        min-height: 120px;
    }

    button[type="submit"] {
        background-color: #0056b3;
        color: white;
        padding: 14px 20px;
        border: none;
        border-radius: 5px;
        font-size: 1.2rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
    }

    button[type="submit"]:hover {
        background-color: #003d7a;
    }

    button[type="submit"]:focus {
        outline: none;
    }

    /* Spacing and Layout */
    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
    }

    /* File Input */
    input[type="file"] {
        padding: 8px;
        background-color: #f0f0f0;
    }

    input[type="file"]:focus {
        background-color: #fff;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }

        h1 {
            font-size: 2rem;
        }

        form {
            grid-template-columns: 1fr;
        }

        button[type="submit"] {
            font-size: 1rem;
        }
    }
</style>

<!-- Entrepreneurs Page -->
<div class="container">
    <h1>Create Your Startup Profile</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="startup_name">Startup Name</label>
            <input type="text" id="startup_name" name="startup_name" required class="form-control">
        </div>

        <div class="form-group">
            <label for="industry">Industry</label>
            <input type="text" id="industry" name="industry" required class="form-control">
        </div>

        <div class="form-group">
            <label for="funding_stage">Funding Stage</label>
            <select id="funding_stage" name="funding_stage" required class="form-control">
                <option value="seed">Seed</option>
                <option value="series_a">Series A</option>
                <option value="series_b">Series B</option>
                <option value="series_c">Series C</option>
                <option value="exit">Exit</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Startup Description</label>
            <textarea id="description" name="description" required class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="team_members">Team Members</label>
            <textarea id="team_members" name="team_members" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" class="form-control">
        </div>

        <div class="form-group">
            <label for="pitch_deck">Pitch Deck</label>
            <input type="file" id="pitch_deck" name="pitch_deck" class="form-control">
        </div>

        <div class="form-group">
            <label for="business_plan">Business Plan</label>
            <input type="file" id="business_plan" name="business_plan" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>