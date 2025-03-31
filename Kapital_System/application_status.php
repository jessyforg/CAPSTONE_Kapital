<?php
// Include database connection
include 'db_connection.php';
session_start();

include 'navbar.php';
// Check if the logged-in user is an entrepreneur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: index.php");
    exit;
}

// Check if application_id is set
if (!isset($_GET['application_id'])) {
    echo "Invalid application.";
    exit;
}

$application_id = intval($_GET['application_id']);

// Fetch the application details, including the cover letter
$query = "SELECT a.*, j.role AS job_role, j.startup_id, u.name AS job_seeker_name, a.cover_letter
          FROM Applications a
          JOIN Jobs j ON a.job_id = j.job_id
          JOIN Users u ON a.job_seeker_id = u.user_id
          WHERE a.application_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $application_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the application exists
if ($result->num_rows > 0) {
    $application = $result->fetch_assoc();
    $application_status = $application['status'];
    $job_seeker_name = $application['job_seeker_name'];
    $job_role = $application['job_role'];
    $cover_letter = $application['cover_letter']; // Get the cover letter
} else {
    echo "Application not found.";
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
    $new_status = $_POST['status'];

    // Validate status
    if (!in_array($new_status, ['reviewed', 'interviewed', 'hired', 'rejected'])) {
        echo "Invalid status.";
        exit;
    }

    // Update the application status
    $update_query = "UPDATE Applications SET status = ? WHERE application_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $new_status, $application_id);
    $update_stmt->execute();

    // Create the notification message
    $notification_message = "Your application for the $job_role role has been updated to $new_status.";

    // Optionally, create a notification for the job seeker
    $notification_query = "INSERT INTO Notifications (user_id, sender_id, type, application_id, job_id, message, status) 
                            VALUES (?, ?, 'application_status', ?, ?, ?, 'unread')";
    $notification_stmt = $conn->prepare($notification_query);
    $notification_stmt->bind_param(
        "iiiss", // Bind parameters
        $application['job_seeker_id'], // Job seeker ID
        $_SESSION['user_id'],           // Entrepreneur ID (sender)
        $application_id,                // Application ID
        $application['job_id'],         // Job ID (from the job associated with the application)
        $notification_message           // The notification message
    );
    $notification_stmt->execute();

    echo "Application status updated successfully.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status</title>
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

        h2 {
            text-align: center;
            color: #7289DA;
            margin-bottom: 20px;
        }

        .status-info {
            margin-bottom: 20px;
            font-size: 1.2em;
        }

        .status-form {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .status-form label {
            font-weight: bold;
            margin-right: 10px;
        }

        .status-form select {
            padding: 8px;
            font-size: 1em;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .btn-update {
            padding: 10px 20px;
            font-size: 1em;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-update:hover {
            background-color: #45a049;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #F44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-back:hover {
            background-color: #e53935;
        }

        .cover-letter {
            margin-top: 20px;
            padding: 10px;
            background-color: #2C2F33;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .cover-letter pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        /* Message section styles */
        .message-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #2C2F33;
            border-radius: 8px;
            border: 1px solid #7289DA;
        }

        .message-history {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #23272A;
            border-radius: 5px;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }

        .message.sent {
            background-color: #7289DA;
            margin-left: 20%;
            color: white;
        }

        .message.received {
            background-color: #40444B;
            margin-right: 20%;
        }

        .message-meta {
            font-size: 0.8em;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 5px;
        }

        .message-form {
            display: flex;
            gap: 10px;
        }

        .message-input {
            flex-grow: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #7289DA;
            background-color: #40444B;
            color: white;
            font-family: inherit;
        }

        .send-button {
            padding: 10px 20px;
            background-color: #7289DA;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .send-button:hover {
            background-color: #5b6eae;
        }

        .view-full-chat {
            color: #7289DA;
            text-decoration: none;
            font-size: 0.9em;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .view-full-chat:hover {
            background-color: rgba(114, 137, 218, 0.1);
            color: #5b6eae;
        }

        .btn-message {
            display: inline-block;
            margin: 20px 0;
            padding: 12px 24px;
            background-color: #7289DA;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        .btn-message:hover {
            background-color: #5b6eae;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            position: relative;
            background-color: #23272A;
            margin: 5% auto;
            padding: 0;
            width: 80%;
            max-width: 700px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            padding: 15px;
            border-bottom: 1px solid #7289DA;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            color: #7289DA;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #fff;
        }

        .modal-footer {
            padding: 15px;
            border-top: 1px solid #40444B;
            text-align: right;
        }

        .message-history {
            height: 300px;
            overflow-y: auto;
            padding: 15px;
            background-color: #2C2F33;
        }

        /* Update existing message styles for modal */
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            max-width: 80%;
        }

        .message.sent {
            background-color: #7289DA;
            margin-left: auto;
            color: white;
        }

        .message.received {
            background-color: #40444B;
            margin-right: auto;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Application Status for <?php echo htmlspecialchars($job_seeker_name); ?> - 
            <?php echo htmlspecialchars($job_role); ?>
        </h2>
        <div class="status-info">
            <p><strong>Current Status:</strong> <span><?php echo ucfirst($application_status); ?></span></p>
        </div>

        <div class="cover-letter">
            <h3>Cover Letter:</h3>
            <pre><?php echo nl2br(htmlspecialchars($cover_letter)); ?></pre>
        </div>

        <!-- Message Button -->
        <button id="openChatBtn" class="btn-message">Message <?php echo htmlspecialchars($job_seeker_name); ?></button>

        <!-- Chat Modal -->
        <div id="chatModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Chat with <?php echo htmlspecialchars($job_seeker_name); ?></h3>
                    <span class="close">&times;</span>
                </div>
                <div class="message-history" id="messageHistory">
                    <?php
                    // Fetch messages between entrepreneur and job seeker
                    $message_query = "SELECT m.*, u.name as sender_name 
                                    FROM Messages m 
                                    JOIN Users u ON m.sender_id = u.user_id 
                                    WHERE (m.sender_id = ? AND m.receiver_id = ?) 
                                       OR (m.sender_id = ? AND m.receiver_id = ?)
                                    ORDER BY m.sent_at ASC";
                    $msg_stmt = $conn->prepare($message_query);
                    $msg_stmt->bind_param("iiii", 
                        $_SESSION['user_id'], 
                        $application['job_seeker_id'],
                        $application['job_seeker_id'],
                        $_SESSION['user_id']
                    );
                    $msg_stmt->execute();
                    $messages = $msg_stmt->get_result();

                    while ($message = $messages->fetch_assoc()):
                        $is_sent = $message['sender_id'] == $_SESSION['user_id'];
                    ?>
                        <div class="message <?php echo $is_sent ? 'sent' : 'received'; ?>">
                            <div class="message-content"><?php echo nl2br(htmlspecialchars($message['content'])); ?></div>
                            <div class="message-meta">
                                <?php echo htmlspecialchars($message['sender_name']); ?> - 
                                <?php echo date('M j, Y g:i A', strtotime($message['sent_at'])); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <form method="POST" action="send_message.php" class="message-form" id="messageForm">
                    <input type="hidden" name="receiver_id" value="<?php echo $application['job_seeker_id']; ?>">
                    <input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
                    <textarea name="message" class="message-input" placeholder="Type your message here..." required></textarea>
                    <button type="submit" class="send-button">Send</button>
                </form>
                
                <div class="modal-footer">
                    <a href="messages.php?chat_with=<?php echo $application['job_seeker_id']; ?>" class="view-full-chat">
                        View Full Conversation <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
        </div>

        <form method="POST" action="application_status.php?application_id=<?php echo $application_id; ?>"
            class="status-form">
            <label for="status">Change Status:</label>
            <select name="status" id="status" required>
                <option value="reviewed" <?php if ($application_status == 'reviewed') echo 'selected'; ?>>Reviewed</option>
                <option value="interviewed" <?php if ($application_status == 'interviewed') echo 'selected'; ?>>Interviewed</option>
                <option value="hired" <?php if ($application_status == 'hired') echo 'selected'; ?>>Hired</option>
<option value="not approved" <?php if ($application_status == 'rejected') echo 'selected'; ?>>Not Approved</option>

            </select>
            <button type="submit" class="btn-update">Update Status</button>
        </form>

        <a href="entrepreneurs.php" class="btn-back">Back to Dashboard</a>
    </div>

    <script>
        // Get modal elements
        const modal = document.getElementById('chatModal');
        const openBtn = document.getElementById('openChatBtn');
        const closeBtn = document.querySelector('.close');
        const messageHistory = document.getElementById('messageHistory');
        const messageForm = document.getElementById('messageForm');

        // Open modal
        openBtn.onclick = function() {
            modal.style.display = "block";
            messageHistory.scrollTop = messageHistory.scrollHeight;
        }

        // Close modal
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Handle form submission with AJAX
        messageForm.onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(messageForm);
            
            fetch('send_message.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(() => {
                // Clear the message input
                messageForm.querySelector('textarea').value = '';
                
                // Refresh messages
                updateMessages();
            })
            .catch(error => console.error('Error:', error));
        };

        // Function to update messages
        function updateMessages() {
            fetch('get_messages.php?application_id=<?php echo $application_id; ?>')
                .then(response => response.text())
                .then(html => {
                    messageHistory.innerHTML = html;
                    messageHistory.scrollTop = messageHistory.scrollHeight;
                });
        }

        // Update messages every 10 seconds
        setInterval(updateMessages, 10000);
    </script>

</body>

</html>
