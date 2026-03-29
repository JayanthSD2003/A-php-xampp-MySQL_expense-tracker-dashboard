<?php
require 'db.php';
require 'auth.php';
require_login();

if (!isset($_GET['export'])) {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['user_id'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=transactions.xls");
header("Pragma: no-cache");
header("Expires: 0");

$stmt = $conn->prepare("
    SELECT tran_date, type, category, description, amount
    FROM transactions
    WHERE user_id = ?
    ORDER BY tran_date DESC, id DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

echo "<table border='1'>";
echo "<tr>
        <th>Date</th>
        <th>Type</th>
        <th>Category</th>
        <th>Description</th>
        <th>Amount</th>
      </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['tran_date']) . "</td>";
    echo "<td>" . htmlspecialchars($row['type']) . "</td>";
    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
    echo "<td>" . htmlspecialchars(number_format($row['amount'], 2)) . "</td>";
    echo "</tr>";
}

echo "</table>";
exit;
?>
