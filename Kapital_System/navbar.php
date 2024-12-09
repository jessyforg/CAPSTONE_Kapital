<!DOCTYPE html>
<html lang="en">

<head>
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

        .cta-buttons {
            display: flex;
            gap: 15px;
        }

        .cta-buttons a {
            background-color: #f3c000;
            color: #000;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .cta-buttons a:hover {
            background-color: #000;
            color: #f3c000;
        }

        /* Responsive Navbar */
        @media (max-width: 768px) {
            header {
                flex-wrap: wrap;
                justify-content: center;
                text-align: center;
            }

            nav ul {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }

            .cta-buttons {
                justify-content: center;
                margin-top: 10px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">Kapital</div>
        <nav>
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="entrepreneurs.php">For Entrepreneurs</a></li>
                <li><a href="investors.php">For Investors</a></li>
                <li><a href="job-seekers.php">For Job Seekers</a></li>
                <li><a href="about-us.php">About Us</a></li>
            </ul>
        </nav>
        <div class="cta-buttons">
            <a href="signin.php">Sign In</a>
            <a href="signup.php">Sign Up</a>
        </div>
    </header>
</body>

</html>