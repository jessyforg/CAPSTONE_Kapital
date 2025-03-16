<?php
require_once 'db_connection.php';
require_once 'ai_service.php';

// Test the AI service
echo "<h2>Testing AI Service</h2>";

try {
    // Create AI service instance
    $ai_service = new AIService();
    
    // Display configuration
    echo "<h3>Current Configuration:</h3>";
    echo "<pre>";
    echo "API Key: " . substr(AI_API_KEY, 0, 10) . "...\n";
    echo "Model: " . AI_MODEL . "\n";
    echo "API URL: https://api-inference.huggingface.co/models/" . AI_MODEL . "\n";
    echo "</pre>";
    
    // Test question
    $test_question = "What are the key steps to create a business plan?";
    echo "<h3>Test Question:</h3>";
    echo "<p>" . htmlspecialchars($test_question) . "</p>";
    
    // Get response
    echo "<h3>AI Response:</h3>";
    $response = $ai_service->getResponse($test_question);
    
    // Check if response starts with "Error:"
    if (strpos($response, "Error:") === 0) {
        echo "<p style='color: red;'>" . nl2br(htmlspecialchars($response)) . "</p>";
    } else {
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
    }
    
    // Test database connection
    echo "<h3>Testing Database Connection:</h3>";
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM AI_Conversations");
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    echo "<p>Total conversations in database: " . $result['count'] . "</p>";
    
    // Test inserting a conversation
    echo "<h3>Testing Database Insert:</h3>";
    $stmt = $conn->prepare("INSERT INTO AI_Conversations (user_id, question, response, created_at, responded_at) VALUES (?, ?, ?, NOW(), NOW())");
    $test_user_id = 1; // Replace with a valid user_id from your database
    $stmt->bind_param("iss", $test_user_id, $test_question, $response);
    
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Successfully inserted test conversation!</p>";
    } else {
        echo "<p style='color: red;'>Error inserting test conversation: " . $conn->error . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?> 