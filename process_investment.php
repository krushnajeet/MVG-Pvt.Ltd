<?php
session_start();
header('Content-Type: application/json');
include 'config.php';

// Check if user is logged in
$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Validate amount from POST
$amount = intval($_POST['amount'] ?? 0);
if ($amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid amount']);
    exit;
}

// Get user's investment plan amount
$stmt = $conn->prepare("SELECT invest FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    echo json_encode(['success' => false, 'message' => 'User plan not found']);
    exit;
}
$plan_amount = (int)$row['invest'];

// Define max counts allowed per denomination for each plan
$denominations = [];
switch ($plan_amount) {
    case 5000:
        $denominations = [100 => 10, 200 => 10, 500 => 4, 1000 => 1];
        break;
    case 10000:
        $denominations = [100 => 10, 200 => 15, 500 => 8, 1000 => 2];
        break;
    case 25000:
        $denominations = [100 => 30, 200 => 20, 500 => 16, 1000 => 10];
        break;
    case 50000:
        $denominations = [100 => 40, 200 => 30, 500 => 40, 1000 => 25];
        break;
    default:
        $denominations = [100 => 60, 200 => 70, 500 => 80, 1000 => 50];
}

if (!isset($denominations[$amount])) {
    echo json_encode(['success' => false, 'message' => 'Denomination not allowed for your plan']);
    exit;
}

// Check current count_used for this user, plan and denomination
$stmt = $conn->prepare("SELECT count_used FROM user_investments WHERE user_id = ? AND plan_amount = ? AND denomination = ?");
$stmt->bind_param("iii", $user_id, $plan_amount, $amount);
$stmt->execute();
$result = $stmt->get_result();

$current_count = 0;
if ($row = $result->fetch_assoc()) {
    $current_count = (int)$row['count_used'];
}

$max_count = $denominations[$amount];

// Check if limit reached
if ($current_count >= $max_count) {
    echo json_encode(['success' => false, 'message' => 'Limit reached']);
    exit;
}

$new_count = $current_count + 1;

// Insert or update count_used
if ($current_count == 0) {
    $stmt = $conn->prepare("INSERT INTO user_investments (user_id, plan_amount, denomination, count_used) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $user_id, $plan_amount, $amount, $new_count);
    $stmt->execute();
} else {
    $stmt = $conn->prepare("UPDATE user_investments SET count_used = ? WHERE user_id = ? AND plan_amount = ? AND denomination = ?");
    $stmt->bind_param("iiii", $new_count, $user_id, $plan_amount, $amount);
    $stmt->execute();
}

// Recalculate grand total and remaining amount
$stmt = $conn->prepare("SELECT denomination, count_used FROM user_investments WHERE user_id = ? AND plan_amount = ?");
$stmt->bind_param("ii", $user_id, $plan_amount);
$stmt->execute();
$result = $stmt->get_result();

$usedCounts = [];
$grandTotal = 0;
foreach ($denominations as $den => $max) {
    $usedCounts[$den] = 0; // initialize counts
}

while ($row = $result->fetch_assoc()) {
    $den = $row['denomination'];
    $count = $row['count_used'];
    $usedCounts[$den] = $count;
    $grandTotal += $den * $count;
}

$remaining = $plan_amount - $grandTotal;

// Update only ONE summary row
$stmt = $conn->prepare("INSERT INTO user_plan_summary (user_id, plan_amount, grand_total, remaining)
                        VALUES (?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE grand_total = VALUES(grand_total), remaining = VALUES(remaining)");
$stmt->bind_param("iiii", $user_id, $plan_amount, $grandTotal, $remaining);
$stmt->execute();

// Return JSON response
echo json_encode([
    'success' => true,
    'used' => $usedCounts[$amount],
    'max' => $max_count,
    'total_denom' => $usedCounts[$amount] * $amount,
    'grand_total' => $grandTotal,
    'remaining' => $remaining
]);
?>
