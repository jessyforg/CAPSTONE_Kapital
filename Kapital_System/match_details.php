<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once 'db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch match details using the provided match ID
if (isset($_GET['match_id'])) {
    $match_id = intval($_GET['match_id']);

    $stmt = $conn->prepare("
        SELECT m.match_id, m.match_type, m.details, m.created_at, 
               u.full_name AS matched_user, u.email AS matched_email
        FROM Matches m
        JOIN Users u ON m.matched_user_id = u.user_id
        WHERE m.match_id = ? AND (m.user_id = ? OR m.matched_user_id = ?)
    ");
    $stmt->bind_param('iii', $match_id, $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $match = $result->fetch_assoc();
    } else {
        $error = "Match not found or you do not have permission to view this match.";
    }
} else {
    $error = "No match ID provided.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #1e1e1e;
            color: #fff;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #000;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #f3c000;
            font-size: 2em;
        }

        .details {
            margin: 20px 0;
            line-height: 1.6;
        }

        .details h2 {
            color: #f3c000;
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .details p {
            margin: 10px 0;
        }

        .cta-buttons {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .cta-buttons a {
            background-color: #f3c000;
            color: #000;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .cta-buttons a:hover {
            background-color: #ffab00;
            transform: scale(1.05);
        }

        .cta-buttons a:active {
            transform: scale(0.95);
        }

        .error {
            color: #ff4c4c;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php else: ?>
            <div class="header">
                <h1>Match Details</h1>
            </div>
            <div class="details">
                <h2>Matched User:</h2>
                <p><?php echo htmlspecialchars($match['matched_user']); ?></p>

                <h2>Match Type:</h2>
                <p><?php echo htmlspecialchars(ucfirst($match['match_type'])); ?></p>

                <h2>Details:</h2>
                <p><?php echo htmlspecialchars($match['details']); ?></p>

                <h2>Contact Email:</h2>
                <p><?php echo htmlspecialchars($match['matched_email']); ?></p>

                <h2>Created At:</h2>
                <p><?php echo htmlspecialchars($match['created_at']); ?></p>
            </div>
            <div class="cta-buttons">
                <a href="messages.php?recipient_id=<?php echo $match['matched_user_id']; ?>">Send Message</a>
                <a href="dashboard.php">Back to Dashboard</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>