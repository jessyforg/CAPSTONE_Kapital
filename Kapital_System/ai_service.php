<?php
require_once 'db_connection.php';

class AIService {
    private $api_key;
    private $model;
    private $api_url;
    
    public function __construct() {
        $this->api_key = AI_API_KEY;
        $this->model = AI_MODEL;
        $this->api_url = "https://api-inference.huggingface.co/models/" . $this->model;
    }
    
    public function getResponse($question) {
        $max_retries = 3;
        $retry_delay = 2; // seconds
        
        for ($attempt = 1; $attempt <= $max_retries; $attempt++) {
            $headers = [
                "Authorization: Bearer " . $this->api_key,
                "Content-Type: application/json"
            ];
            
            $data = [
                "inputs" => "Question: " . $question . "\nAnswer:",
                "parameters" => [
                    "max_new_tokens" => 250,
                    "temperature" => 0.7,
                    "top_p" => 0.95,
                    "wait_for_model" => true
                ]
            ];
            
            $ch = curl_init($this->api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            try {
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curl_error = curl_error($ch);
                
                error_log("Attempt $attempt - API Response Code: " . $http_code);
                error_log("Attempt $attempt - API Response: " . $response);
                
                if ($curl_error) {
                    throw new Exception("Curl Error: " . $curl_error);
                }
                
                if ($http_code === 200) {
                    $result = json_decode($response, true);
                    if (isset($result[0]['generated_text'])) {
                        return $result[0]['generated_text'];
                    }
                } elseif ($http_code === 503 && $attempt < $max_retries) {
                    error_log("Model is loading, retrying in $retry_delay seconds...");
                    sleep($retry_delay);
                    continue;
                }
                
                $error_message = "API Error: HTTP Code " . $http_code . "\n";
                $error_message .= "Response: " . $response . "\n";
                $error_message .= "Curl Error: " . $curl_error;
                
                error_log($error_message);
                
                if ($attempt === $max_retries) {
                    return "Error: " . $error_message;
                }
                
            } catch (Exception $e) {
                error_log("Attempt $attempt - Error: " . $e->getMessage());
                if ($attempt === $max_retries) {
                    return "Error: " . $e->getMessage();
                }
            } finally {
                curl_close($ch);
            }
            
            // Wait before retrying
            if ($attempt < $max_retries) {
                sleep($retry_delay);
            }
        }
        
        return "Error: Maximum retries reached";
    }
} 