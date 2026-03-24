<?php
require 'db.php';
require 'auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date        = $_POST['tran_date'] ?? null;
    $type        = $_POST['type'] ?? null;        // "income" or "expense"
    $category    = $_POST['category'] ?? null;
    $description = $_POST['description'] ?? '';
    $amount      = $_POST['amount'] ?? 0;

    $date        = trim((string)$date);
    $type        = strtolower(trim((string)$type));   // normalize
    $category    = trim((string)$category);
    $description = trim((string)$description);
    $amount      = (float)$amount;

    // For now, if type is income, force category to salary (optional)
    if ($type === 'income' && $category === '') {
        $category = 'salary';
    }

    if ($date !== '' && ($type === 'income' || $type === 'expense') && $category !== '' && $amount > 0) {
        $stmt = $conn->prepare("
            INSERT INTO transactions (tran_date, type, category, description, amount, user_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        if ($stmt) {
            $userId = current_user_id();
            $stmt->bind_param('ssssdi', $date, $type, $category, $description, $amount, $userId);
            $stmt->execute();
        }
    }
}

header('Location: index.php');
exit;
?>