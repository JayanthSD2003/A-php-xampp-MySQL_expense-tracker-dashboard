<?php
require 'auth.php';
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$currentMonth = date('Y-m');

if (isset($_POST['remove_limit'])) {
    $stmt = $conn->prepare("DELETE FROM settings WHERE user_id = ? AND month_year = ?");
    $stmt->bind_param("is", $userId, $currentMonth);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

$limit = isset($_POST['expenseLimit']) ? (float)$_POST['expenseLimit'] : 0;

if ($limit < 0) {
    die("Invalid limit amount.");
}

$check = $conn->prepare("SELECT id FROM settings WHERE user_id = ? AND month_year = ?");
$check->bind_param("is", $userId, $currentMonth);
$check->execute();
$result = $check->get_result();

if ($row = $result->fetch_assoc()) {
    $stmt = $conn->prepare("UPDATE settings SET expense_limit = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("dii", $limit, $row['id'], $userId);
    $stmt->execute();
} else {
    $stmt = $conn->prepare("INSERT INTO settings (user_id, month_year, expense_limit) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $userId, $currentMonth, $limit);
    $stmt->execute();
}

header("Location: index.php");
exit;
?>
