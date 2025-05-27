<?php
session_start();
$investLink = isset($_SESSION['user_id']) ? 'moneyinvest.php' : 'login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome to SafeInvest</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f5f7fb;
        }

        /* Navbar */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #001f3f;
            padding: 10px 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }

        .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .nav-links li {
            display: inline;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .nav-links a:hover {
            background-color: #ff4d4d;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                flex-direction: column;
                width: 100%;
                gap: 10px;
                padding-top: 10px;
            }
        }

        /* Hero Section */
        section.hero {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            background: #f5f7fb;
        }

        .hero-content {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            text-align: center;
        }

        .hero-content h1 {
            font-size: 2.5em;
            color: #0b3d91;
            margin-bottom: 20px;
        }

        .hero-content p {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn {
            background: #0b3d91;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
            display: inline-block;
        }

        .btn:hover {
            background: #072966;
        }

        /* Pricing Section */
        section.ep-pricing-section {
            background-color: white;
            padding: 60px 20px 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
        }

        .col-lg-4 {
            flex: 1 1 30%;
            min-width: 280px;
            background: #e9f0d7;
            border-radius: 30px;
            padding: 25px 30px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.07);
            color:rgb(36, 38, 34);
        }

        .pricing-item h3.title {
            font-weight: 700;
            font-size: 1.7rem;
            margin-bottom: 15px;
            color:rgb(19, 20, 18);
        }

        .price-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .icon-box {
            background:rgb(231, 221, 221);
            padding: 15px;
            border-radius: 20px;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-box img {
            width: 24px;
            height: 24px;
        }

        ul.options {
            margin-top: 30px;
            list-style-type: none;
            padding-left: 0;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        ul.options li span {
            display: block;
        }

        /* Contact Section */
        section.contact-form {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            background: linear-gradient(135deg,rgb(250, 254, 254) 0%,rgb(224, 228, 235) 100%);
            color: #e9f0d7;
        }

        .container.contact-container {
            background: rgba(255 255 255 / 0.15);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            max-width: 900px;
            width: 100%;
            padding: 3rem 4rem;
            color: #e9f0d7;
        }

        .section-title-block {
            margin-bottom: 2rem;
            text-align: left;
        }

        .section-sub-title {
            color:rgb(30, 30, 30);
            font-weight: 600;
            letter-spacing: 1.5px;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .section-title {
            font-size: 2.8rem;
            font-weight: 700;
            color:rgb(22, 22, 21);
            letter-spacing: 1px;
            line-height: 1.2;
        }

        form {
            margin-top: 1rem;
        }

        .row.g-4 {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .col-lg-6 {
            flex: 1 1 45%;
        }

        .col-lg-12 {
            flex: 1 1 100%;
        }

                .vmq-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .vmq-box {
            background: #ecf3fa;
            border-radius: 20px;
            width: 300px;
            padding: 30px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
        }

        .vmq-box:hover {
            border-color: #ff4d4d;
            background-color: #ffffff;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .vmq-title {
            font-size: 24px;
            font-weight: bold;
            color: #001858;
            margin-bottom: 15px;
        }

        .vmq-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background-color: #ff6b6b;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .vmq-icon img {
            width: 30px;
            height: 30px;
        }

        .vmq-text {
            color: #333;
            font-size: 15px;
            line-height: 1.6;
        }

        .input-group {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            background: rgba(255 255 255 / 0.3);
            border: none;
            border-radius: 10px;
            padding: 0.85rem 1rem;
            color:rgb(26, 50, 58);
            font-weight: 600;
            font-size: 1rem;
            outline: none;
            transition: background-color 0.3s ease;
            box-shadow: inset 2px 2px 8px rgba(121, 115, 115, 0.4),
                        inset -2px -2px 8px rgba(0, 0, 0, 0.2);
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        textarea:focus {
            background:rgb(218, 202, 202);
            color:rgb(22, 20, 20);
            box-shadow: 0 0 8px #d4f8a8;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
            font-family: 'Poppins', sans-serif;
        }

        button[type="submit"] {
            margin-top: 2rem;
            background:rgb(40, 7, 226);
            color:rgb(255, 255, 255);
            font-weight: 700;
            padding: 0.85rem 2.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1.15rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(166, 215, 133, 0.7);
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background: #c3e293;
            box-shadow: 0 6px 20px rgba(195, 226, 147, 0.85);
        }
    </style>
</head>
<body>
    <nav class="navbar">
    <div class="logo">
        <img src="https://maharashtravikasgroup.com/writable/uploads/logo.png?v=1.0.2" alt="Company Logo"> <!-- Replace with actual logo path -->
        Maharashtra Vikas Group PVT. LTD
    </div>
    <ul class="nav-links">
        <li><a href="index.php">🏠 Home</a></li>
        <li><a href="register.php">📝 Register</a></li>
        <li><a href="login.php">🔐 Login</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="moneyinvest.php">💲Money Invest</a></li>
            <li><a href="logout.php">🔓 Logout</a></li> <!-- Optional: you can remove this -->
        <?php endif; ?>
    </ul>
</nav>

    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to SafeInvest</h1>
            <p>We help you make your money work smartly by investing it safely in fixed deposits and more.</p>
            <a href="<?php echo $investLink; ?>" class="btn">Start Investing Now</a>
        </div>
    </section>

<div class="vmq-container">
    <!-- Vision -->
    <div class="vmq-box">
        <div class="vmq-title">Vision</div>
        <div class="vmq-icon">
            <img src="https://img.icons8.com/ios-filled/50/cloud-connection.png" alt="vision icon"/>
        </div>
        <div class="vmq-text">
            To be the leading provider of innovative and accessible loan solutions, empowering individuals and businesses to achieve their financial goals. To be the most trusted and customer-centric loan service provider, recognized for our ethical practices and commitment to financial well-being. To create a seamless and personalized loan experience that simplifies access to credit and fosters financial growth.
        </div>
    </div>

    <!-- Mission -->
    <div class="vmq-box">
        <div class="vmq-title">Mission</div>
        <div class="vmq-icon">
            <img src="https://img.icons8.com/ios-filled/50/cloud-network.png" alt="mission icon"/>
        </div>
        <div class="vmq-text">
            We provide responsible and transparent loan services that meet the diverse needs of our customers, while adhering to the highest ethical standards. We empower our customers with the financial tools and knowledge they need to make informed decisions and build a secure future. We leverage technology and innovation to deliver efficient and convenient loan solutions that exceed customer expectations.
        </div>
    </div>

    <!-- Quality -->
    <div class="vmq-box">
        <div class="vmq-title">Quality</div>
        <div class="vmq-icon">
            <img src="https://img.icons8.com/ios-filled/50/robot.png" alt="quality icon"/>
        </div>
        <div class="vmq-text">
            We are committed to providing exceptional customer service, ensuring every interaction is professional, respectful, and solution-oriented. We strive for continuous improvement in our processes and services, seeking feedback and adapting to the evolving needs of our customers. We maintain the highest standards of compliance and security, protecting customer data and ensuring the integrity of our loan operations.
        </div>
    </div>
</div>

    <section class="contact-form" id="contact-us">
        <div class="container contact-container">
            <div class="section-title-block">
                <h6 class="section-sub-title">Get In Touch</h6>
                <h2 class="section-title">Contact Us</h2>
            </div>
            <form method="POST" action="send_contact.php">
                <div class="row g-4">
                    <div class="col-lg-6 input-group">
                        <input type="text" id="fullname" name="fullname" placeholder="Full Name" required />
                    </div>
                    <div class="col-lg-6 input-group">
                        <input type="email" id="email" name="email" placeholder="Email" required />
                    </div>
                    <div class="col-lg-12 input-group">
                        <textarea id="message" name="message" placeholder="Message" required></textarea>
                    </div>
                    <div class="col-lg-12 input-group">
                        <button type="submit">Send Message</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- footer.php -->
<footer style="background: #111; color: #fff; padding: 50px 20px; font-family: 'Segoe UI', sans-serif;">
    <div style="max-width: 1200px; margin: auto; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start;">
        <!-- Logo -->
        <div style="flex: 1; min-width: 200px; text-align: center;">
            <img src="https://maharashtravikasgroup.com/writable/uploads/logo.png?v=1.0.2" alt="Maharashtra Vikas" style="width: 150px; border-radius: 50%;">
        </div>

        <!-- Main Menu -->
        <div style="flex: 1; min-width: 200px;">
            <h3>Main Menu</h3>
            <ul style="list-style: none; padding: 0;">
                <li><a href="index.php" style="color: #ccc; text-decoration: none;">🏠 Home</a></li>
                <li><a href="register.php" style="color: #ccc; text-decoration: none;">📝 Register</a></li>
                <li><a href="login.php" style="color: #ccc; text-decoration: none;">🔐 Login</a></li>
                <li><a href="moneyinvest.php" style="color: #ccc; text-decoration: none;">💲Money Invest</a></li>
                <li><a href="logout.php" style="color: #ccc; text-decoration: none;">🔓 Logout</a></li>
            </ul>
        </div>

        <!-- Address -->
        <div style="flex: 1; min-width: 200px;">
            <h3><span style="color: red;">📍</span> Address</h3>
            <p>
                Maharashtra Vikas Group Pvt Ltd<br>
                Second Floor, Indreshwar Complex,<br>
                Gore Hospital Building, Near Kardile Hospital,<br>
                Juna Kacheri Road, Indapur.
            </p>
            <h3><span style="color: red;">📍</span> Head Office</h3>
            <p>
                Dr Ambedkar Rd, Pali Pathar, Bandra West,<br>
                Mumbai, Maharashtra 400050
            </p>
        </div>

        <!-- Contact -->
        <div style="flex: 1; min-width: 200px;">
            <h3><span style="color: red;">📞</span> Phone Number</h3>
            <p>+91 9373111675</p>
            <h3><span style="color: red;">📧</span> Email</h3>
            <p>Mahashtravikasgroup111@gmail.com</p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 40px; color: #aaa;">
        <p>
            Copyright © 2025 Maharashtra Vikas Group. Crafted with ❤️ by Krushnajeet Shinde
        </p>
    </div>

    <!-- Scroll to top button -->
    <a href="#" style="position: fixed; bottom: 20px; right: 20px; background: red; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 20px;">⬆</a>
</footer>

</body>
</html>
