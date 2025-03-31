<?php
function checkVerification($requireVerification = false) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: sign_in.php");
        exit("Redirecting to login page...");
    }

    $user_id = $_SESSION['user_id'];

    // Check if verification_status is set in session
    if (!isset($_SESSION['verification_status'])) {
        // Fetch verification status from database
        global $conn;
        $verification_query = "SELECT verification_status FROM Users WHERE user_id = '$user_id'";
        $verification_result = mysqli_query($conn, $verification_query);
        
        if ($verification_result && mysqli_num_rows($verification_result) > 0) {
            $verification_status = mysqli_fetch_assoc($verification_result)['verification_status'];
            $_SESSION['verification_status'] = $verification_status;
        } else {
            $_SESSION['verification_status'] = 'not verified';
        }
    }

    // If verification is required and user is not verified, redirect to verification page
    if ($requireVerification && $_SESSION['verification_status'] !== 'verified') {
        header("Location: verify_account.php");
        exit("Redirecting to verification page...");
    }

    return $_SESSION['verification_status'];
}
?> 