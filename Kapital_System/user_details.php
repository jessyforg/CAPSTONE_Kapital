<?php
// Include database connection
include 'db_connection.php';
session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit("Redirecting to login page...");
}

// Check if user is admin
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Get user_id from URL or use logged-in user's ID
$viewing_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];
$is_own_profile = $viewing_user_id == $_SESSION['user_id'];

// Fetch user details
$user_query = "SELECT * FROM Users WHERE user_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $viewing_user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Fetch verification documents
$documents_query = "SELECT * FROM Verification_Documents WHERE user_id = ? ORDER BY uploaded_at DESC";
$stmt = $conn->prepare($documents_query);
$stmt->bind_param("i", $viewing_user_id);
$stmt->execute();
$documents_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(243, 192, 0, 0.2);
        }

        h1 {
            color: #f3c000;
            margin-bottom: 20px;
        }

        .user-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
        }

        .info-item h3 {
            color: #f3c000;
            margin: 0 0 10px 0;
            font-size: 1.1em;
        }

        .info-item p {
            margin: 0;
            color: #fff;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            margin-top: 5px;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }

        .status-verified {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .status-not-approved {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .documents-list {
            margin-top: 20px;
        }

        .document-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .document-info {
            flex-grow: 1;
        }

        .document-preview {
            max-width: 200px;
            max-height: 150px;
            object-fit: contain;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .document-preview:hover {
            transform: scale(1.05);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-primary {
            background: #f3c000;
            color: #000;
        }

        .btn-danger {
            background: #dc3545;
            color: #fff;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            overflow: auto;
        }

        .modal-content {
            position: relative;
            margin: auto;
            padding: 20px;
            width: 90%;
            max-width: 1200px;
            height: 90vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .modal-image {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-modal:hover {
            color: #f3c000;
        }

        .zoom-controls {
            position: absolute;
            bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .zoom-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .zoom-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="profile-section">
            <h1>User Details</h1>
            
            <div class="user-info">
                <div class="info-item">
                    <h3>Basic Information</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Role:</strong> <?php echo ucfirst(htmlspecialchars($user['role'])); ?></p>
                    <p><strong>Joined:</strong> <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                </div>

                <div class="info-item">
                    <h3>Verification Status</h3>
                    <p>
                        <span class="status-badge status-<?php echo strtolower($user['verification_status']); ?>">
                            <?php echo ucfirst($user['verification_status']); ?>
                        </span>
                    </p>
                </div>
            </div>

            <?php if ($is_admin): ?>
                <div class="documents-list">
                    <h2>Verification Documents</h2>
                    <?php if ($documents_result->num_rows > 0): ?>
                        <?php while ($document = $documents_result->fetch_assoc()): ?>
                            <div class="document-item">
                                <div class="document-info">
                                    <strong><?php echo ucwords(str_replace('_', ' ', $document['document_type'])); ?></strong>
                                    <br>
                                    <small>Uploaded: <?php echo date('F j, Y g:i A', strtotime($document['uploaded_at'])); ?></small>
                                    <?php if ($document['status'] === 'not approved' && $document['rejection_reason']): ?>
                                        <br>
                                        <small style="color: #dc3545;">Rejection Reason: <?php echo htmlspecialchars($document['rejection_reason']); ?></small>
                                    <?php endif; ?>
                                    <br>
                                    <img src="<?php echo htmlspecialchars($document['file_path']); ?>" 
                                         alt="Document Preview" 
                                         class="document-preview"
                                         onclick="openModal(this.src, '<?php echo htmlspecialchars($document['document_type']); ?>')">
                                </div>
                                <div class="document-status status-<?php echo strtolower($document['status']); ?>">
                                    <?php echo ucfirst($document['status']); ?>
                                </div>
                            </div>
                            <?php if ($document['status'] === 'pending'): ?>
                                <div class="action-buttons">
                                    <form action="process_verification.php" method="post" style="display: inline;">
                                        <input type="hidden" name="document_id" value="<?php echo $document['document_id']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-primary">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No verification documents uploaded yet.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal for document preview -->
    <div id="documentModal" class="modal">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImage" class="modal-image" src="" alt="Document Preview">
            <div class="zoom-controls">
                <button class="zoom-btn" onclick="zoomIn()"><i class="fas fa-search-plus"></i> Zoom In</button>
                <button class="zoom-btn" onclick="zoomOut()"><i class="fas fa-search-minus"></i> Zoom Out</button>
            </div>
        </div>
    </div>

    <script>
        let currentZoom = 1;
        const modal = document.getElementById('documentModal');
        const modalImage = document.getElementById('modalImage');

        function openModal(imageSrc, documentType) {
            modal.style.display = "block";
            modalImage.src = imageSrc;
            currentZoom = 1;
            modalImage.style.transform = `scale(${currentZoom})`;
        }

        function closeModal() {
            modal.style.display = "none";
            currentZoom = 1;
            modalImage.style.transform = `scale(${currentZoom})`;
        }

        function zoomIn() {
            currentZoom += 0.2;
            modalImage.style.transform = `scale(${currentZoom})`;
        }

        function zoomOut() {
            if (currentZoom > 0.4) {
                currentZoom -= 0.2;
                modalImage.style.transform = `scale(${currentZoom})`;
            }
        }

        // Close modal when clicking outside the image
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html> 