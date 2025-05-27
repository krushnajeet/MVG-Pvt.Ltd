<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $invest = $_POST['invest'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (fullname, email, phone, gender, invest, age, address, password)
            VALUES ('$fullname', '$email', '$phone', '$gender', '$invest', '$age', '$address', '$password')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registration Successful'); window.location='login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | Maharashtra Vikas Group Pvt. Ltd</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('https://th.bing.com/th/id/OIP.g9H1rKmKew4c7-3V8pSr6QHaE7?w=265&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7') no-repeat center center fixed;
            background-size: cover;
            overflow-x: hidden;
        }
        .navbar {
            background-color: red;
            display: flex;
            align-items: center;
            padding: 10px 30px;
            color: #fff;
        }
        .navbar img {
            height: 50px;
            margin-right: 15px;
        }
        .navbar h2 {
            flex: 1;
            margin: 0;
            font-size: 22px;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            font-weight: bold;
        }
        .navbar a:hover {
            background-color: #ffffff20;
            border-radius: 5px;
        }

        select,
select option {
    color: black;
    background-color: white;
}


        .register-form {
            max-width: 480px;
            margin: 60px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(15px);
            color: white;
            animation: fadeInSlide 1.5s ease;
        }
        .register-form h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #fff;
        }
        .form-group {
            margin-bottom: 18px;
            position: relative;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 45px 12px 40px;
            border: none;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 15px;
            transition: transform 0.2s ease-in-out;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            transform: scale(1.03);
            outline: none;
        }
        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #eee;
        }
        .form-group i {
            position: absolute;
            top: 12px;
            left: 12px;
            color: #ddd;
        }

        .register-btn {
            width: 100%;
            padding: 14px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .register-btn:hover {
            background-color: #43a047;
            transform: scale(1.05);
            animation: bounce 0.3s;
        }

        @media (max-width: 600px) {
            .register-form {
                margin: 20px;
                padding: 25px;
            }
        }

        /* Animations */
        @keyframes fadeInSlide {
            0% {
                opacity: 0;
                transform: translateY(-30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            0% {
                transform: translateY(-100%);
            }
            100% {
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            0% { transform: scale(1); }
            50% { transform: scale(1.08); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <img src="https://maharashtravikasgroup.com/writable/uploads/logo.png?v=1.0.2" alt="Company Logo">
    <h2>Maharashtra Vikas Group Pvt. Ltd</h2>
    <a href="index.php">🏠 Home</a>
    <a href="register.php">📝 Register</a>
    <a href="login.php">🔐 Login</a>
</div>

<!-- Registration Form -->
<div class="register-form">
    <h2>Register</h2>
    <form method="POST" action="">
        <div class="form-group">
            <i class="fa fa-user"></i>
            <input type="text" name="fullname" placeholder="Full Name" required>
        </div>
        <div class="form-group">
            <i class="fa fa-envelope"></i>
            <input type="email" name="email" placeholder="Email Address" required>
        </div>
        <div class="form-group">
            <i class="fa fa-phone"></i>
            <input type="text" name="phone" placeholder="Phone Number" required>
        </div>
        <div class="form-group">
            <i class="fa fa-venus-mars"></i>
            <select name="gender">
                <option value="">-- Select Gender --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <i class="fa fa-indian-rupee-sign"></i>
            <select name="invest" required>
                <option value="">-- Select Investment Amount --</option>
                <option value="5000">₹5,000</option>
                <option value="10000">₹10,000</option>
                <option value="25000">₹25,000</option>
                <option value="50000">₹50,000</option>
                <option value="100000">₹1,00,000</option>
            </select>
        </div>
        <div class="form-group">
            <i class="fa fa-calendar"></i>
            <input type="number" name="age" placeholder="Age">
        </div>
        <div class="form-group">
            <i class="fa fa-map-marker-alt"></i>
            <textarea name="address" placeholder="Address" rows="3"></textarea>
        </div>
        <div class="form-group">
            <i class="fa fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="register-btn">Register Now</button>
    </form>
</div>

</body>
</html>
