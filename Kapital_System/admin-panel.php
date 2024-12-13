<?php
// Include database connection
include 'db_connection.php';

// Start session
session_start();

// Check if the logged-in user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Fetch pending startups
$query = "SELECT s.startup_id, s.name, s.industry, s.description, u.name AS entrepreneur_name
          FROM Startups s
          JOIN Entrepreneurs e ON s.entrepreneur_id = e.entrepreneur_id
          JOIN Users u ON e.entrepreneur_id = u.user_id
          WHERE s.approval_status = 'pending'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .startup {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .startup:last-child {
            border-bottom: none;
        }
        .startup h2 {
            margin: 0;
            font-size: 18px;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .approve {
            background-color: #28a745;
            color: #fff;
        }
        .reject {
            background-color: #dc3545;
            color: #fff;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Admin Panel - Pending Startups</h1>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($startup = mysqli_fetch_assoc($result)): ?>
                <div class="startup">
                    <h2><?php echo htmlspecialchars($startup['name']); ?></h2>
                    <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                    <p><strong>Entrepreneur:</strong> <?php echo htmlspecialchars($startup['entrepreneur_name']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($startup['description'])); ?></p>
                    <form action="process-startup.php" method="post" style="display: inline;">
                        <input type="hidden" name="startup_id" value="<?php echo $startup['startup_id']; ?>">
                        <button type="submit" name="action" value="approve" class="btn approve">Approve</button>
                    </form>
                    <form action="process-startup.php" method="post" style="display: inline;">
                        <input type="hidden" name="startup_id" value="<?php echo $startup['startup_id']; ?>">
                        <button type="submit" name="action" value="reject" class="btn reject">Reject</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No pending startups to review.</p>
        <?php endif; ?>
    </div>
</body>
</html>
