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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #2C2F33;
            color: #f9f9f9;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #23272A;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            color: #7289DA;
            margin-bottom: 20px;
        }
        .startup {
            border-bottom: 1px solid #444;
            padding: 15px 0;
        }
        .startup:last-child {
            border-bottom: none;
        }
        .startup h2 {
            margin: 0;
            font-size: 20px;
            color: #FFB74D;
        }
        .startup p {
            margin: 5px 0;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            margin: 5px 2px 0 0;
            transition: all 0.3s ease;
        }
        .approve {
            background-color: #4CAF50;
            color: #fff;
        }
        .reject {
            background-color: #F44336;
            color: #fff;
        }
        .add-comment {
            background-color: #FF9800;
            color: #fff;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .comment-box {
            display: none;
            margin-top: 10px;
        }
        p {
            color: #ddd;
        }
        a {
            color: #7289DA;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function toggleCommentBox(startupId) {
            const commentBox = document.getElementById(`comment-box-${startupId}`);
            if (commentBox.style.display === 'none' || commentBox.style.display === '') {
                commentBox.style.display = 'block';
            } else {
                commentBox.style.display = 'none';
            }
        }
    </script>
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
                    <button class="btn add-comment" onclick="toggleCommentBox(<?php echo $startup['startup_id']; ?>)">Add Comment</button>
                    <div class="comment-box" id="comment-box-<?php echo $startup['startup_id']; ?>">
                        <form action="process-comment.php" method="post">
                            <input type="hidden" name="startup_id" value="<?php echo $startup['startup_id']; ?>">
                            <textarea name="approval_comment" rows="4" cols="50" placeholder="Enter your comment here..." required></textarea><br>
                            <button type="submit" class="btn approve">Submit Comment</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No pending startups to review.</p>
        <?php endif; ?>
    </div>
</body>
</html>
