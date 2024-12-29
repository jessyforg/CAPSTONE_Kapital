<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection file
require_once 'db_connection.php';

// Ensure the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch unread notifications for the user
    $stmt_notifications = $conn->prepare("SELECT * FROM Notifications WHERE user_id = ? AND status = 'unread'");
    $stmt_notifications->bind_param('i', $user_id);
    $stmt_notifications->execute();
    $result_notifications = $stmt_notifications->get_result();
    $notifications = $result_notifications->fetch_all(MYSQLI_ASSOC);

    // Fetch the total count of unread notifications
    $notification_count = count($notifications);

    // Fetch the user's messages (sent and received)
    $stmt_messages = $conn->prepare("SELECT * FROM Messages WHERE sender_id = ? OR receiver_id = ?");
    $stmt_messages->bind_param('ii', $user_id, $user_id);
    $stmt_messages->execute();
    $result_messages = $stmt_messages->get_result();
    $messages = $result_messages->fetch_all(MYSQLI_ASSOC);

    // Fetch the total count of unread messages
    $unread_messages = array_filter($messages, fn($msg) => $msg['status'] == 'unread');
    $message_count = count($unread_messages);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <style>
        /* General Reset */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #1e1e1e;
            color: #fff;
        }

        /* Header and Navbar */
        header {
            background-color: #000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        header .logo {
            font-size: 1.8em;
            font-weight: bold;
            color: #f3c000;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            font-size: 1em;
            font-weight: 500;
            border-radius: 4px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        nav ul li a.active,
        nav ul li a:hover {
            background-color: #f3c000;
            color: #000;
            border-radius: 5px;
        }

        /* Notifications and Messaging */
        .icon-btn {
            position: relative;
            margin-left: 20px;
            cursor: pointer;
            color: #fff;
            text-decoration: none;
            font-size: 1.5em;
        }

        .icon-btn .badge {
            position: absolute;
            top: -5px;
            right: -10px;
            background: #f3c000;
            color: #000;
            border-radius: 50%;
            padding: 3px 6px;
            font-size: 0.7em;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transform: scale(0.85);
        }

        .cta-buttons {
            display: flex;
            align-items: center;
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-btn,
        .cta-buttons a.cta-btn {
            background: linear-gradient(90deg, #f3c000, #ffab00);
            color: #000;
            font-weight: 600;
            padding: 10px 20px;
            margin-left: 10px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .dropdown-btn:hover,
        .cta-buttons a.cta-btn:hover {
            background: linear-gradient(90deg, #ffab00, #f3c000);
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .dropdown-btn:active,
        .cta-buttons a.cta-btn:active {
            transform: scale(0.95);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .profile-dropdown .dropdown-content {
            display: none;
            position: absolute;
            background-color: #1e1e1e;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
        }

        .profile-dropdown .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background 0.3s;
        }

        .profile-dropdown .dropdown-content a:hover {
            background-color: #f3c000;
            color: black;
        }

        .profile-dropdown:hover .dropdown-content {
            display: block;
        }

        /* Dropdown content for notifications and messages */
        .dropdown-container {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #1e1e1e;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            width: 300px;
            max-height: 300px;
            overflow-y: auto;
        }

        .dropdown-content a {
            color: white;
            padding: 10px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #333;
        }

        .dropdown-content a:hover {
            background-color: #f3c000;
            color: black;
        }

        .dropdown-container:hover .dropdown-content {
            display: block;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">Kapital</div>
        <nav>
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'entrepreneur'): ?>
                    <li><a href="entrepreneurs.php">For Entrepreneurs</a></li>
                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'investor'): ?>
                    <li><a href="investors.php">For Investors</a></li>
                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'job_seeker'): ?>
                    <li><a href="job-seekers.php">For Job Seekers</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin-panel.php">Admin Panel</a></li>
                <?php endif; ?>

                <li><a href="about-us.php">About Us</a></li>
            </ul>
        </nav>
        <div class="cta-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Notifications Dropdown -->
                <div class="dropdown-container">
                    <a class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="badge"><?php echo $notification_count; ?></span>
                    </a>
                    <div class="dropdown-content">
                        <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $notification): ?>
                                <a href="notification_redirect.php?notification_id=<?php echo $notification['notification_id']; ?>">
                                    <?php echo htmlspecialchars($notification['message']); ?>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a href="#">No new notifications</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Messages Dropdown -->
                <div class="dropdown-container">
                    <a href="messages.php" class="icon-btn">
                        <i class="fas fa-envelope"></i>
                        <span class="badge"><?php echo $message_count; ?></span>
                    </a>
                    <div class="dropdown-content">
                        <?php if (!empty($messages)): ?>
                            <?php foreach ($messages as $message): ?>
                                <a href="#">
                                    <?php echo htmlspecialchars(substr($message['content'], 0, 30)) . '...'; ?>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a href="#">No new messages</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="profile-dropdown">
                    <button class="dropdown-btn">Profile</button>
                    <div class="dropdown-content">
                        <a href="profile.php">Edit Profile</a>
                        <a href="settings.php">Settings</a>
                        <a href="logout.php">Log Out</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="sign_in.php" class="cta-btn">Sign In</a>
                <a href="sign_up.php" class="cta-btn">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>
</body>

</html>