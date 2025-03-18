<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['application_id'])) {
    die("Unauthorized access");
}

// Get application details to verify access and get job seeker ID
$application_id = $_GET['application_id'];
$query = "SELECT a.job_seeker_id, j.startup_id 
          FROM Applications a
          JOIN Jobs j ON a.job_id = j.job_id
          JOIN Startups s ON j.startup_id = s.startup_id
          WHERE a.application_id = ? AND s.entrepreneur_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $application_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Unauthorized access");
}

$application = $result->fetch_assoc();
$job_seeker_id = $application['job_seeker_id'];

// Fetch messages
$message_query = "SELECT m.*, u.name as sender_name 
                FROM Messages m 
                JOIN Users u ON m.sender_id = u.user_id 
                WHERE (m.sender_id = ? AND m.receiver_id = ?) 
                   OR (m.sender_id = ? AND m.receiver_id = ?)
                ORDER BY m.sent_at ASC";
$msg_stmt = $conn->prepare($message_query);
$msg_stmt->bind_param("iiii", 
    $_SESSION['user_id'], 
    $job_seeker_id,
    $job_seeker_id,
    $_SESSION['user_id']
);
$msg_stmt->execute();
$messages = $msg_stmt->get_result();

// Output messages HTML
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