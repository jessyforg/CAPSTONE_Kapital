<?php
session_start();
include('navbar.php');
include('db_connection.php');

// Redirect if the user is not logged in or does not have the entrepreneur role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

$user_id = $_SESSION['user_id'];

// Check if startup_id is provided
if (!isset($_GET['startup_id'])) {
    echo "Invalid startup.";
    exit;
}

$startup_id = intval($_GET['startup_id']);

// First, fetch all jobs posted by this startup
$jobs_query = "SELECT job_id, role, created_at FROM Jobs WHERE startup_id = ? ORDER BY created_at DESC";
$jobs_stmt = $conn->prepare($jobs_query);
$jobs_stmt->bind_param("i", $startup_id);
$jobs_stmt->execute();
$jobs_result = $jobs_stmt->get_result();

// Fetch the startup details for the header
$startup_query = "SELECT name FROM Startups WHERE startup_id = ?";
$startup_stmt = $conn->prepare($startup_query);
$startup_stmt->bind_param("i", $startup_id);
$startup_stmt->execute();
$startup_result = $startup_stmt->get_result();
$startup = $startup_result->fetch_assoc();

// Create an array to store applicants by job
$applicants_by_job = array();

// For each job, fetch its applicants
while ($job = $jobs_result->fetch_assoc()) {
    $applicants_query = "SELECT a.*, u.name AS job_seeker_name, u.email AS job_seeker_email 
                        FROM Applications a
                        JOIN Users u ON a.job_seeker_id = u.user_id
                        WHERE a.job_id = ?
                        ORDER BY a.created_at DESC";
    $applicants_stmt = $conn->prepare($applicants_query);
    $applicants_stmt->bind_param("i", $job['job_id']);
    $applicants_stmt->execute();
    $applicants_result = $applicants_stmt->get_result();
    
    $applicants = array();
    while ($applicant = $applicants_result->fetch_assoc()) {
        $applicants[] = $applicant;
    }
    
    $applicants_by_job[$job['job_id']] = array(
        'role' => $job['role'],
        'applicants' => $applicants,
        'created_at' => $job['created_at']
    );
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applicants for <?php echo htmlspecialchars($startup['name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #2C2F33;
            color: #f9f9f9;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h1 {
            text-align: center;
            color: #7289DA;
            margin-bottom: 25px;
            font-size: 2em;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            padding: 0 20px;
        }

        .job-section {
            margin-bottom: 40px;
            background: #23272A;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #40444B;
        }

        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #40444B;
            margin-bottom: 20px;
        }

        .job-title {
            color: #7289DA;
            font-size: 1.4em;
            font-weight: 600;
            margin: 0;
        }

        .job-meta {
            color: #B9BBBE;
            font-size: 0.9em;
        }

        .applicant-count {
            background: #7289DA;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 500;
        }

        .applicant-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .applicant {
            background: #2C2F33;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #40444B;
            display: flex;
            flex-direction: column;
        }

        .applicant:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            border-color: #7289DA;
        }

        .applicant-info {
            flex-grow: 1;
            margin-bottom: 15px;
        }

        .applicant h3 {
            margin: 0 0 12px 0;
            color: #FFFFFF;
            font-size: 1.2em;
            font-weight: 600;
        }

        .applicant p {
            margin: 8px 0;
            color: #B9BBBE;
            font-size: 0.95em;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 500;
            margin-top: 3px;
            text-transform: capitalize;
        }

        .status-pending { background-color: #FAA61A; color: #000; }
        .status-reviewed { background-color: #3498DB; color: #fff; }
        .status-interviewed { background-color: #9B59B6; color: #fff; }
        .status-hired { background-color: #2ECC71; color: #fff; }
        .status-rejected { background-color: #E74C3C; color: #fff; }

        .button-group {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }

        .btn-view, .btn-message {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.9em;
            flex: 1;
        }

        .btn-view {
            background-color: #7289DA;
        }

        .btn-message {
            background-color: #43B581;
        }

        .btn-view:hover, .btn-message:hover {
            transform: translateY(-2px);
        }

        .btn-view:hover { background-color: #5b6eae; }
        .btn-message:hover { background-color: #3ca374; }

        .no-applicants {
            text-align: center;
            color: #B9BBBE;
            padding: 30px;
            background: #2C2F33;
            border-radius: 8px;
            margin-top: 15px;
        }

        .no-jobs {
            text-align: center;
            color: #B9BBBE;
            padding: 50px 20px;
            background: #23272A;
            border-radius: 12px;
            margin: 40px auto;
            max-width: 500px;
            border: 1px solid #40444B;
        }

        .no-jobs i, .no-applicants i {
            font-size: 2.5em;
            margin-bottom: 20px;
            display: block;
            color: #7289DA;
        }

        .create-job-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #7289DA;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .create-job-btn:hover {
            background: #5b6eae;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
                margin: 15px auto;
            }

            .job-section {
                padding: 15px;
            }

            .applicant-list {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .job-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .button-group {
                flex-direction: column;
            }

            h1 {
                font-size: 1.6em;
                margin-bottom: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Job Applicants for <?php echo htmlspecialchars($startup['name']); ?></h1>

        <?php if (empty($applicants_by_job)): ?>
            <div class="no-jobs">
                <i class="fas fa-briefcase"></i>
                <p>No jobs posted yet.</p>
                <a href="post-job.php" class="create-job-btn">
                    <i class="fas fa-plus"></i> Create a Job Posting
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($applicants_by_job as $job_id => $job_data): ?>
                <div class="job-section">
                    <div class="job-header">
                        <div>
                            <h2 class="job-title"><?php echo htmlspecialchars($job_data['role']); ?></h2>
                            <span class="job-meta">Posted on <?php echo date('F j, Y', strtotime($job_data['created_at'])); ?></span>
                        </div>
                        <span class="applicant-count">
                            <?php echo count($job_data['applicants']); ?> Applicant<?php echo count($job_data['applicants']) != 1 ? 's' : ''; ?>
                        </span>
                    </div>

                    <?php if (empty($job_data['applicants'])): ?>
                        <div class="no-applicants">
                            <i class="fas fa-user-slash"></i>
                            <p>No applicants yet for this position</p>
                        </div>
                    <?php else: ?>
                        <div class="applicant-list">
                            <?php foreach ($job_data['applicants'] as $applicant): ?>
                                <div class="applicant">
                                    <div class="applicant-info">
                                        <h3><?php echo htmlspecialchars($applicant['job_seeker_name']); ?></h3>
                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($applicant['job_seeker_email']); ?></p>
                                        <p>
                                            <strong>Status:</strong> 
                                            <span class="status-badge status-<?php echo strtolower($applicant['status']); ?>">
                                                <?php echo ucfirst(htmlspecialchars($applicant['status'])); ?>
                                            </span>
                                        </p>
                                        <p><strong>Applied:</strong> <?php echo date('F j, Y', strtotime($applicant['created_at'])); ?></p>
                                    </div>
                                    <div class="button-group">
                                        <a href="application_status.php?application_id=<?php echo $applicant['application_id']; ?>" class="btn-view">
                                            <i class="fas fa-eye"></i> View Application
                                        </a>
                                        <a href="messages.php?chat_with=<?php echo $applicant['job_seeker_id']; ?>" class="btn-message">
                                            <i class="fas fa-comment"></i> Message
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>