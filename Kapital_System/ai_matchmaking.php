<?php
include('fetch_data.php');

// Fetch investor data and startups
$investor = get_investor_data($_SESSION['user_id']);
$startups = get_startups();

// Prepare data for Python script
$data = [
    'investor' => $investor,
    'startups' => $startups
];

// Call Python script
$command = escapeshellcmd("python3 python_matchmaking.py '" . json_encode($data) . "'");
$output = shell_exec($command);

// Return results to frontend
echo $output;
?>
