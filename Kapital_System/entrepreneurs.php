<?php
session_start();
include('navbar.php');
include('db_connection.php');
include('verification_check.php');

// Redirect if the user is not logged in or does not have the entrepreneur role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

$user_id = $_SESSION['user_id'];

// Check verification status
$verification_status = checkVerification(false);

// Retrieve the entrepreneur details
$query = "SELECT * FROM Entrepreneurs WHERE entrepreneur_id = '$user_id'";
$result = mysqli_query($conn, $query);
$entrepreneur = mysqli_fetch_assoc($result);

// Fetch startups posted by the entrepreneur and others
$startups_query = "
    SELECT * FROM Startups 
    WHERE entrepreneur_id = '$user_id' 
    OR entrepreneur_id IN (SELECT entrepreneur_id FROM Startups)
    ORDER BY created_at DESC";
$startups_result = mysqli_query($conn, $startups_query);
?>

<!-- Display the entrepreneur's dashboard -->
<div class="container">
    <h1>Welcome, <span
            class="entrepreneur-name"><?php echo isset($entrepreneur['name']) ? $entrepreneur['name'] : 'Entrepreneur'; ?></span>!
    </h1>

    <?php if ($verification_status !== 'verified'): ?>
        <div class="verification-notice">
            <h3><i class="fas fa-exclamation-triangle"></i> Account Verification Required</h3>
            <p>Your account needs to be verified to access the following features:</p>
            <ul>
                <li>Creating new startups</li>
                <li>Posting jobs</li>
                <li>Managing startup profiles</li>
                <li>Viewing applicant details</li>
            </ul>
            <a href="verify_account.php" class="btn btn-warning">Verify Your Account</a>
        </div>
    <?php endif; ?>

    <div class="action-buttons">
        <?php if ($verification_status === 'verified'): ?>
            <a href="create_startup.php" class="btn btn-secondary">Create New Startup</a>
            <a href="post-job.php" class="btn btn-primary">Post a Job</a>
        <?php endif; ?>
    </div>

    <h2>News Feed</h2>
    <?php while ($startup = mysqli_fetch_assoc($startups_result)): ?>
        <?php $is_entrepreneur_post = $startup['entrepreneur_id'] == $user_id; ?>
        <div class="startup-post">
            <div class="startup-header">
                <div class="startup-logo">
                    <?php if (!empty($startup['logo_url']) && file_exists($startup['logo_url'])): ?>
                        <img src="<?php echo htmlspecialchars($startup['logo_url']); ?>" alt="<?php echo htmlspecialchars($startup['name']); ?> logo">
                    <?php else: ?>
                        <i class="fas fa-building"></i>
                    <?php endif; ?>
                </div>
                <div class="startup-info">
                    <h3><?php echo htmlspecialchars($startup['name']); ?></h3>
                    <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($startup['description']); ?></p>
                </div>
            </div>

            <div class="startup-actions">
                <?php if ($is_entrepreneur_post && $verification_status === 'verified'): ?>
                    <a href="edit_startup.php?startup_id=<?php echo $startup['startup_id']; ?>" class="btn btn-warning">Edit Startup</a>
                    <a href="view_applicants.php?startup_id=<?php echo $startup['startup_id']; ?>" class="btn btn-info">View Applicants</a>
                <?php endif; ?>
                <a href="startup_detail.php?startup_id=<?php echo $startup['startup_id']; ?>" class="btn btn-info">View Details</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- Embedded CSS -->
<style>
    .container {
        width: 80%;
        margin: 0 auto;
    }

    h1 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }

    .entrepreneur-name {
        color: #D8A25E;
    }

    h2 {
        font-size: 2rem;
        font-weight: bold;
        color: #D8A25E;
        margin-bottom: 20px;
        text-align: center;
    }

    .startup-post {
        background-color: #ffffff;
        padding: 20px;
        margin: 20px 0;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease-in-out;
        overflow: hidden;
    }

    .startup-post:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .startup-header {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }

    .startup-logo {
        width: 100px;
        height: 100px;
        flex-shrink: 0;
        border-radius: 8px;
        overflow: hidden;
        background-color: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .startup-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .startup-logo i {
        font-size: 40px;
        color: #D8A25E;
    }

    .startup-info {
        flex-grow: 1;
    }

    .startup-info h3 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 10px;
        margin-top: 0;
    }

    .startup-info p {
        font-size: 1rem;
        color: #555;
        margin: 5px 0;
    }

    .startup-actions {
        margin-top: 15px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .startup-post .btn {
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }

    .startup-post .btn-info {
        background-color: #17a2b8;
        color: white;
    }

    .startup-post .btn-info:hover {
        background-color: #138496;
    }

    .startup-post .btn-warning {
        background-color: #ffc107;
        color: white;
    }

    .startup-post .btn-warning:hover {
        background-color: #e0a800;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    @media (max-width: 768px) {
        .container {
            width: 95%;
        }

        .startup-header {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .startup-logo {
            width: 80px;
            height: 80px;
        }

        .startup-actions {
            justify-content: center;
        }
    }

    .verification-notice {
        background: #23272A;
        border: 1px solid #40444B;
        color: #FFFFFF;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .verification-notice h3 {
        color: #7289DA;
        font-size: 1.5rem;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .verification-notice h3 i {
        color: #7289DA;
        font-size: 1.8rem;
    }

    .verification-notice p {
        color: #B9BBBE;
        margin-bottom: 15px;
    }

    .verification-notice ul {
        list-style: none;
        padding: 0;
        margin: 0 0 20px 0;
    }

    .verification-notice ul li {
        color: #B9BBBE;
        padding: 8px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .verification-notice ul li:before {
        content: "•";
        color: #7289DA;
        font-size: 1.2rem;
    }

    .verification-notice .btn-warning {
        background: #7289DA;
        color: #FFFFFF;
        padding: 12px 24px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .verification-notice .btn-warning:hover {
        background: #5b6eae;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }

    @media (max-width: 768px) {
        .verification-notice {
            padding: 20px;
            margin: 15px;
        }

        .verification-notice h3 {
            font-size: 1.3rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 10px;
        }

        .action-buttons .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>