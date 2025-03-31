<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connection.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch all notifications, including read and unread, sorted by created_at
    $stmt_notifications = $conn->prepare("SELECT * FROM Notifications WHERE user_id = ? ORDER BY created_at DESC");
    $stmt_notifications->bind_param('i', $user_id);
    $stmt_notifications->execute();
    $result_notifications = $stmt_notifications->get_result();
    $notifications = $result_notifications->fetch_all(MYSQLI_ASSOC);
    $notification_count = count(array_filter($notifications, function($notification) {
        return $notification['status'] == 'unread';
    }));

    // Fetch messages where the user is either the sender or receiver
    $stmt_messages = $conn->prepare(
        "SELECT m.message_id, m.sender_id, m.receiver_id, m.content, m.status, u.name AS sender_name
        FROM Messages m
        JOIN Users u ON u.user_id = m.sender_id
        WHERE (m.sender_id = ? OR m.receiver_id = ?) AND (m.receiver_id = ? OR m.sender_id = ?)"
    );
    $stmt_messages->bind_param('iiii', $user_id, $user_id, $user_id, $user_id);
    $stmt_messages->execute();
    $result_messages = $stmt_messages->get_result();
    $messages = $result_messages->fetch_all(MYSQLI_ASSOC);

    // Get distinct senders who have sent unread messages to the user
    $stmt_unread_senders = $conn->prepare(
        "SELECT DISTINCT m.sender_id
        FROM Messages m
        WHERE (m.sender_id = ? OR m.receiver_id = ?) AND m.status = 'unread' AND m.receiver_id = ?"
    );
    $stmt_unread_senders->bind_param('iii', $user_id, $user_id, $user_id);
    $stmt_unread_senders->execute();
    $result_unread_senders = $stmt_unread_senders->get_result();
    $unread_sender_count = $result_unread_senders->num_rows;
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

        .logo-search-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        header .logo {
            font-size: 1.8em;
            font-weight: bold;
            color: #f3c000;
        }

        .search-container {
            position: relative;
            width: 300px;
        }

        .search-container input {
            width: 100%;
            padding: 8px 35px 8px 15px;
            border: 2px solid #f3c000;
            border-radius: 20px;
            background-color: rgba(243, 192, 0, 0.1);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }

        .search-container input:focus {
            outline: none;
            background-color: rgba(243, 192, 0, 0.15);
            box-shadow: 0 0 5px rgba(243, 192, 0, 0.3);
        }

        .search-container input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .search-container .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #f3c000;
            font-size: 0.9em;
            cursor: pointer;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background-color: #1e1e1e;
            border: 2px solid #f3c000;
            border-radius: 10px;
            margin-top: 5px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .search-results .result-item {
            padding: 10px 15px;
            border-bottom: 1px solid #333;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background-color 0.3s ease;
        }

        .search-results .result-item:last-child {
            border-bottom: none;
        }

        .search-results .result-item:hover {
            background-color: rgba(243, 192, 0, 0.1);
        }

        .search-results .result-item a {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            text-decoration: none;
            color: inherit;
        }

        .search-results .result-item .result-icon {
            color: #f3c000;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .search-results .result-item .result-content {
            flex: 1;
            min-width: 0;
        }

        .search-results .result-item .result-title {
            font-weight: 500;
            color: #fff;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-results .result-item .result-subtitle {
            font-size: 0.8em;
            color: rgba(255, 255, 255, 0.6);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-results .result-item .result-type {
            font-size: 0.8em;
            padding: 2px 8px;
            border-radius: 10px;
            background-color: rgba(243, 192, 0, 0.2);
            color: #f3c000;
            flex-shrink: 0;
            margin-left: auto;
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

        /* Add Taraki logo styling */
        .taraki-logo {
            height: 30px;
            margin-right: 15px;
            transition: transform 0.2s ease;
        }

        .taraki-logo:hover {
            transform: scale(1.1);
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

        /* Profile dropdown content */
        .profile-dropdown .dropdown-content {
            display: none;
            position: absolute;
            background-color: #1e1e1e;
            min-width: 160px;
            width: auto;
            max-width: 300px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            left: 0;
            top: 100%;
            word-wrap: break-word;
            white-space: normal;
        }

        .profile-dropdown .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #333;
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
            min-width: 160px;
            max-width: 300px;
            width: auto;
            max-height: 300px;
            overflow-y: auto;
            left: 0;
            top: 100%;
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

        /* Highlight unread messages */
        .message-item.unread {
            background-color: #2e2e2e;
            font-weight: bold;
        }

        .message-item.read {
            background-color: #1e1e1e;
            color: gray;
        }

        /* Highlight unread notifications */
        .notification-item.unread {
            background-color: #2e2e2e;
            font-weight: bold;
        }

        .notification-item.read {
            background-color: #1e1e1e;
            color: gray;
        }

        /* Handle positioning based on screen height to avoid overflow */
        .dropdown-container .dropdown-content {
            top: 100%;
            bottom: auto;
        }

        .dropdown-container .dropdown-content-upward {
            top: auto;
            bottom: 100%;
        }

        /* Adjustments for responsiveness */
        @media (max-height: 600px) {
            .dropdown-container .dropdown-content {
                top: auto;
                bottom: 100%;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logo-search-container">
            <div class="logo">Kapital</div>
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search users, startups, jobs..." aria-label="Search">
                <i class="fas fa-search search-icon"></i>
                <div class="search-results" id="searchResults"></div>
            </div>
        </div>
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
                <a href="https://taraki.vercel.app" target="_blank">
                    <img src="imgs/taraki logo.png" alt="Taraki Logo" class="taraki-logo">
                </a>
                <div class="dropdown-container">
                    <a class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="badge"><?php echo $notification_count; ?></span>
                    </a>
                    <div class="dropdown-content">
                        <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $notification): ?>
                                <a href="notification_redirect.php?notification_id=<?php echo $notification['notification_id']; ?>"
                                    class="notification-item <?php echo ($notification['status'] == 'unread') ? 'unread' : 'read'; ?>"
                                    data-notification-id="<?php echo $notification['notification_id']; ?>">
                                    <?php echo htmlspecialchars($notification['message']); ?>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a href="#">No new notifications</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="dropdown-container">
                    <a href="messages.php" class="icon-btn">
                        <i class="fas fa-envelope"></i>
                        <span class="badge"><?php echo $unread_sender_count; ?></span>
                    </a>
                    <div class="dropdown-content">
                        <?php if (!empty($messages)): ?>
                            <?php foreach ($messages as $message): ?>
                                <a href="messages.php?chat_with=<?php echo ($message['sender_id'] == $user_id) ? $message['receiver_id'] : $message['sender_id']; ?>"
                                    class="message-item <?php echo ($message['status'] == 'unread') ? 'unread' : 'read'; ?> "
                                    data-message-id="<?php echo $message['message_id']; ?>">
                                    <?php echo ($message['sender_id'] == $user_id ? 'To: ' : 'From: ') . htmlspecialchars($message['sender_name']) . " | " . htmlspecialchars(substr($message['content'], 0, 30)) . '...'; ?>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a href="#">No messages</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="profile-dropdown">
                    <button class="dropdown-btn">Profile</button>
                    <div class="dropdown-content">
                        <a href="profile.php">View Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="sign_in.php" class="cta-btn">Login</a>
                <a href="sign_up.php" class="cta-btn">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>

    <script>
        // Function to mark the message as read
        document.querySelectorAll('.message-item').forEach(function (item) {
            item.addEventListener('click', function () {
                const messageId = this.getAttribute('data-message-id');

                fetch('mark_message_read.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message_id: messageId }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.classList.remove('unread');
                            this.classList.add('read');
                        }
                    });
            });
        });

        // Function to mark the notification as read
        document.querySelectorAll('.notification-item').forEach(function (item) {
            item.addEventListener('click', function () {
                const notificationId = this.getAttribute('data-notification-id');

                fetch('mark_notification_read.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ notification_id: notificationId }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.classList.remove('unread');
                        this.classList.add('read');
                    }
                });
            });
        });

        // Function to set active class on the current page
        function setActiveLink() {
            const currentPage = window.location.pathname;
            const navLinks = document.querySelectorAll('nav ul li a');
            navLinks.forEach(link => {
                const linkPath = link.getAttribute('href');
                if (currentPage.includes(linkPath)) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        // Adjust dropdown positioning based on screen height
        function adjustDropdownPosition() {
            const dropdowns = document.querySelectorAll('.dropdown-container');
            dropdowns.forEach(function (dropdown) {
                const dropdownContent = dropdown.querySelector('.dropdown-content');
                const rect = dropdown.getBoundingClientRect();
                const windowHeight = window.innerHeight;

                // If the dropdown overflows the bottom of the window, show it above
                if (rect.bottom + dropdownContent.offsetHeight > windowHeight) {
                    dropdownContent.classList.add('dropdown-content-upward');
                } else {
                    dropdownContent.classList.remove('dropdown-content-upward');
                }
            });
        }

        // Call the function after the page loads or when resizing
        window.addEventListener('resize', adjustDropdownPosition);
        window.addEventListener('load', adjustDropdownPosition);
        setActiveLink(); // Set active class on page load

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                console.log('Searching for:', query); // Debug log
                
                fetch(`search.php?query=${encodeURIComponent(query)}`)
                    .then(response => {
                        console.log('Response status:', response.status); // Debug log
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Search results:', data); // Debug log
                        if (data.error) {
                            searchResults.innerHTML = `<div class="result-item"><div class="result-content">Error: ${data.error}</div></div>`;
                            searchResults.style.display = 'block';
                            return;
                        }
                        
                        if (Array.isArray(data) && data.length > 0) {
                            displaySearchResults(data);
                        } else {
                            searchResults.innerHTML = '<div class="result-item"><div class="result-content">No results found</div></div>';
                            searchResults.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error); // Debug log
                        searchResults.innerHTML = `<div class="result-item"><div class="result-content">Error: ${error.message}</div></div>`;
                        searchResults.style.display = 'block';
                    });
            }, 300);
        });

        function displaySearchResults(results) {
            searchResults.innerHTML = '';
            
            results.forEach(result => {
                const resultItem = document.createElement('div');
                resultItem.className = 'result-item';
                
                let icon, title, subtitle, link;
                
                switch(result.type) {
                    case 'user':
                        icon = 'fa-user';
                        title = result.name || 'Unknown User';
                        subtitle = result.role || 'User';
                        link = `profile.php?user_id=${result.user_id}`;
                        break;
                    case 'startup':
                        icon = 'fa-building';
                        title = result.name || 'Unknown Startup';
                        subtitle = result.industry || 'Various Industries';
                        link = `startup_detail.php?startup_id=${result.startup_id}`;
                        break;
                    case 'job':
                        icon = 'fa-briefcase';
                        title = result.title || 'Job Opening';
                        subtitle = result.company ? (result.location ? `${result.company} - ${result.location}` : result.company) : 'Company';
                        link = `job-details.php?job_id=${result.job_id}`;
                        break;
                }
                
                resultItem.innerHTML = `
                    <a href="${link}" style="text-decoration: none; color: inherit;">
                        <div class="result-icon">
                            <i class="fas ${icon}"></i>
                        </div>
                        <div class="result-content">
                            <div class="result-title">${title}</div>
                            <div class="result-subtitle">${subtitle}</div>
                        </div>
                        <div class="result-type">${result.type}</div>
                    </a>
                `;
                
                searchResults.appendChild(resultItem);
            });
            
            searchResults.style.display = 'block';
        }

        // Close search results when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchResults.contains(event.target) && event.target !== searchInput) {
                searchResults.style.display = 'none';
            }
        });
    </script>
</body>

</html>