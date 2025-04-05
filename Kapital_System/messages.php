<?php
session_start();
include('db_connection.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

include('navbar.php');

$user_id = $_SESSION['user_id'];

// Handle message request approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['sender_id'])) {
    $sender_id = (int)$_POST['sender_id'];
    $action = $_POST['action'];
    
    if ($action === 'approve' || $action === 'reject') {
        $status = $action === 'approve' ? 'approved' : 'rejected';
        
        // Update the conversation request status
        $update_query = "UPDATE Conversation_Requests 
                        SET status = ? 
                        WHERE sender_id = ? AND receiver_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('sii', $status, $sender_id, $user_id);
        
        if ($update_stmt->execute()) {
            // Also update the message status
            $update_message_query = "UPDATE Messages 
                                   SET request_status = ? 
                                   WHERE sender_id = ? AND receiver_id = ? 
                                   AND is_intro_message = TRUE";
            $update_message_stmt = $conn->prepare($update_message_query);
            $update_message_stmt->bind_param('sii', $status, $sender_id, $user_id);
            $update_message_stmt->execute();
            
            $_SESSION['success'] = $action === 'approve' ? 
                'Message request accepted.' : 
                'Message request declined.';
        } else {
            $_SESSION['error'] = 'Failed to process the request. Please try again.';
        }
        
        header("Location: messages.php");
        exit();
    }
}

// Modify the message sending handler to check for intro messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    $receiver_id = $_POST['receiver_id'] ?? null;
    
    if ($receiver_id && !empty($message)) {
        try {
            $conn->begin_transaction();
            
            // Check if there are any existing approved messages between these users
            $check_query = "SELECT COUNT(*) as count FROM Messages 
                          WHERE ((sender_id = ? AND receiver_id = ?) 
                          OR (sender_id = ? AND receiver_id = ?)) 
                          AND request_status = 'approved'";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("iiii", $_SESSION['user_id'], $receiver_id, $receiver_id, $_SESSION['user_id']);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['count'] > 0) {
                // If there are approved messages, send directly
                $insert_query = "INSERT INTO Messages (sender_id, receiver_id, content, request_status) 
                               VALUES (?, ?, ?, 'approved')";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bind_param("iis", $_SESSION['user_id'], $receiver_id, $message);
                $insert_stmt->execute();
                $_SESSION['success'] = "Message sent successfully.";
            } else {
                // Check for any existing requests
                $request_query = "SELECT * FROM Conversation_Requests 
                                WHERE ((sender_id = ? AND receiver_id = ?) 
                                OR (sender_id = ? AND receiver_id = ?))";
                $request_stmt = $conn->prepare($request_query);
                $request_stmt->bind_param("iiii", $_SESSION['user_id'], $receiver_id, $receiver_id, $_SESSION['user_id']);
                $request_stmt->execute();
                $request_result = $request_stmt->get_result();
                
                if ($request_result->num_rows > 0) {
                    $request = $request_result->fetch_assoc();
                    if ($request['status'] === 'pending') {
                        $_SESSION['error'] = "A message request is already pending.";
                    } else if ($request['status'] === 'rejected') {
                        // Delete old rejected request and create new one
                        $delete_query = "DELETE FROM Conversation_Requests WHERE request_id = ?";
                        $delete_stmt = $conn->prepare($delete_query);
                        $delete_stmt->bind_param("i", $request['request_id']);
                        $delete_stmt->execute();
                        
                        // Create new request and intro message
                        $insert_request = "INSERT INTO Conversation_Requests (sender_id, receiver_id) VALUES (?, ?)";
                        $insert_request_stmt = $conn->prepare($insert_request);
                        $insert_request_stmt->bind_param("ii", $_SESSION['user_id'], $receiver_id);
                        $insert_request_stmt->execute();
                        
                        // Insert the intro message directly without relying on trigger
                        $insert_message = "INSERT INTO Messages (sender_id, receiver_id, content, is_intro_message, request_status) 
                                         VALUES (?, ?, ?, TRUE, 'pending')";
                        $insert_message_stmt = $conn->prepare($insert_message);
                        $insert_message_stmt->bind_param("iis", $_SESSION['user_id'], $receiver_id, $message);
                        $insert_message_stmt->execute();
                        
                        $_SESSION['success'] = "Message request sent successfully.";
                    }
                } else {
                    // Create new request and intro message
                    $insert_request = "INSERT INTO Conversation_Requests (sender_id, receiver_id) VALUES (?, ?)";
                    $insert_request_stmt = $conn->prepare($insert_request);
                    $insert_request_stmt->bind_param("ii", $_SESSION['user_id'], $receiver_id);
                    $insert_request_stmt->execute();
                    
                    // Insert the intro message directly without relying on trigger
                    $insert_message = "INSERT INTO Messages (sender_id, receiver_id, content, is_intro_message, request_status) 
                                     VALUES (?, ?, ?, TRUE, 'pending')";
                    $insert_message_stmt = $conn->prepare($insert_message);
                    $insert_message_stmt->bind_param("iis", $_SESSION['user_id'], $receiver_id, $message);
                    $insert_message_stmt->execute();
                    
                    $_SESSION['success'] = "Message request sent successfully.";
                }
            }
            
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error'] = "Failed to send message. Error: " . $e->getMessage();
        }
    }
}

// Add query to fetch pending message requests
$pending_requests_query = "
    SELECT 
        cr.*,
        u.name as sender_name,
        u.role as sender_role,
        m.content as intro_message,
        m.sent_at
    FROM Conversation_Requests cr
    JOIN Users u ON cr.sender_id = u.user_id
    JOIN Messages m ON cr.sender_id = m.sender_id 
        AND cr.receiver_id = m.receiver_id 
        AND m.is_intro_message = TRUE
    WHERE cr.receiver_id = ? 
    AND cr.status = 'pending'
    ORDER BY cr.created_at DESC";
$pending_stmt = $conn->prepare($pending_requests_query);
$pending_stmt->bind_param('i', $user_id);
$pending_stmt->execute();
$pending_requests = $pending_stmt->get_result();

// Fetch conversations
$conversations_query = "
    SELECT DISTINCT 
        Users.user_id, 
        Users.name, 
        Users.role
    FROM Messages
    JOIN Users ON (Messages.sender_id = Users.user_id OR Messages.receiver_id = Users.user_id)
    WHERE (Messages.sender_id = ? OR Messages.receiver_id = ?) AND Users.user_id != ?
    ORDER BY (SELECT MAX(Messages.sent_at) FROM Messages 
              WHERE (Messages.sender_id = Users.user_id AND Messages.receiver_id = ?) 
              OR (Messages.sender_id = ? AND Messages.receiver_id = Users.user_id)) DESC";
$conversations_stmt = $conn->prepare($conversations_query);
$conversations_stmt->bind_param('iiiii', $user_id, $user_id, $user_id, $user_id, $user_id);
$conversations_stmt->execute();
$conversations_result = $conversations_stmt->get_result();

// Check if recipient_id is provided in URL parameters
if (isset($_GET['recipient_id']) && !empty($_GET['recipient_id'])) {
    $chat_with = (int)$_GET['recipient_id'];
    
    // Check if the user exists
    $check_user = $conn->prepare("SELECT user_id FROM Users WHERE user_id = ?");
    $check_user->bind_param("i", $chat_with);
    $check_user->execute();
    $user_exists = $check_user->get_result()->num_rows > 0;
    
    if ($user_exists) {
        // If valid recipient, use it as chat_with
        $_GET['chat_with'] = $chat_with;
    }
}

// Fetch chat messages and user details for the selected conversation
$chat_with = isset($_GET['chat_with']) ? (int)$_GET['chat_with'] : null;
$messages = [];
$chat_user = null;  // To store the chat user's details (name, role)
if ($chat_with) {
    $messages_query = "
        SELECT m.*, cr.status as request_status
        FROM Messages m
        LEFT JOIN Conversation_Requests cr ON 
            (m.sender_id = cr.sender_id AND m.receiver_id = cr.receiver_id)
        WHERE (m.sender_id = ? AND m.receiver_id = ?)
        OR (m.sender_id = ? AND m.receiver_id = ?)
        ORDER BY m.sent_at ASC";
    $messages_stmt = $conn->prepare($messages_query);
    $messages_stmt->bind_param('iiii', $user_id, $chat_with, $chat_with, $user_id);
    $messages_stmt->execute();
    $messages_result = $messages_stmt->get_result();

    while ($row = $messages_result->fetch_assoc()) {
        $messages[] = $row;
    }

    // Fetch user details (name and role) of the person you're chatting with
    $user_query = "SELECT name, role FROM Users WHERE user_id = ?";
    $user_stmt = $conn->prepare($user_query);
    $user_stmt->bind_param('i', $chat_with);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $chat_user = $user_result->fetch_assoc();

    // Add this section to show the approval buttons if there's a pending request
    $check_pending_query = "
        SELECT status 
        FROM Conversation_Requests 
        WHERE sender_id = ? AND receiver_id = ? AND status = 'pending'";
    $check_pending_stmt = $conn->prepare($check_pending_query);
    $check_pending_stmt->bind_param('ii', $chat_with, $user_id);
    $check_pending_stmt->execute();
    $pending_result = $check_pending_stmt->get_result();
    $has_pending_request = $pending_result->num_rows > 0;
}

// Handle user search
$search_query = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : null;
$search_results = [];
if ($search_query) {
    $user_search_query = "SELECT user_id, name, role FROM Users WHERE name LIKE ? AND user_id != ?";
    $search_stmt = $conn->prepare($user_search_query);
    $like_query = '%' . $search_query . '%';
    $search_stmt->bind_param('si', $like_query, $user_id);
    $search_stmt->execute();
    $search_results = $search_stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Remove navbar search styling override since it's not needed */
        body {
            background-color: #2C2F33;
            min-height: 100vh;
        }

        .container {
            display: flex;
            height: calc(100vh - 110px); /* 70px navbar + 40px padding */
            margin: 20px auto;
            padding: 20px;
            max-width: 1400px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            background-color: #23272A;
            border: 1px solid #40444B;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 0;
                height: calc(100vh - 90px); /* 70px navbar + 20px margin */
                border-radius: 0;
                border: none;
            }
        }

        /* Sidebar Styles */
        .sidebar {
            width: 350px;
            background-color: #2C2F33;
            border-right: 1px solid #40444B;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .search-container {
            padding: 15px;
            width: 100%;
            box-sizing: border-box;
        }

        .search-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }

        .search-input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            border: 1px solid #40444B;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #2C2F33;
            color: #FFFFFF;
        }

        .search-input:focus {
            outline: none;
            border-color: #7289DA;
            box-shadow: 0 0 0 2px rgba(114, 137, 218, 0.1);
        }

        .search-input::placeholder {
            color: #72767D;
        }

        .search-button {
            width: 100%;
            padding: 12px;
            background-color: #7289DA;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .search-button:hover {
            background-color: #5b6eae;
            transform: translateY(-2px);
        }

        /* Conversation List Styles */
        .conversations-container {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        .section-title {
            padding: 15px;
            color: #B9BBBE;
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .conversation, .search-result {
            padding: 15px;
            margin: 5px 0;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #DCDDDE;
        }

        .conversation:hover, .search-result:hover {
            background-color: #40444B;
        }

        .conversation.active {
            background-color: #40444B;
            border-left: 3px solid #7289DA;
        }

        .conversation i, .search-result i {
            color: #7289DA;
        }

        /* Chat Area Styles */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #23272A;
        }

        .conversation-header {
            padding: 20px;
            background-color: #2C2F33;
            border-bottom: 1px solid #40444B;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #FFFFFF;
        }

        .conversation-header i {
            color: #7289DA;
        }

        .messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background-color: #23272A;
        }

        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 8px;
            position: relative;
            font-size: 14px;
            line-height: 1.4;
        }

        .message.sent {
            background-color: #7289DA;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }

        .message.received {
            background-color: #40444B;
            color: #FFFFFF;
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }

        .message-input {
            padding: 20px;
            background-color: #2C2F33;
            border-top: 1px solid #40444B;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .message-input textarea {
            flex: 1;
            padding: 12px;
            border: 1px solid #40444B;
            border-radius: 6px;
            resize: none;
            font-size: 14px;
            font-family: inherit;
            height: 45px;
            transition: all 0.3s ease;
            background-color: #23272A;
            color: #FFFFFF;
        }

        .message-input textarea:focus {
            outline: none;
            border-color: #7289DA;
            box-shadow: 0 0 0 2px rgba(114, 137, 218, 0.1);
        }

        .message-input textarea::placeholder {
            color: #72767D;
        }

        .message-input button {
            padding: 12px 24px;
            background-color: #7289DA;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .message-input button:hover {
            background-color: #5b6eae;
            transform: translateY(-2px);
        }

        .no-chat-selected {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #B9BBBE;
            text-align: center;
            padding: 20px;
        }

        .no-chat-selected i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #7289DA;
        }

        .no-results {
            color: #B9BBBE;
            text-align: center;
            padding: 20px;
            font-style: italic;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #2C2F33;
        }

        ::-webkit-scrollbar-thumb {
            background: #40444B;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #7289DA;
        }

        .requests-section {
            margin-bottom: 20px;
            background-color: #2F3136;
            border-radius: 8px;
            overflow: hidden;
        }

        .requests-header {
            padding: 15px;
            background-color: #36393F;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.2s ease;
        }

        .requests-header:hover {
            background-color: #40444B;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-content i {
            color: #7289DA;
            font-size: 1.2em;
        }

        .header-content h3 {
            color: #FFFFFF;
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        #toggleIcon {
            color: #B9BBBE;
            transition: transform 0.3s ease;
        }

        .requests-container {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .requests-container.show {
            max-height: 500px;
            overflow-y: auto;
        }

        .request-card {
            padding: 15px;
            border-bottom: 1px solid #40444B;
            transition: background-color 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .request-content {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info i {
            color: #7289DA;
            font-size: 24px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            color: #FFFFFF;
            font-weight: 500;
        }

        .user-role {
            color: #B9BBBE;
            font-size: 12px;
        }

        .request-actions {
            display: flex;
            gap: 8px;
            margin-left: auto;
        }

        .btn-approve, .btn-reject {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-approve {
            background-color: #43B581;
            color: white;
        }

        .btn-approve:hover {
            background-color: #3CA374;
        }

        .btn-reject {
            background-color: #F04747;
            color: white;
        }

        .btn-reject:hover {
            background-color: #D84040;
        }

        /* Scrollbar styling */
        .requests-container::-webkit-scrollbar {
            width: 8px;
        }

        .requests-container::-webkit-scrollbar-track {
            background: #2F3136;
        }

        .requests-container::-webkit-scrollbar-thumb {
            background: #202225;
            border-radius: 4px;
        }

        .requests-container::-webkit-scrollbar-thumb:hover {
            background: #40444B;
        }

        .pending-request-banner {
            background-color: #36393F;
            padding: 15px;
            border-bottom: 1px solid #40444B;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .banner-text {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #FFFFFF;
        }

        .banner-text i {
            color: #7289DA;
        }

        .banner-actions {
            display: flex;
            gap: 10px;
        }

        .banner-actions .request-form {
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="search-container">
                <form method="GET" action="messages.php" class="search-form">
                    <input type="text" name="search" placeholder="Search users..." class="search-input" 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="search-button">Search</button>
                </form>
            </div>

            <div class="conversations-container">
                <?php if (isset($_GET['search']) && $_GET['search']): ?>
                    <h5 class="section-title">Search Results</h5>
                    <?php if ($search_results && $search_results->num_rows > 0): ?>
                        <?php while ($user = $search_results->fetch_assoc()): ?>
                            <div class="search-result" onclick="window.location.href='messages.php?chat_with=<?php echo $user['user_id']; ?>'">
                                <i class="fas fa-user"></i>
                                <?php echo htmlspecialchars($user['name']) . " (" . ucfirst($user['role']) . ")"; ?>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="no-results">No users found</p>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message">
                        <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success-message">
                        <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if ($pending_requests->num_rows > 0): ?>
                    <div class="requests-section">
                        <div class="requests-header" onclick="toggleRequests()">
                            <div class="header-content">
                                <i class="fas fa-envelope-open-text"></i>
                                <h3>Message Requests (<?php echo $pending_requests->num_rows; ?>)</h3>
                            </div>
                            <i class="fas fa-chevron-down" id="toggleIcon"></i>
                        </div>
                        <div class="requests-container" id="requestsContainer">
                            <?php while ($request = $pending_requests->fetch_assoc()): ?>
                                <div class="request-card">
                                    <div class="request-content" onclick="window.location.href='messages.php?chat_with=<?php echo $request['sender_id']; ?>'">
                                        <div class="user-info">
                                            <i class="fas fa-user-circle"></i>
                                            <div class="user-details">
                                                <span class="user-name"><?php echo htmlspecialchars($request['sender_name']); ?></span>
                                                <span class="user-role"><?php echo ucfirst($request['sender_role']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <form method="POST" class="request-actions">
                                        <input type="hidden" name="sender_id" value="<?php echo $request['sender_id']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn-approve">
                                            <i class="fas fa-check"></i> Accept
                                        </button>
                                        <button type="submit" name="action" value="reject" class="btn-reject">
                                            <i class="fas fa-times"></i> Decline
                                        </button>
                                    </form>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <h5 class="section-title">Conversations</h5>
                <?php while ($conversation = $conversations_result->fetch_assoc()): ?>
                    <div class="conversation <?php echo ($chat_with == $conversation['user_id']) ? 'active' : ''; ?>" 
                         onclick="window.location.href='messages.php?chat_with=<?php echo $conversation['user_id']; ?>'">
                        <i class="fas fa-user"></i>
                        <?php echo htmlspecialchars($conversation['name']) . " (" . ucfirst($conversation['role']) . ")"; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <?php if ($chat_with && $chat_user): ?>
                <div class="conversation-header">
                    <i class="fas fa-user"></i>
                    <?php echo htmlspecialchars($chat_user['name']) . " (" . ucfirst($chat_user['role']) . ")"; ?>
                </div>

                <?php if ($has_pending_request): ?>
                    <div class="pending-request-banner">
                        <div class="banner-text">
                            <i class="fas fa-envelope"></i>
                            <span>Message Request</span>
                        </div>
                        <div class="banner-actions">
                            <form method="POST" class="request-form">
                                <input type="hidden" name="sender_id" value="<?php echo $chat_with; ?>">
                                <button type="submit" name="action" value="approve" class="btn-approve">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                                <button type="submit" name="action" value="reject" class="btn-reject">
                                    <i class="fas fa-times"></i> Decline
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="messages">
                    <?php foreach ($messages as $message): ?>
                        <div class="message <?php echo ($message['sender_id'] == $user_id) ? 'sent' : 'received'; ?>">
                            <?php echo htmlspecialchars($message['content']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (!$has_pending_request): ?>
                    <form method="POST" class="message-input">
                        <textarea name="message" placeholder="Type a message..." required></textarea>
                        <input type="hidden" name="receiver_id" value="<?php echo $chat_with; ?>">
                        <button type="submit">Send</button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-chat-selected">
                    <i class="fas fa-comments"></i>
                    <p>Select a conversation or search for a user to start chatting</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleRequests() {
            const container = document.getElementById('requestsContainer');
            const icon = document.getElementById('toggleIcon');
            container.classList.toggle('show');
            icon.style.transform = container.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0)';
        }

        // Show requests by default when there are pending requests
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('requestsContainer');
            const icon = document.getElementById('toggleIcon');
            if (container) {
                container.classList.add('show');
                icon.style.transform = 'rotate(180deg)';
            }
        });
    </script>
</body>

</html>
