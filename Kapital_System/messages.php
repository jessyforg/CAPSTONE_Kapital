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

// Fetch conversations
$conversations_query = "
    SELECT DISTINCT 
        Users.user_id, 
        Users.name, 
        Users.role
    FROM Messages
    JOIN Users ON (Messages.sender_id = Users.user_id OR Messages.receiver_id = Users.user_id)
    WHERE Messages.sender_id = ? OR Messages.receiver_id = ?
    ORDER BY (SELECT MAX(Messages.sent_at) FROM Messages 
              WHERE Messages.sender_id = Users.user_id OR Messages.receiver_id = Users.user_id) DESC";
$conversations_stmt = $conn->prepare($conversations_query);
$conversations_stmt->bind_param('ii', $user_id, $user_id);
$conversations_stmt->execute();
$conversations_result = $conversations_stmt->get_result();

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver_id'], $_POST['message'])) {
    $receiver_id = htmlspecialchars($_POST['receiver_id']);
    $message = htmlspecialchars($_POST['message']);

    $send_message_query = "INSERT INTO Messages (sender_id, receiver_id, content, sent_at) VALUES (?, ?, ?, NOW())";
    $send_message_stmt = $conn->prepare($send_message_query);
    $send_message_stmt->bind_param('iis', $user_id, $receiver_id, $message);
    $send_message_stmt->execute();

    // Redirect to avoid form resubmission
    header("Location: messages.php?chat_with=$receiver_id");
    exit();
}

// Fetch chat messages and user details for the selected conversation
$chat_with = isset($_GET['chat_with']) ? (int)$_GET['chat_with'] : null;
$messages = [];
$chat_user = null;  // To store the chat user's details (name, role)
if ($chat_with) {
    $messages_query = "
        SELECT *
        FROM Messages
        WHERE (sender_id = ? AND receiver_id = ?)
        OR (sender_id = ? AND receiver_id = ?)
        ORDER BY sent_at ASC";
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

                <div class="messages">
                    <?php foreach ($messages as $message): ?>
                        <div class="message <?php echo ($message['sender_id'] == $user_id) ? 'sent' : 'received'; ?>">
                            <?php echo htmlspecialchars($message['content']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <form method="POST" class="message-input">
                    <textarea name="message" placeholder="Type a message..." required></textarea>
                    <input type="hidden" name="receiver_id" value="<?php echo $chat_with; ?>">
                    <button type="submit">Send</button>
                </form>
            <?php else: ?>
                <div class="no-chat-selected">
                    <i class="fas fa-comments"></i>
                    <p>Select a conversation or search for a user to start chatting</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
