<?php
session_start();
include('navbar.php');
include('db_connection.php');
include('ai_service.php');

// Ensure user is logged in and is an entrepreneur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

// Handle AI conversation
if (isset($_POST['question'])) {
    $question = trim($_POST['question']);
    $user_id = $_SESSION['user_id'];
    
    // Store the question in the database
    $stmt = $conn->prepare("INSERT INTO AI_Conversations (user_id, question, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $user_id, $question);
    $stmt->execute();
    $conversation_id = $conn->insert_id;
    
    // Get AI response
    $ai_service = new AIService();
    $response = $ai_service->getResponse($question);
    
    // Store the response
    $stmt = $conn->prepare("UPDATE AI_Conversations SET response = ?, responded_at = NOW() WHERE conversation_id = ?");
    $stmt->bind_param("si", $response, $conversation_id);
    $stmt->execute();
    
    // Redirect to prevent form resubmission
    header("Location: startup_ai_advisor.php");
    exit();
}

// Fetch previous conversations
$stmt = $conn->prepare("
    SELECT question, response, created_at, responded_at 
    FROM AI_Conversations 
    WHERE user_id = ? 
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$conversations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startup AI Advisor</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .ai-chat-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            border: 1px solid rgba(243, 192, 0, 0.2);
        }

        .chat-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .chat-header h1 {
            color: #f3c000;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .chat-header p {
            color: rgba(255, 255, 255, 0.8);
        }

        .question-form {
            margin-bottom: 30px;
        }

        .question-input {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(243, 192, 0, 0.3);
            border-radius: 8px;
            color: #fff;
            font-size: 1em;
            margin-bottom: 15px;
            resize: vertical;
            min-height: 100px;
        }

        .question-input:focus {
            outline: none;
            border-color: #f3c000;
            box-shadow: 0 0 0 2px rgba(243, 192, 0, 0.2);
        }

        .submit-button {
            background: linear-gradient(45deg, #f3c000, #ffab00);
            color: #000;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 192, 0, 0.3);
        }

        .conversation-history {
            margin-top: 40px;
        }

        .conversation-item {
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            border: 1px solid rgba(243, 192, 0, 0.1);
        }

        .question {
            color: #f3c000;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .response {
            color: #fff;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .timestamp {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9em;
        }

        .suggested-topics {
            margin-top: 30px;
            padding: 20px;
            background: rgba(243, 192, 0, 0.1);
            border-radius: 10px;
        }

        .suggested-topics h3 {
            color: #f3c000;
            margin-bottom: 15px;
        }

        .topic-list {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .topic-item {
            background: rgba(243, 192, 0, 0.2);
            color: #f3c000;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .topic-item:hover {
            background: rgba(243, 192, 0, 0.3);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .ai-chat-container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="ai-chat-container">
        <div class="chat-header">
            <h1><i class="fas fa-robot"></i> Startup AI Advisor</h1>
            <p>Ask anything about starting and growing your business</p>
        </div>

        <form method="POST" class="question-form">
            <textarea 
                name="question" 
                class="question-input" 
                placeholder="Ask about business planning, market analysis, funding strategies, or any other startup-related questions..."
                required
            ></textarea>
            <button type="submit" class="submit-button">
                <i class="fas fa-paper-plane"></i> Ask Question
            </button>
        </form>

        <div class="suggested-topics">
            <h3>Suggested Topics</h3>
            <div class="topic-list">
                <div class="topic-item" onclick="setQuestion('How do I create a compelling business plan?')">
                    Business Plan
                </div>
                <div class="topic-item" onclick="setQuestion('What are effective strategies for market research?')">
                    Market Research
                </div>
                <div class="topic-item" onclick="setQuestion('How can I attract potential investors?')">
                    Investment Strategy
                </div>
                <div class="topic-item" onclick="setQuestion('What are the key financial metrics I should track?')">
                    Financial Planning
                </div>
                <div class="topic-item" onclick="setQuestion('How do I identify my target market?')">
                    Target Market
                </div>
            </div>
        </div>

        <div class="conversation-history">
            <?php foreach ($conversations as $conv): ?>
                <div class="conversation-item">
                    <div class="question">
                        <i class="fas fa-question-circle"></i> 
                        <?php echo htmlspecialchars($conv['question']); ?>
                    </div>
                    <div class="response">
                        <i class="fas fa-robot"></i> 
                        <?php echo nl2br(htmlspecialchars($conv['response'])); ?>
                    </div>
                    <div class="timestamp">
                        Asked: <?php echo date('M d, Y H:i', strtotime($conv['created_at'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function setQuestion(question) {
            document.querySelector('.question-input').value = question;
        }
    </script>
</body>
</html> 