<?php
include 'db_connection.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Query to fetch the user's role
$user_query = "SELECT role FROM Users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
    $role = $user['role'];
} else {
    echo "User not found.";
    exit;
}

// Check if notification_id is set
if (!isset($_GET['notification_id'])) {
    header("Location: index.php");
    exit;
}

$notification_id = intval($_GET['notification_id']);

// Query to fetch the notification
$query = "SELECT * FROM Notifications WHERE notification_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $notification_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $notification = $result->fetch_assoc();

    // Mark notification as 'read'
    $update_query = "UPDATE Notifications SET status = 'read' WHERE notification_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("i", $notification_id);
    $update_stmt->execute();

    // Role-based redirection
    if ($role == 'entrepreneur') {
        // If it's a system alert, redirect to index.php
        if ($notification['type'] == 'system_alert') {
            header("Location: index.php");
            exit;
        }

        // For investment match, redirect to match-details.php
        if ($notification['type'] == 'investment_match' && !empty($notification['match_id'])) {
            header("Location: match_details.php?match_id=" . $notification['match_id']);
            exit;
        }
        
        // For other types, redirect to application status page if application_id exists
        if (!empty($notification['application_id'])) {
            $application_id = $notification['application_id'];
            header("Location: application_status.php?application_id=" . $application_id);
            exit;
        } else {
            echo "No application ID for this notification.";
            exit;
        }
    } elseif ($role == 'job_seeker') {
        // Redirect job seeker to specific job details page
        if (!empty($notification['job_id'])) {
            $job_id = $notification['job_id'];
            header("Location: job-details.php?job_id=" . $job_id);
            exit;
        } else {
            echo "No job ID for this notification.";
            exit;
        }
    }

    // Redirect based on the notification type if role-based redirection hasn't been triggered
    switch ($notification['type']) {
        case 'application_status':
            if (!empty($notification['application_id'])) {
                $application_id = $notification['application_id'];
                header("Location: application_status.php?application_id=" . $application_id);
            } else {
                echo "No application ID provided for this notification.";
            }
            break;

        case 'investment_match':
            if (!empty($notification['match_id'])) {
                header("Location: match_details.php?match_id=" . $notification['match_id']);
            } else {
                echo "No match ID provided for this notification.";
            }
            break;

        case 'job_offer':
            if (!empty($notification['job_id'])) {
                header("Location: job_offer_details.php?job_id=" . $notification['job_id']);
            } else {
                echo "No job ID provided for this notification.";
            }
            break;

        case 'message':
            if (!empty($notification['sender_id'])) {
                header("Location: messages.php?conversation_id=" . $notification['sender_id']);
            } else {
                echo "No sender ID provided for this notification.";
            }
            break;

        case 'startup_status':
            if (!empty($notification['startup_id'])) {
                // Query to fetch startup details based on startup_id
                $startup_query = "SELECT * FROM Startups WHERE startup_id = ?";
                $startup_stmt = $conn->prepare($startup_query);
                $startup_stmt->bind_param("i", $notification['startup_id']);
                $startup_stmt->execute();
                $startup_result = $startup_stmt->get_result();

                if ($startup_result->num_rows > 0) {
                    $startup = $startup_result->fetch_assoc();
                    // Redirect to the startup details page
                    header("Location: startup_detail.php?startup_id=" . $startup['startup_id']);
                } else {
                    echo "Startup not found.";
                }
            } else {
                echo "No startup ID provided for this notification.";
            }
            break;

        case 'system_alert':
            // For system alerts, redirect to the homepage (index.php)
            header("Location: index.php");
            break;

        default:
            // If the notification type is unknown, check for the 'url' field and redirect
            if (!empty($notification['url'])) {
                header("Location: " . $notification['url']);
            } else {
                header("Location: index.php");
            }
            break;
    }
    exit;
} else {
    echo "Notification not found.";
    exit;
}
?>
