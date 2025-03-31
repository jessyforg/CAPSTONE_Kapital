<?php
session_start(); // Start session to check login status
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>

    <!-- Integrated CSS -->
    <style>
        /* General Reset */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background-color: #1e1e1e;
            line-height: 1.6;
        }

        /* Hero Section */
        .hero-section {
            text-align: center;
            padding: 100px 20px;
            background: url('imgs/bg.png') no-repeat center center/cover;
            color: #fff;
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Overlay for better text visibility */
        .hero-section::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        /* Heading Styling */
        .hero-section h1 {
            font-size: 3.5em;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
            max-width: 700px;
        }

        /* Paragraph Styling */
        .hero-section p {
            font-size: 1.5em;
            margin-bottom: 30px;
            max-width: 700px;
            line-height: 1.6;
            text-align: center;
        }

        /* Button Styling */
        .hero-section .btn {
            background-color: #f3c000;
            color: #000;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 1.2em;
            font-weight: 600;
            transition: background-color 0.3s ease, color 0.3s ease;
            display: inline-block;
        }

        /* Button Hover Effects */
        .hero-section .btn:hover {
            background-color: #000;
            color: #f3c000;
        }

        /* Features Section Inside Hero */
        .features {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        /* Feature Card Styling */
        .feature-card {
            background-color: #333;
            padding: 30px;
            border-radius: 10px;
            width: 250px;
            text-align: center;
            transition: transform 0.3s ease, background-color 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .feature-card h3 {
            font-size: 1.5em;
            color: #f3c000;
            margin-bottom: 15px;
        }

        .feature-card p {
            font-size: 1em;
            color: #fff;
        }

        /* Hover Effect for Feature Cards */
        .feature-card:hover {
            transform: translateY(-10px);
            color: #000;
        }

        /* Analytics Section */
        .analytics-section {
            background-color: #23272A;
            padding: 40px 20px;
            text-align: center;
        }

        .analytics-section h2 {
            color: #f3c000;
            margin-bottom: 20px;
        }

        .analytics-cards {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .analytics-card {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            width: 200px;
            color: #fff;
            transition: transform 0.3s ease;
        }

        .analytics-card h3 {
            font-size: 2em;
            margin: 0;
        }

        .analytics-card p {
            margin: 5px 0 0;
        }

        .analytics-card:hover {
            transform: translateY(-5px);
        }

        /* Responsive Design for Mobile */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5em;
            }

            .hero-section p {
                font-size: 1.2em;
                padding: 0 20px;
            }

            .hero-section .btn {
                font-size: 1.1em;
                padding: 10px 25px;
            }

            .features {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section with Features Cards -->
    <section class="hero-section">
        <div class="container">
            <!-- Hero Text -->
            <h1>Your Path To Financial Freedom Starts Here.</h1>
            <p>Empowering Entrepreneurs, Investors, and Job Seekers.</p>
            <a href="sign_up.php" class="btn">Join Us</a>

            <!-- Analytics Section -->
            <section class="analytics-section">
                <div class="container">
                    <h2>Success Stories</h2>
                    <div class="analytics-cards">
                        <div class="analytics-card">
                            <h3><?php 
                                // Fetch analytics data
                                include 'db_connection.php';
                                $active_startups = 0; 
                                $matched_investors = 0; 
                                $hired_job_seekers = 0; 

                                // Query to get the number of active startups
                                $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM Startups");
                                if ($row = mysqli_fetch_assoc($result)) {
                                    $active_startups = $row['count'];
                                }
                                echo $active_startups; 
                            ?></h3>
                            <p>Active Startups</p>
                        </div>
                        <div class="analytics-card">
                            <h3><?php 
                                // Query to get the number of matched investors (unique investors who have matches)
                                $result = mysqli_query($conn, "SELECT COUNT(DISTINCT investor_id) as count FROM Matches");
                                if ($row = mysqli_fetch_assoc($result)) {
                                    $matched_investors = $row['count'];
                                }
                                echo $matched_investors; 
                            ?></h3>
                            <p>Matched Investors</p>
                        </div>

                        <div class="analytics-card">
                            <h3><?php 
                                // Query to get the number of hired job seekers
                                $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM job_seekers");

                                if ($row = mysqli_fetch_assoc($result)) {
                                    $hired_job_seekers = $row['count'];
                                }
                                echo $hired_job_seekers; 
                            ?></h3>
                            <p>Hired Job Seekers</p>
                        </div>

                    </div>
                </div>
            </section>

            <!-- Features Cards -->
            <div class="features">
                <div class="feature-card">
                    <h3>Post Your Project</h3>
                    <p>Share your project description and attract interest.</p>
                </div>
                <div class="feature-card">
                    <h3>Showcase Your Prototype</h3>
                    <p>Display your immersive prototypes to engage investors.</p>
                </div>
                <div class="feature-card">
                    <h3>Connect With Investors</h3>
                    <p>Network with potential backers who believe in your vision.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- Include Footer (if any) 
    <?php include 'footer.php'; ?>
    -->

</body>

</html>
