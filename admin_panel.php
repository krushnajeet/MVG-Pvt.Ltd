<?php
session_start();
require 'config.php'; // Your DB connection here

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit();
}

// Handle user deletion
if (isset($_GET['delete_user'])) {
    $user_id = intval($_GET['delete_user']);

    // Delete investments first
    $stmt = $conn->prepare("DELETE FROM user_investments WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }

    // Delete user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin_panel.php?msg=User+Deleted+Successfully");
    exit();
}

// Fetch all users
$users_result = $conn->query("SELECT id, fullname, phone, invest FROM users ORDER BY fullname ASC");
if (!$users_result) {
    die("Error fetching users: " . $conn->error);
}

// Fetch paid users with sums
$paid_users_sql = "
SELECT 
    u.id, 
    u.fullname, 
    u.phone, 
    u.invest, 
    IFNULL(SUM(ups.grand_total), 0) AS total_paid, 
    (u.invest - IFNULL(SUM(ups.grand_total), 0)) AS remaining_amount
FROM users u
LEFT JOIN user_plan_summary ups ON u.id = ups.user_id
GROUP BY u.id
HAVING total_paid > 0
ORDER BY total_paid DESC
";

$paid_users_result = $conn->query($paid_users_sql);
if (!$paid_users_result) {
    die("Error fetching paid users: " . $conn->error);
}

function safeNumberFormat($value, $decimals = 2) {
    return (is_numeric($value) && $value !== '') ? number_format((float)$value, $decimals) : '0.00';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Investment Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        @keyframes gradientBG {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }
        body {
            margin: 0; padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(-45deg, #87CEFA, #00BFFF, #87CEEB, #00CED1);
            background-size: 400% 400%;
            animation: gradientBG 20s ease infinite;
            color: #222;
            min-height: 100vh;
        }
        nav.navbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        nav.navbar a.nav-link,
        nav.navbar .navbar-brand {
            color: #007acc;
            font-weight: 600;
            transition: color 0.3s;
            cursor: pointer;
        }
        nav.navbar a.nav-link:hover,
        nav.navbar a.nav-link.active {
            color: #004a99;
        }
        .container {
            background: rgba(255,255,255,0.9);
            padding: 30px;
            border-radius: 15px;
            margin-top: 30px;
            box-shadow: 0 0 30px rgba(0, 123, 255, 0.3);
            color: #222;
        }
        .btn-danger:hover {
            background-color: #d9534f;
        }
        table {
            color: #222;
        }
        h1, h3 {
            color: #005f99;
        }
        #users-section, #payments-section {
            display: none;
        }
        #users-section.active, #payments-section.active {
            display: block;
        }
        .search-box {
            margin-bottom: 15px;
            max-width: 300px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid px-4">
    <a class="navbar-brand d-flex align-items-center" href="admin_panel.php">
      <img src="https://maharashtravikasgroup.com/writable/uploads/logo.png?v=1.0.2" alt="SafeInvest Logo" style="height:40px; margin-right:10px;">
      Maharashtra Vikas Group PVT.LTD
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="color:#007acc;"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" id="nav-users" href="#">Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="nav-payments" href="#">Payments</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
    <h1 class="mb-4 text-center">Investment System - Admin Panel</h1>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <!-- Users Section -->
    <section id="users-section" class="active">
        <h3>Registered Users</h3>
        <input type="text" id="userSearch" class="form-control search-box" placeholder="Search Users by any field..." />
        <table class="table table-bordered table-striped table-hover" id="usersTable">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>Phone Number</th>
                    <th>Investment Goal (₹)</th>
                    <th>Action (Delete)</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users_result->num_rows > 0): ?>
                    <?php while ($user = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['fullname']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td><?= safeNumberFormat($user['invest'], 0) ?></td>
                        <td>
                            <a href="admin_panel.php?delete_user=<?= $user['id'] ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this user and all their investments?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">No registered users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <!-- Payments Section -->
    <section id="payments-section">
        <h3>Users Who Have Paid</h3>
        <input type="text" id="paymentSearch" class="form-control search-box" placeholder="Search Payments by any field..." />
        <table class="table table-bordered table-striped table-hover" id="paymentsTable">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Phone</th>
                    <th>Investment Goal (₹)</th>
                    <th>Total Paid (₹)</th>
                    <th>Remaining Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($paid_users_result->num_rows > 0): ?>
                    <?php while ($paid_user = $paid_users_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($paid_user['fullname']) ?></td>
                        <td><?= htmlspecialchars($paid_user['phone']) ?></td>
                        <td><?= safeNumberFormat($paid_user['invest'], 2) ?></td>
                        <td><?= safeNumberFormat($paid_user['total_paid'], 2) ?></td>
                        <td><strong>₹<?= safeNumberFormat($paid_user['remaining_amount'], 2) ?></strong></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No payments recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function setActivePanel(panel) {
    const usersSection = document.getElementById('users-section');
    const paymentsSection = document.getElementById('payments-section');
    const navUsers = document.getElementById('nav-users');
    const navPayments = document.getElementById('nav-payments');

    if(panel === 'payments') {
      paymentsSection.classList.add('active');
      usersSection.classList.remove('active');
      navPayments.classList.add('active');
      navUsers.classList.remove('active');
    } else {
      usersSection.classList.add('active');
      paymentsSection.classList.remove('active');
      navUsers.classList.add('active');
      navPayments.classList.remove('active');
    }

    const url = new URL(window.location);
    url.searchParams.set('panel', panel);
    window.history.replaceState({}, '', url);
  }

  document.getElementById('nav-users').addEventListener('click', function(e) {
    e.preventDefault();
    setActivePanel('users');
  });

  document.getElementById('nav-payments').addEventListener('click', function(e) {
    e.preventDefault();
    setActivePanel('payments');
  });

  window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const panel = urlParams.get('panel') || 'users';
    setActivePanel(panel);

    document.getElementById('userSearch').addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('#usersTable tbody tr');
      rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
      });
    });

    document.getElementById('paymentSearch').addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('#paymentsTable tbody tr');
      rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
      });
    });
  });
</script>

</body>
</html>
