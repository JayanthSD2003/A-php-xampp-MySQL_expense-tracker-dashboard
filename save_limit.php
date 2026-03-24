<?php
require 'db.php';
require 'auth.php';
require_login();

$currentMonth = date('Y-m');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['remove_limit'])) {
        $del = $conn->prepare("DELETE FROM settings WHERE month_year = ?");
        $del->bind_param('s', $currentMonth);
        $del->execute();
        header('Location: index.php');
        exit;
    }

    $limit = isset($_POST['expenseLimit']) ? (float)$_POST['expenseLimit'] : 0;

    if ($limit >= 0) {
        $stmt = $conn->prepare("SELECT id FROM settings WHERE month_year = ?");
        $stmt->bind_param('s', $currentMonth);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            $id = $row['id'];
            $u  = $conn->prepare("UPDATE settings SET expense_limit = ? WHERE id = ?");
            $u->bind_param('di', $limit, $id);
            $u->execute();
        } else {
            $i = $conn->prepare("INSERT INTO settings (month_year, expense_limit) VALUES (?, ?)");
            $i->bind_param('sd', $currentMonth, $limit);
            $i->execute();
        }
    }
}

header('Location: index.php');
exit;
?>