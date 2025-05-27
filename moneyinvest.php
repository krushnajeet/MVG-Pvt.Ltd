<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    header("Location: register.php");
    exit();
}

// Fetch user plan
$stmt = $conn->prepare("SELECT invest FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $plan_amount = $row['invest'];
} else {
    header("Location: register.php");
    exit();
}

$plan_amount = (int)$plan_amount;

// Define max counts
if ($plan_amount == 5000) {
    $denominations = [100 => 10, 200 => 10, 500 => 4, 1000 => 1];
} elseif ($plan_amount == 10000) {
    $denominations = [100 => 10, 200 => 15, 500 => 8, 1000 => 2];
} elseif ($plan_amount == 25000) {
    $denominations = [100 => 30, 200 => 20, 500 => 16, 1000 => 10];
} elseif ($plan_amount == 50000) {
    $denominations = [100 => 40, 200 => 30, 500 => 40, 1000 => 25];
} else {
    $denominations = [100 => 60, 200 => 70, 500 => 80, 1000 => 50];
}

// Fetch used counts
$usedCounts = [];
$stmt = $conn->prepare("SELECT denomination, count_used FROM user_investments WHERE user_id = ? AND plan_amount = ?");
$stmt->bind_param("ii", $user_id, $plan_amount);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $usedCounts[$row['denomination']] = $row['count_used'];
}

$grandTotal = 0;  // reset before loop
while ($row = $result->fetch_assoc()) {
    $den = $row['denomination'];
    $count = $row['count_used'];
    $usedCounts[$den] = $count;
    $grandTotal += $den * $count;
}

$remaining = $plan_amount - $grandTotal;
?>

<!DOCTYPE html>
<html>
<head>
    <title>SafeInvest</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            padding: 0;
            background: #f2f2f2;
        }
        .navbar {
            background-color:rgb(48, 225, 225);
            padding: 15px;
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .navbar .center-logo {
            margin: 0 auto;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
        }
        .navbar img {
            height: 40px;
            margin-right: 10px;
        }
        .table-container {
            max-width: 800px;
            margin: 80px auto 40px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn-denom {
            width: 100px;
            margin: 5px;
            font-weight: bold;
        }
        .btn-green {
            background-color: green !important;
            color: white;
        }
        .grand-total-green {
            background-color: lightgreen !important;
            font-weight: bold;
        }
        .denom-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .denom-box {
            margin: 10px;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <a href="index.php" class="btn btn-light btn-sm">← Back</a>
    <div class="center-logo">
        <img src="https://maharashtravikasgroup.com/writable/uploads/logo.png?v=1.0.2" alt="Logo">
        <span>Maharashtra Vikas Group PVT.LTD</span>
    </div>
</div>

<div class="table-container">
    <h3 class="text-center mb-4">Investing in Plan: ₹<?= $plan_amount ?></h3>

    <div class="denom-wrapper mb-4">
        <?php foreach ($denominations as $value => $maxCount): ?>
            <div class="denom-box">
                <?php
                $used = $usedCounts[$value] ?? 0;
                for ($i = 1; $i <= $maxCount; $i++):
                    $isDone = $i <= $used;
                    $btnId = "btn-{$value}-{$i}";
                    $btnClass = $isDone ? 'btn-success btn-green' : 'btn-danger';
                ?>
                <button id="<?= $btnId ?>" class="btn btn-denom <?= $btnClass ?>"
                        <?= $isDone ? 'disabled' : '' ?>
                        onclick="investAmount(<?= $value ?>, <?= $i ?>, '<?= $btnId ?>')">
                    ₹<?= $value ?>
                </button>
                <?php endfor; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <table class="table table-bordered text-center">
        <thead class="table-danger">
            <tr>
                <th>Denomination</th>
                <th>Used</th>
                <th>Total (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($denominations as $value => $maxCount): ?>
                <tr>
                    <td>₹<?= $value ?></td>
                    <td id="used-<?= $value ?>"><?= $usedCounts[$value] ?? 0 ?>/<?= $maxCount ?></td>
                    <td id="total-<?= $value ?>"><?= ($usedCounts[$value] ?? 0) * $value ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr id="row-grand" class="<?= $remaining == 0 ? 'grand-total-green' : 'table-secondary' ?>">
                <td colspan="2"><strong>Grand Total Invested</strong></td>
                <td id="grand-total"><strong>₹<?= $grandTotal ?></strong></td>
            </tr>
            <tr id="row-remaining" class="<?= $remaining == 0 ? 'grand-total-green' : 'table-warning' ?>">
                <td colspan="2"><strong>Remaining to Invest</strong></td>
                <td id="remaining"><strong>₹<?= $remaining ?></strong></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
function investAmount(amount, index, btnId) {
    const upiId = "8010288022@ibl";
    const note = "SafeInvest Payment of ₹" + amount;
    const phonePeUrl = `upi://pay?pa=${upiId}&pn=SafeInvest&am=${amount}&cu=INR&tn=${encodeURIComponent(note)}`;
    window.open(phonePeUrl, "_blank");

setTimeout(() => {
    if (confirm("Did you complete the payment of ₹" + amount + "?")) {
        fetch("process_investment.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: "amount=" + amount
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const btn = document.getElementById(btnId);
                btn.classList.remove("btn-danger");
                btn.classList.add("btn-success", "btn-green");
                btn.disabled = true;

                document.getElementById("used-" + amount).innerText = data.used + "/" + data.max;
                document.getElementById("total-" + amount).innerText = data.total_denom;
                document.getElementById("grand-total").innerHTML = "<strong>₹" + data.grand_total + "</strong>";
                document.getElementById("remaining").innerHTML = "<strong>₹" + data.remaining + "</strong>";

                document.getElementById("row-grand").className = data.remaining == 0 ? 'grand-total-green' : 'table-secondary';
                document.getElementById("row-remaining").className = data.remaining == 0 ? 'grand-total-green' : 'table-warning';
            } else {
                alert("Limit reached or error occurred.");
            }
        });
    } else {
        alert("Payment not completed. Try again.");
    }
}, 5000);

}
</script>

</body>
</html>
