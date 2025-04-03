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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            overflow-y: auto;
        }

        .modal-content {
            background-color: #2C2F33;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .close-modal {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            cursor: pointer;
            color: #f3c000;
            transition: color 0.3s ease;
        }

        .close-modal:hover {
            color: #ffab00;
        }

        .document-details {
            margin-top: 20px;
        }

        .document-details p {
            margin: 10px 0;
            line-height: 1.6;
        }

        .document-details strong {
            color: #f3c000;
            margin-right: 10px;
        }

        .document-preview-container {
            margin: 20px 0;
            text-align: center;
        }

        .document-preview-container img {
            max-width: 100%;
            max-height: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .document-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .rejection-reason {
            margin-top: 20px;
        }

        .rejection-reason textarea {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            background-color: #23272A;
            border: 1px solid #40444B;
            color: #fff;
            margin-top: 10px;
            resize: vertical;
            min-height: 100px;
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
                            <td><?php 
                                $role = htmlspecialchars($verification['role']);
                                switch($role) {
                                    case 'entrepreneur':
                                        echo 'Entrepreneur';
                                        break;
                                    case 'job_seeker':
                                        echo 'Job Seeker';
                                        break;
                                    case 'investor':
                                        echo 'Investor';
                                        break;
                                    case 'admin':
                                        echo 'Admin';
                                        break;
                                    default:
                                        echo ucfirst($role);
                                }
                            ?></td>
                            <td><?php echo ucwords(str_replace('_', ' ', $verification['document_type'])); ?></td>
                            <td>
                                <img src="<?php echo htmlspecialchars($verification['file_path']); ?>" 
                                     alt="Document Preview" class="document-preview">
                            </td>
                            <td><?php echo date('M j, Y', strtotime($verification['uploaded_at'])); ?></td>
                            <td>
                                <button class="btn view-details" onclick="openVerificationModal(<?php echo htmlspecialchars(json_encode($verification)); ?>)">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Verification Details Modal -->
        <div id="verificationModal" class="modal">
            <div class="modal-content">
                <span class="close-modal" onclick="closeVerificationModal()">&times;</span>
                <h2>Verification Details</h2>
                <div class="document-details">
                    <p><strong>User Name:</strong> <span id="modal-user-name"></span></p>
                    <p><strong>Email:</strong> <span id="modal-email"></span></p>
                    <p><strong>Role:</strong> <span id="modal-role"></span></p>
                    <p><strong>Document Type:</strong> <span id="modal-document-type"></span></p>
                    <p><strong>Document Number:</strong> <span id="modal-document-number"></span></p>
                    <p><strong>Issue Date:</strong> <span id="modal-issue-date"></span></p>
                    <p><strong>Expiry Date:</strong> <span id="modal-expiry-date"></span></p>
                    <p><strong>Issuing Authority:</strong> <span id="modal-issuing-authority"></span></p>
                    <p><strong>Uploaded:</strong> <span id="modal-uploaded"></span></p>
                    <div class="document-preview-container">
                        <img id="modal-document-preview" src="" alt="Document Preview">
                    </div>
                    <form action="process_verification.php" method="post" id="verification-form">
                        <input type="hidden" name="document_id" id="modal-document-id">
                        <div class="rejection-reason">
                            <label for="rejection_reason">Rejection Reason (if applicable):</label>
                            <textarea name="rejection_reason" id="rejection_reason"></textarea>
                        </div>
                        <div class="document-actions">
                            <button type="submit" name="action" value="approve" class="btn approve">Approve</button>
                            <button type="submit" name="action" value="reject" class="btn reject">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
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
                            <td><?php 
                                $role = htmlspecialchars($user['role']);
                                switch($role) {
                                    case 'entrepreneur':
                                        echo 'Entrepreneur';
                                        break;
                                    case 'job_seeker':
                                        echo 'Job Seeker';
                                        break;
                                    case 'investor':
                                        echo 'Investor';
                                        break;
                                    case 'admin':
                                        echo 'Admin';
                                        break;
                                    default:
                                        echo ucfirst($role);
                                }
                            ?></td>
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

        function openVerificationModal(verification) {
            document.getElementById('modal-user-name').textContent = verification.user_name;
            document.getElementById('modal-email').textContent = verification.email;
            document.getElementById('modal-role').textContent = verification.role;
            document.getElementById('modal-document-type').textContent = verification.document_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            document.getElementById('modal-document-number').textContent = verification.document_number || 'N/A';
            document.getElementById('modal-issue-date').textContent = verification.issue_date || 'N/A';
            document.getElementById('modal-expiry-date').textContent = verification.expiry_date || 'N/A';
            document.getElementById('modal-issuing-authority').textContent = verification.issuing_authority || 'N/A';
            document.getElementById('modal-uploaded').textContent = new Date(verification.uploaded_at).toLocaleDateString();
            document.getElementById('modal-document-preview').src = verification.file_path;
            document.getElementById('modal-document-id').value = verification.document_id;
            
            document.getElementById('verificationModal').style.display = 'block';
        }

        function closeVerificationModal() {
            document.getElementById('verificationModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('verificationModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>

</html>
