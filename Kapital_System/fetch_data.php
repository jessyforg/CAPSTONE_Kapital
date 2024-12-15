<?php
include('db_connection.php');

// Fetch investor data
function get_investor_data($investor_id) {
    global $conn;
    $sql = "SELECT * FROM Investors WHERE investor_id = $investor_id";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

// Fetch all startups
function get_startups() {
    global $conn;
    $sql = "SELECT * FROM Startups WHERE approval_status = 'approved'";
    $result = $conn->query($sql);
    $startups = [];
    while ($row = $result->fetch_assoc()) {
        $startups[] = $row;
    }
    return $startups;
}
?>
