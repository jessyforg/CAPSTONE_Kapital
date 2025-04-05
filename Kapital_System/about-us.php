<?php
$page = 'about';
include('navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Kapital</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .hero-section {
            text-align: center;
            padding: 60px 0;
            background: linear-gradient(135deg, rgba(243, 192, 0, 0.1), rgba(255, 171, 0, 0.1));
            border-radius: 20px;
            margin-bottom: 60px;
        }

        .hero-section h1 {
            font-size: 3em;
            color: #f3c000;
            margin-bottom: 20px;
        }

        .kapital-section {
            margin-bottom: 60px;
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
        }

        .kapital-section h2 {
            color: #f3c000;
            margin-bottom: 20px;
        }

        .features-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .feature-item {
            background: rgba(243, 192, 0, 0.1);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid rgba(243, 192, 0, 0.2);
        }

        .feature-item h3 {
            color: #f3c000;
            margin-bottom: 10px;
        }

        .team-section {
            margin-bottom: 60px;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .team-member {
            text-align: center;
        }

        .member-photo {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            margin: 0 auto 20px;
            border: 3px solid #f3c000;
            overflow: hidden;
            background-color: rgba(243, 192, 0, 0.1);
        }

        .member-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .member-info h3 {
            color: #f3c000;
            margin-bottom: 10px;
        }

        .social-link {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .social-link:hover {
            color: #f3c000;
        }

        .taraki-section {
            margin-bottom: 60px;
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
        }

        .taraki-logo {
            width: auto;
            height: 100px;
            margin: 0 auto 20px;
            display: block;
        }

        .taraki-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .taraki-title h2 {
            font-size: 40px;
            line-height: 1;
            margin: 0;
            color: #f3c000;
        }

        .taraki-title img {
            height: 45px;
            width: auto;
        }

        .social-media {
            margin-top: 40px;
            text-align: center;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .social-icons a {
            color: #fff;
            font-size: 24px;
            transition: color 0.3s ease;
        }

        .social-icons a:hover {
            color: #f3c000;
        }

        .contact-info {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            background: rgba(243, 192, 0, 0.1);
            border-radius: 10px;
        }

        .contact-info p {
            margin: 10px 0;
        }

        .contact-info i {
            color: #f3c000;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero-section">
            <div class="section-title">
                <h1>About</h1>
                <img src="imgs/logo.png" alt="TARAKI" style="height: 60px; width: auto; margin-left: 15px;">
            </div>
            <p>Empowering Innovation in the Cordillera Region</p>
        </div>

        <div class="kapital-section">
            <h2>What is Kapital?</h2>
            <p>Kapital is an innovative startup ecosystem platform designed to connect entrepreneurs, investors, and job seekers in the Cordillera region. Our platform serves as a bridge between ambitious startups and potential investors, while also creating opportunities for talented individuals seeking employment in the startup sector.</p>
            
            <div class="features-list">
                <div class="feature-item">
                    <h3>Startup Showcase</h3>
                    <p>Platform for entrepreneurs to showcase their innovative startups and connect with potential investors.</p>
                </div>
                <div class="feature-item">
                    <h3>Investment Matching</h3>
                    <p>Connecting startups with investors interested in supporting regional innovation and growth.</p>
                </div>
                <div class="feature-item">
                    <h3>Job Opportunities</h3>
                    <p>Creating employment opportunities within the startup ecosystem for local talent.</p>
                </div>
                <div class="feature-item">
                    <h3>Verification System</h3>
                    <p>Robust verification process ensuring trust and credibility within the platform.</p>
                </div>
            </div>
        </div>

        <div class="team-section">
            <h2>Meet Our Team</h2>
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-photo">
                        <img src="imgs/troy.jpg" alt="Troy L. Ayson">
                    </div>
                    <div class="member-info">
                        <h3>Troy L. Ayson</h3>
                        <a href="https://www.facebook.com/troyxayson" class="social-link" target="_blank">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-photo">
                        <img src="imgs/eug.jpg" alt="Eugene Jherico P. Naval">
                    </div>
                    <div class="member-info">
                        <h3>Eugene Jherico P. Naval</h3>
                        <a href="https://www.facebook.com/eugenejericho.naval" class="social-link" target="_blank">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-photo">
                        <img src="imgs/jes.jpg" alt="Jester A. Perez">
                    </div>
                    <div class="member-info">
                        <h3>Jester A. Perez</h3>
                        <a href="https://www.facebook.com/jstrprz/" class="social-link" target="_blank">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="taraki-section">
            <div class="taraki-title">
                <h2>About</h2>
                <img src="imgs/logo.png" alt="TARAKI">
            </div>
            <p>Across the Philippines, there are 19 total consortia (as of 2024) funded by DOST-PCIEERD (Department of Science and Technology-Philippine Council for Industry, Energy and Emerging Technology Research and Development) under the HeIRIT-ReSEED (Higher Education Institution Readiness for Innovation and Technopreneurship-Regional Startup Enablers for Ecosystem Development) Program.</p>
            
            <p>TARAKI-CAR (Technological Consortium for Awareness, Readiness, and Advancement of Knowledge in Innovation-Cordillera Administrative Region) is the startup consortium in the Cordillera Region which started on January 3, 2022. This is being led by the University of the Cordilleras with the regional DOST (Department of Science and Technology), DICT (Department of Information and Communications Technology), DTI (Department of Trade and Industry), and TESDA-CSITE offices; including Technology Business Incubators such as UPB SILBI TBI and UC InTTO.</p>

            <p>The consortium spearheads the development and formalization of the startup ecosystem, acting as a bridge to connect stakeholders. It engages partners and innovators across the Cordillera region to nurture and support startups, making them attractive to investors and government funding opportunities.</p>

            <div class="social-media">
                <h3>Connect with TARAKI</h3>
                <div class="social-icons">
                    <a href="https://taraki.vercel.app" target="_blank" title="TARAKI Website">
                        <i class="fas fa-globe"></i>
                    </a>
                    <a href="https://www.facebook.com/tarakicar" target="_blank" title="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/taraki-car/?originalSubdomain=ph" target="_blank" title="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="https://www.instagram.com/tarakicar/" target="_blank" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="contact-info">
            <h3>Contact Us</h3>
            <p><i class="fas fa-envelope"></i> startup.kapital@gmail.com</p>
        </div>
    </div>
</body>
</html>
