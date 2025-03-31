<?php
// Include database connection
include 'db_connection.php';

// Start session
session_start();

// Check if the logged-in user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Fetch pending startups
$startup_query = "SELECT s.startup_id, s.name, s.industry, s.description, u.name AS entrepreneur_name
                  FROM Startups s
                  JOIN Entrepreneurs e ON s.entrepreneur_id = e.entrepreneur_id
                  JOIN Users u ON e.entrepreneur_id = u.user_id
                  WHERE s.approval_status = 'pending'";
$startup_result = mysqli_query($conn, $startup_query);

// Fetch pending verifications
$verification_query = "SELECT vd.*, u.name AS user_name, u.email, u.role
                      FROM Verification_Documents vd
                      JOIN Users u ON vd.user_id = u.user_id
                      WHERE vd.status = 'pending'
                      ORDER BY vd.uploaded_at DESC";
$verification_result = mysqli_query($conn, $verification_query);

// Fetch all users
$users_query = "SELECT * FROM Users ORDER BY created_at DESC";
$users_result = mysqli_query($conn, $users_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
            margin: 40px auto;
            background: #23272A;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #7289DA;
            margin-bottom: 20px;
        }

        /* Tab Styles */
        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 2px solid #40444B;
        }

        .tab {
            padding: 15px 30px;
            cursor: pointer;
            color: #7289DA;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .tab.active {
            color: #fff;
            border-bottom: 2px solid #7289DA;
            margin-bottom: -2px;
        }

        .tab:hover {
            color: #fff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #40444B;
        }

        th {
            background-color: #2C2F33;
            color: #7289DA;
        }

        tr:hover {
            background-color: #2C2F33;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            margin: 2px;
            transition: all 0.3s ease;
        }

        .approve {
            background-color: #4CAF50;
            color: #fff;
        }

        .reject {
            background-color: #F44336;
            color: #fff;
        }

        .view-details {
            background-color: #3F51B5;
            color: #fff;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #FFA726;
            color: #fff;
        }

        .status-verified {
            background-color: #4CAF50;
            color: #fff;
        }

        .status-rejected {
            background-color: #F44336;
            color: #fff;
        }

        .document-preview {
            max-width: 200px;
            max-height: 150px;
            object-fit: contain;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Admin Panel</h1>

        <div class="tabs">
            <div class="tab active" onclick="openTab(event, 'startups')">Startup Applications</div>
            <div class="tab" onclick="openTab(event, 'verifications')">User Verifications</div>
            <div class="tab" onclick="openTab(event, 'users')">Users List</div>
        </div>

        <!-- Startup Applications Tab -->
        <div id="startups" class="tab-content active">
            <h2>Pending Startup Applications</h2>
            <?php if (mysqli_num_rows($startup_result) > 0): ?>
                <?php while ($startup = mysqli_fetch_assoc($startup_result)): ?>
                    <div class="startup">
                        <h2><?php echo htmlspecialchars($startup['name']); ?></h2>
                        <p><strong>Industry:</strong> <?php echo htmlspecialchars($startup['industry']); ?></p>
                        <p><strong>Entrepreneur:</strong> <?php echo htmlspecialchars($startup['entrepreneur_name']); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($startup['description'])); ?></p>

                        <form action="process-startup.php" method="post" style="display: inline;">
                            <input type="hidden" name="startup_id" value="<?php echo $startup['startup_id']; ?>">
                            <button type="submit" name="action" value="approve" class="btn approve">Approve</button>
                        </form>
                        <form action="process-startup.php" method="post" style="display: inline;">
                            <input type="hidden" name="startup_id" value="<?php echo $startup['startup_id']; ?>">
                            <button type="submit" name="action" value="not_approved" class="btn reject">Not Approved</button>
                        </form>
                        <button class="btn view-details">
                            <a href="startup_detail.php?startup_id=<?php echo $startup['startup_id']; ?>">View Details</a>
                        </button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No pending startups to review.</p>
            <?php endif; ?>
        </div>

        <!-- User Verifications Tab -->
        <div id="verifications" class="tab-content">
            <h2>Pending User Verifications</h2>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Document Type</th>
                        <th>Preview</th>
                        <th>Uploaded</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($verification = mysqli_fetch_assoc($verification_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($verification['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($verification['role']); ?></td>
                            <td><?php echo htmlspecialchars($verification['document_type']); ?></td>
                            <td>
                                <img src="<?php echo htmlspecialchars($verification['file_path']); ?>" 
                                     alt="Document Preview" class="document-preview">
                            </td>
                            <td><?php echo date('M j, Y', strtotime($verification['uploaded_at'])); ?></td>
                            <td>
                                <form action="process_verification.php" method="post" style="display: inline;">
                                    <input type="hidden" name="document_id" value="<?php echo $verification['document_id']; ?>">
                                    <button type="submit" name="action" value="approve" class="btn approve">Approve</button>
                                    <button type="submit" name="action" value="reject" class="btn reject">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Users List Tab -->
        <div id="users" class="tab-content">
            <h2>All Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Verification Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $user['verification_status']; ?>">
                                    <?php echo ucfirst($user['verification_status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <button class="btn view-details">
                                    <a href="user_details.php?user_id=<?php echo $user['user_id']; ?>">View Details</a>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            tablinks = document.getElementsByClassName("tab");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
</body>

</html>
