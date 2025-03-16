CREATE TABLE IF NOT EXISTS AI_Conversations (
    conversation_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    question TEXT NOT NULL,
    response TEXT,
    created_at DATETIME NOT NULL,
    responded_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 