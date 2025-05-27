<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid Email or Password!";
        }
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Maharashtra Vikas Group PVT.LTD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://th.bing.com/th/id/OIP.g9H1rKmKew4c7-3V8pSr6QHaE7?w=265&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.65);
            z-index: -1;
        }

        .navbar {
            background-color: rgba(227, 21, 73, 0.9);
            backdrop-filter: blur(10px);
        }

        .navbar .navbar-brand {
            color: white;
            font-weight: bold;
            font-size: 20px;
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #ffdfba !important;
        }

        .login-box {
            max-width: 400px;
            margin: 100px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 20px;
            backdrop-filter: blur(15px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            color: #fff;
            animation: slideIn 1s ease forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: #fff;
        }

        .form-control::placeholder {
            color: #f0f0f0;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.3);
            color: #000;
            box-shadow: none;
        }

        .form-group i {
            position: absolute;
            top: 13px;
            left: 12px;
            color: #ccc;
        }

        .form-group {
            position: relative;
        }

        .form-group input {
            padding-left: 35px;
        }

        .btn-custom {
            background: #28a745;
            font-weight: bold;
            width: 100%;
            transition: background 0.3s ease;
        }

        .btn-custom:hover {
            background: #218838;
        }

        .alert {
            background-color: rgba(255, 0, 0, 0.6);
            border: none;
            color: white;
        }

        a {
            color: #ffffff;
            text-decoration: none;
        }

        a:hover {
            color: #ffc107;
            text-decoration: underline;
        }

        .footer-links {
            text-align: center;
            margin-top: 15px;
            font-size: 15px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg px-4">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
    <img src="https://maharashtravikasgroup.com/writable/uploads/logo.png?v=1.0.2" alt="Company Logo" style="height: 40px; margin-right: 10px;">
    Maharashtra Vikas Group PVT.LTD
    </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">🏠 Home</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">📝 Register</a></li>
                <li class="nav-item"><a class="nav-link active" href="login.php">🔐 Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Login Form -->
<div class="login-box">
    <h3 class="text-center mb-4">Login to Your Account</h3>
    <?php if (!empty($error)) echo "<div class='alert text-center'>$error</div>"; ?>
    <form method="POST">
        <div class="form-group mb-3">
            <i class="fa fa-envelope"></i>
            <input type="email" name="email" class="form-control" placeholder="Email address" required>
        </div>
        <div class="form-group mb-4">
            <i class="fa fa-lock"></i>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" name="login" class="btn btn-custom">Login</button>
    </form>
    <div class="footer-links mt-3">
        <p>Don't have an account? <a href="register.php">Register Here</a></p>
        <p>Are you an Admin? <a href="admin-login.php">Login Here</a></p>
    </div>
</div>

</body>
</html>
