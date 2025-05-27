<?php
session_start();
include "config.php"; // Make sure this file contains your DB connection

if (isset($_SESSION['admin_id'])) {
    header("Location: admin_panel.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $result = $conn->query("SELECT * FROM admin WHERE email = '$email' AND password = '$password'");
    if ($result->num_rows > 0) {
        $_SESSION['admin_id'] = $email;
        header("Location: admin_panel.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login</title>

    <!-- Font Awesome CDN -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      integrity="sha512-papbXGkz+qN+72A9P3CUcwR+RWfW2U84VdKw9A9K6q4x+EG2u+O8Gqjp1o3/TwDh4RVp6x7Q4ewq0gZ0jq3pGw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: url('https://th.bing.com/th/id/OIP.g9H1rKmKew4c7-3V8pSr6QHaE7?w=265&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            z-index: 0;
        }

        /* 🌟 Navigation Bar Styling */
        .navbar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(239, 57, 57, 0.7);
            padding: 10px 30px;
            z-index: 2;
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
            width: 40px;
            margin-right: 10px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .logo span {
            color: #fff;
            font-size: 20px;
            font-weight: 600;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .nav-links li a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s ease;
        }

        .nav-links li a:hover {
            color: #ff7eb3;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
            position: relative;
            z-index: 1;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h2 {
            margin-bottom: 20px;
            color: #fff;
            font-weight: 600;
        }

        /* Input container for icon + input */
        .input-container {
            position: relative;
            margin: 10px 0;
        }

        .input-container input {
            width: 100%;
            padding: 12px 40px 12px 40px; /* left padding for icon */
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.3);
            font-size: 16px;
            color: white;
            transition: 0.3s;
        }
        .input-container input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .input-container input:focus {
            background: rgba(255, 255, 255, 0.5);
            outline: none;
        }

        /* Icon inside input */
        .input-container .icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: white;
            pointer-events: none;
            user-select: none;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(90deg, #ff758c, #ff7eb3);
            color: white;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }
        .login-btn:hover {
            background: linear-gradient(90deg, #ff5c72, #ff6a99);
        }
        .error {
            color: #ff4d4d;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 500;
        }
        .form-footer {
            margin-top: 15px;
            font-size: 14px;
        }
        .form-footer a {
            color: #ff758c;
            text-decoration: none;
            font-weight: 600;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- 🌟 Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-content">
            <div class="logo">
                <img src="https://maharashtravikasgroup.com/writable/uploads/logo.png?v=1.0.2" alt="Logo" />
                <span>Maharashtra Vikas Group PVT.LTD</span>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">🏠 Home</a></li>
                <li><a href="register.php">📝 Register</a></li>
                <li><a href="login.php">🔐 Login</a></li>
            </ul>
        </div>
    </nav>

    <div class="login-container">
        <h2>Admin Log in</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" autocomplete="off">
            <div class="input-container">
                <i class="fa fa-envelope icon"></i>
                <input type="email" name="email" placeholder="Admin Email" required />
            </div>
            <div class="input-container">
                <i class="fa fa-lock icon"></i>
                <input type="password" name="password" placeholder="Admin Password" required />
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>
