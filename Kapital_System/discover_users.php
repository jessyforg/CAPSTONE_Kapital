<?php
session_start();
include('navbar.php');
include('db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit();
}

// Fetch all users except the current user
$current_user_id = $_SESSION['user_id'];
$query = "SELECT DISTINCT u.*, 
    CASE 
        WHEN u.role = 'investor' THEN i.preferred_industries
        WHEN u.role = 'job_seeker' THEN js.preferred_industries
        WHEN u.role = 'entrepreneur' THEN 
            (SELECT GROUP_CONCAT(DISTINCT s.industry) 
            FROM Startups s 
            WHERE s.entrepreneur_id = u.user_id AND s.approval_status = 'approved')
    END as industries,
    u.industry as primary_industry,
    COALESCE(u.introduction, '') as about_me
FROM Users u
LEFT JOIN Investors i ON u.user_id = i.investor_id
LEFT JOIN Job_Seekers js ON u.user_id = js.job_seeker_id
WHERE u.user_id != ? AND u.verification_status = 'verified'";

if (!empty($search)) {
    $query .= " AND (u.name LIKE ? OR u.introduction LIKE ?)";
}

$query .= " ORDER BY u.name ASC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $current_user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Users - TARAKI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(45deg, #343131, #808080);
            color: #fff;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #D8A25E;
            margin-bottom: 30px;
            font-size: 2.5rem;
        }

        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .user-card {
            background: #2C2F33;
            border: 1px solid #40444B;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .user-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .user-info {
            flex: 1;
        }

        .user-info h3 {
            margin: 0;
            color: #FFFFFF;
            font-size: 1.2em;
            margin-bottom: 5px;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            background: rgba(243, 192, 0, 0.1);
            color: #f3c000;
            border-radius: 15px;
            font-size: 0.9em;
        }

        .user-detail {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #B9BBBE;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .user-detail i {
            color: #f3c000;
            width: 16px;
            text-align: center;
        }

        .user-about {
            color: #DCDDDE;
            font-size: 0.95em;
            line-height: 1.5;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #40444B;
        }

        .user-info h2,
        .user-role,
        .user-bio,
        .view-profile-btn {
            display: none;
        }

        .user-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .action-button {
            flex: 1;
            min-width: 120px;
            background: linear-gradient(45deg, #f3c000, #ffab00);
            color: #000;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 500;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-align: center;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 192, 0, 0.3);
            background: linear-gradient(45deg, #ffab00, #f3c000);
        }

        .action-button i {
            font-size: 1.1em;
        }

        .action-button.message-btn {
            background: linear-gradient(45deg, #7289DA, #5865F2);
            color: #fff;
        }

        .action-button.message-btn:hover {
            background: linear-gradient(45deg, #5865F2, #7289DA);
            box-shadow: 0 5px 15px rgba(114, 137, 218, 0.3);
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
            background: #D8A25E;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info h2 {
            margin: 0;
            font-size: 1.2rem;
            color: #fff;
        }

        .user-role {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            margin-top: 5px;
            background: #D8A25E;
            color: #fff;
        }

        .user-bio {
            margin: 15px 0;
            font-size: 0.9rem;
            color: #ddd;
            line-height: 1.6;
            max-height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            background: rgba(0, 0, 0, 0.1);
            padding: 10px;
            border-radius: 5px;
        }

        .view-profile-btn {
            display: inline-block;
            padding: 8px 20px;
            background: #D8A25E;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
        }

        .view-profile-btn:hover {
            background: #b88c50;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .users-grid {
                grid-template-columns: 1fr;
                padding: 10px;
            }

            h1 {
                font-size: 2rem;
                margin: 20px 0;
            }

            .user-actions {
                flex-direction: column;
            }

            .action-button {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .user-header {
                flex-direction: column;
                text-align: center;
            }

            .user-avatar {
                margin: 0 auto 15px;
            }

            .user-info {
                text-align: center;
            }

            .user-detail {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Discover Users</h1>
        
        <div class="users-grid">
            <?php while ($user = mysqli_fetch_assoc($result)): ?>
                <div class="user-card">
                    <div class="user-header">
                        <div class="user-avatar">
                            <?php if (!empty($user['profile_picture_url'])): ?>
                                <img src="<?php echo htmlspecialchars($user['profile_picture_url']); ?>" alt="Profile Picture">
                            <?php else: ?>
                                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                            <?php endif; ?>
                        </div>
                        <div class="user-info">
                            <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                            <span class="role-badge">
                                <i class="fas <?php
                                    switch($user['role']) {
                                        case 'entrepreneur':
                                            echo 'fa-lightbulb';
                                            break;
                                        case 'investor':
                                            echo 'fa-chart-line';
                                            break;
                                        case 'job_seeker':
                                            echo 'fa-briefcase';
                                            break;
                                        default:
                                            echo 'fa-user';
                                    }
                                ?>"></i>
                                <?php 
                                    $role = $user['role'];
                                    switch($role) {
                                        case 'job_seeker':
                                            echo 'Job Seeker';
                                            break;
                                        default:
                                            echo ucfirst($role);
                                    }
                                ?>
                            </span>
                        </div>
                    </div>
                    
                    <?php if (!empty($user['primary_industry'])): ?>
                    <div class="user-detail">
                        <i class="fas fa-briefcase"></i>
                        <?php echo htmlspecialchars($user['primary_industry']); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($user['location'])): ?>
                    <div class="user-detail">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo htmlspecialchars($user['location']); ?>
                    </div>
                    <?php endif; ?>

                    <?php 
                    if (!empty($user['industries'])) {
                        $industries = is_string($user['industries']) ? 
                            explode(',', $user['industries']) : 
                            json_decode($user['industries'], true);
                        
                        if (!empty($industries)): 
                    ?>
                    <div class="user-detail">
                        <i class="fas fa-industry"></i>
                        <?php 
                        if (is_array($industries)) {
                            echo htmlspecialchars(implode(', ', array_slice($industries, 0, 3)));
                            if (count($industries) > 3) echo '...';
                        } else {
                            echo htmlspecialchars($industries);
                        }
                        ?>
                    </div>
                    <?php 
                        endif;
                    }
                    ?>
                    
                    <?php if (!empty($user['about_me'])): ?>
                    <div class="user-about">
                        <?php 
                        $about = htmlspecialchars($user['about_me']);
                        echo strlen($about) > 150 ? substr($about, 0, 147) . '...' : $about;
                        ?>
                    </div>
                    <?php endif; ?>

                    <div class="user-actions">
                        <a href="profile.php?user_id=<?php echo $user['user_id']; ?>" class="action-button">
                            <i class="fas fa-user"></i> View Profile
                        </a>
                        <a href="messages.php?recipient_id=<?php echo $user['user_id']; ?>" class="action-button message-btn">
                            <i class="fas fa-comments"></i> Message
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html> 