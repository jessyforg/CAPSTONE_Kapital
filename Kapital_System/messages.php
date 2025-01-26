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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Layout */
        .container {
            display: flex;
            height: calc(100vh - 60px); /* Adjust for navbar height */
            border-radius: 15px; /* Rounded corners for the entire container */
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Soft shadow for the container */
        }

        /* Sidebar */
        .sidebar {
            width: 30%;
            background-color: #f8f9fa;
            overflow-y: auto;
            border-right: 1px solid #ddd;
            border-radius: 15px 0 0 15px; /* Rounded corners for the left sidebar */
            padding: 20px 15px;
        }

        .chat-area {
            width: 70%;
            display: flex;
            flex-direction: column;
            border-radius: 0 15px 15px 0; /* Rounded corners for the right chat area */
        }

        /* Conversations and Search Results */
        .conversation, .search-result {
            padding: 12px 15px;
            cursor: pointer;
            border-radius: 10px; /* Rounded corners for each conversation */
            margin-bottom: 5px;
            transition: background-color 0.3s;
        }

        .conversation:hover, .search-result:hover {
            background-color: #e9ecef;
        }

        .conversation.active {
            background-color: #007bff;
            color: #fff;
        }

        /* Message Styles */
        .messages {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .message {
            max-width: 70%;
            padding: 12px 15px;
            border-radius: 20px;
            margin-bottom: 12px;
            word-wrap: break-word;
            display: inline-block;
            font-size: 16px;
        }

        .message.sent {
            background-color: #007bff;
            color: white;
            align-self: flex-end; /* Align to the right */
            border-radius: 20px 20px 0 20px; /* Rounded corners for the right side */
        }

        .message.received {
            background-color: #f1f1f1;
            align-self: flex-start; /* Align to the left */
            border-radius: 20px 20px 20px 0; /* Rounded corners for the left side */
        }

        /* Message Input */
        .message-input {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ddd;
            background-color: #fff;
        }

        .message-input textarea {
            flex-grow: 1;
            border-radius: 15px;
            padding: 12px;
            border: 1px solid #ddd;
        }

        .message-input button {
            margin-left: 10px;
            border-radius: 15px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .message-input button:hover {
            background-color: #0056b3;
        }

        /* Conversation Header */
        .conversation-header {
            background-color: #f8f9fa;
            padding: 15px;
            font-size: 18px;
            border-bottom: 1px solid #ddd;
            border-radius: 15px 15px 0 0; /* Rounded top corners */
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <form method="GET" action="messages.php" class="p-3">
                <input type="text" name="search" placeholder="Search users..." class="form-control mb-2" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </form>

            <?php if ($search_query): ?>
                <h5 class="p-3">Search Results</h5>
                <?php while ($user = $search_results->fetch_assoc()): ?>
                    <div class="search-result" onclick="window.location.href='messages.php?chat_with=<?php echo $user['user_id']; ?>'">
                        <?php echo htmlspecialchars($user['name']) . " (" . ucfirst($user['role']) . ")"; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>

            <h5 class="p-3">Conversations</h5>
            <?php while ($conversation = $conversations_result->fetch_assoc()): ?>
                <div class="conversation <?php echo ($chat_with == $conversation['user_id']) ? 'active' : ''; ?>" 
                     onclick="window.location.href='messages.php?chat_with=<?php echo $conversation['user_id']; ?>'">
                    <?php echo htmlspecialchars($conversation['name']) . " (" . ucfirst($conversation['role']) . ")"; ?>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <?php if ($chat_with && $chat_user): ?>
                <div class="conversation-header">
                    <?php echo htmlspecialchars($chat_user['name']) . " (" . ucfirst($chat_user['role']) . ")"; ?>
                </div>
            <?php endif; ?>

            <div class="messages">
                <?php if ($chat_with): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="message <?php echo ($message['sender_id'] == $user_id) ? 'sent' : 'received'; ?>">
                            <?php echo htmlspecialchars($message['content']); ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="p-3">Select a conversation or search for a user to start chatting.</p>
                <?php endif; ?>
            </div>
            <?php if ($chat_with): ?>
                <form method="POST" class="message-input">
                    <textarea name="message" class="form-control" rows="1" placeholder="Type a message..." required></textarea>
                    <input type="hidden" name="receiver_id" value="<?php echo $chat_with; ?>">
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
