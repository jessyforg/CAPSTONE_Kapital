<?php

class AIResumeHelper {
    private $api_key;
    private $model;
    private $api_url;
    
    public function __construct() {
        $this->api_key = AI_API_KEY;
        $this->model = AI_MODEL;
        $this->api_url = "https://api-inference.huggingface.co/models/" . $this->model;
    }

    /**
     * Enhance work experience with action verbs and quantifiable achievements
     */
    public function enhanceWorkExperience($experience, $role) {
        $prompt = "As a professional resume writer, enhance the following work experience for a {$role} position. 
                  Use strong action verbs, add quantifiable achievements where possible, and focus on relevant accomplishments. 
                  Keep the same basic information but make it more impactful:

                  Original experience:
                  {$experience}";

        return $this->callAI($prompt);
    }

    /**
     * Generate a professional summary based on experience and target role
     */
    public function generateProfessionalSummary($experience, $skills, $role) {
        $prompt = "Create a compelling professional summary for a {$role} position based on the following experience and skills:

                  Experience:
                  {$experience}

                  Skills:
                  {$skills}

                  Write a concise, powerful summary that highlights relevant achievements and skills for the {$role} position.";

        return $this->callAI($prompt);
    }

    /**
     * Optimize skills section for ATS and target role
     */
    public function optimizeSkills($skills, $role) {
        $prompt = "As an ATS optimization expert, analyze and reorganize these skills for a {$role} position:

                  Skills:
                  {$skills}

                  Return a comma-separated list of skills, prioritizing those most relevant for the role, 
                  including both hard and soft skills, and ensuring ATS-friendly formatting.";

        return $this->callAI($prompt);
    }

    /**
     * Enhance achievements to be more impactful
     */
    public function enhanceAchievements($achievements, $role) {
        $prompt = "Enhance the following achievements to be more impactful for a {$role} position. 
                  Add metrics where possible and focus on relevant outcomes:

                  Original achievements:
                  {$achievements}";

        return $this->callAI($prompt);
    }

    /**
     * Generate improvement suggestions for the resume
     */
    public function generateSuggestions($resume_content, $role) {
        $prompt = "As a professional resume reviewer, analyze this resume content for a {$role} position and provide specific suggestions for improvement:

                  Resume content:
                  {$resume_content}

                  Provide actionable suggestions for:
                  1. Content improvements
                  2. Format and structure
                  3. Keywords and ATS optimization
                  4. Areas that need more detail or metrics";

        return $this->callAI($prompt);
    }

    /**
     * Call the Hugging Face Inference API
     */
    private function callAI($prompt) {
        $max_retries = 3;
        $retry_delay = 2; // seconds
        
        for ($attempt = 1; $attempt <= $max_retries; $attempt++) {
            $headers = [
                "Authorization: Bearer " . $this->api_key,
                "Content-Type: application/json"
            ];
            
            $data = [
                "inputs" => $prompt,
                "parameters" => [
                    "max_new_tokens" => 500,
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