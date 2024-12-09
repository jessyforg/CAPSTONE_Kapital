<?php
session_start();
include('navbar.php'); // Include the navbar
include('db_connection.php'); // Include database connection

// Check if `startup_id` is passed in the query string
if (!isset($_GET['startup_id'])) {
    die("Startup ID is not provided.");
}

$startup_id = $_GET['startup_id'];

// Fetch startup details from the database
$query_startup = "SELECT * FROM Startups WHERE startup_id = ?";
$stmt = $conn->prepare($query_startup);
$stmt->bind_param("i", $startup_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $startup = $result->fetch_assoc();
} else {
    die("Startup not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($startup['name']); ?> - Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
        }

        h1 {
            color: #f4f4f4;
        }

        .details {
            line-height: 1.8;
            margin-top: 20px;
        }

        .details strong {
            color: #ddd;
        }

        .details a {
            color: #D8A25E;
            text-decoration: none;
        }

        .details a:hover {
            text-decoration: underline;
        }

        button {
            background: #D8A25E;
            color: #fff;
            font-size: 16px;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            border: none;
            margin-top: 20px;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #D8A25E;
        }

        .error {
            color: #f44336;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($startup['name']); ?></h1>
        <div class="details">
            <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
            <p><strong>Funding Stage:</strong> <?php echo htmlspecialchars(ucwords($startup['funding_stage'])); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($startup['location']); ?></p>
            <p><strong>Website:</strong>
                <?php if (!empty($startup['website'])): ?>
                    <a href="<?php echo htmlspecialchars($startup['website']); ?>"
                        target="_blank"><?php echo htmlspecialchars($startup['website']); ?></a>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </p>
            <p><strong>Pitch Deck:</strong>
                <?php if (!empty($startup['pitch_deck_url'])): ?>
                    <a href="<?php echo htmlspecialchars($startup['pitch_deck_url']); ?>" target="_blank">View Pitch
                        Deck</a>
                <?php else: ?>
                    Not Provided
                <?php endif; ?>
            </p>
            <p><strong>Business Plan:</strong>
                <?php if (!empty($startup['business_plan_url'])): ?>
                    <a href="<?php echo htmlspecialchars($startup['business_plan_url']); ?>" target="_blank">View Business
                        Plan</a>
                <?php else: ?>
                    Not Provided
                <?php endif; ?>
            </p>
        </div>
        <button onclick="window.history.back();">Back to Startups</button>
    </div>
</body>

</html>