<?php
require_once 'db_connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test database connection and content
$test_query = "SELECT COUNT(*) as count FROM Users";
$test_result = $conn->query($test_query);
file_put_contents('search_log.txt', date('Y-m-d H:i:s') . " - Total users in database: " . $test_result->fetch_assoc()['count'] . "\n", FILE_APPEND);

// Log access to this file
file_put_contents('search_log.txt', date('Y-m-d H:i:s') . " - Search accessed\n", FILE_APPEND);

if (isset($_GET['query'])) {
    $search_query = '%' . $_GET['query'] . '%';
    $results = array();

    // Log the search query
    file_put_contents('search_log.txt', date('Y-m-d H:i:s') . " - Search query: " . $_GET['query'] . "\n", FILE_APPEND);

    try {
        // Prepare statements for different types of searches
        $user_stmt = $conn->prepare("
            SELECT user_id, name, role 
            FROM Users 
            WHERE name LIKE ? OR email LIKE ? 
            LIMIT 5
        ");

        $startup_stmt = $conn->prepare("
            SELECT s.startup_id, s.name, s.description, s.industry 
            FROM Startups s
            WHERE s.name LIKE ? OR s.description LIKE ? OR s.industry LIKE ?
            AND s.approval_status = 'approved'
            LIMIT 5
        ");

        $job_stmt = $conn->prepare("
            SELECT j.job_id, j.role as title, s.name as company, j.location, j.description
            FROM Jobs j
            JOIN Startups s ON j.startup_id = s.startup_id
            WHERE j.role LIKE ? OR s.name LIKE ? OR j.description LIKE ?
            AND s.approval_status = 'approved'
            LIMIT 5
        ");

        // Execute user search
        $user_stmt->bind_param('ss', $search_query, $search_query);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        while ($row = $user_result->fetch_assoc()) {
            $row['type'] = 'user';
            $results[] = $row;
        }

        // Execute startup search
        $startup_stmt->bind_param('sss', $search_query, $search_query, $search_query);
        $startup_stmt->execute();
        $startup_result = $startup_stmt->get_result();
        while ($row = $startup_result->fetch_assoc()) {
            $row['type'] = 'startup';
            $results[] = $row;
        }

        // Execute job search
        $job_stmt->bind_param('sss', $search_query, $search_query, $search_query);
        $job_stmt->execute();
        $job_result = $job_stmt->get_result();
        while ($row = $job_result->fetch_assoc()) {
            $row['type'] = 'job';
            $results[] = $row;
        }

        // Log the number of results found
        file_put_contents('search_log.txt', date('Y-m-d H:i:s') . " - Results found: " . count($results) . "\n", FILE_APPEND);

    } catch (Exception $e) {
        // Log any errors
        file_put_contents('search_log.txt', date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n", FILE_APPEND);
        $results = ['error' => $e->getMessage()];
    }

    // Return results as JSON
    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
} else {
    // Log when query parameter is missing
    file_put_contents('search_log.txt', date('Y-m-d H:i:s') . " - No query parameter provided\n", FILE_APPEND);
}
?> 