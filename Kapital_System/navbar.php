<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <style>
        /* General Reset */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background-color: #1e1e1e;
            line-height: 1.6;
        }

        /* Header and Navbar */
        header {
            background-color: #000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        header .logo {
            font-size: 1.8em;
            font-weight: bold;
            color: #f3c000;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 30px;
            align-items: center;
        }

        nav ul li {
            display: inline-block;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            font-size: 1em;
            font-weight: 500;
            border-radius: 4px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        nav ul li a.active,
        nav ul li a:hover {
            background-color: #f3c000;
            color: #000;
            border-radius: 5px;
        }

        /* CTA Buttons */
        .cta-buttons a {
            display: inline-block;
            background: linear-gradient(90deg, #f3c000, #ffab00);
            color: #000;
            font-weight: 600;
            padding: 10px 20px;
            margin-left: 10px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .cta-buttons a:hover {
            background: linear-gradient(90deg, #ffab00, #f3c000);
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .cta-buttons a:active {
            transform: scale(0.95);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">Kapital</div>
        <nav>
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <!-- Role-based navigation -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'entrepreneur'): ?>
                    <li><a href="entrepreneurs.php">For Entrepreneurs</a></li>
                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'investor'): ?>
                    <li><a href="investors.php">For Investors</a></li>
                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'job_seeker'): ?>
                    <li><a href="job-seekers.php">For Job Seekers</a></li>
                <?php endif; ?>
                <li><a href="about-us.php">About Us</a></li>
            </ul>
        </nav>
        <div class="cta-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" id="logOutBtn">Log Out</a>
            <?php else: ?>
                <a href="sign_in.php" class="cta-btn">Sign In</a>
                <a href="sign_up.php" class="cta-btn">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>

    <script>
        // Function to set active class on the current page
        function setActiveLink() {
            const currentPage = window.location.pathname;
            const navLinks = document.querySelectorAll('nav ul li a');
            navLinks.forEach(link => {
                if (currentPage.includes(link.getAttribute('href'))) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        // Call the function when the page loads
        window.onload = () => {
            setActiveLink();
        };
    </script>
</body>

</html>