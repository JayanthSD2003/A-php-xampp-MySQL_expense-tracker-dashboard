<?php
require 'db.php';
require 'auth.php';
require_login();

$userName     = current_user_name();
$currentMonth = date('Y-m');

// Expense limit (global)
$stmt = $conn->prepare('SELECT expense_limit FROM settings WHERE month_year = ?');
$stmt->bind_param('s', $currentMonth);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$expenseLimit = $row['expense_limit'] ?? 0;

// All transactions
$tStmt = $conn->prepare('SELECT * FROM transactions ORDER BY tran_date DESC, id DESC');
$tStmt->execute();
$transactions = $tStmt->get_result();

// Totals (lowercase)
$incomeStmt = $conn->prepare("SELECT SUM(amount) AS total_income FROM transactions WHERE type='income'");
$incomeStmt->execute();
$incomeRow = $incomeStmt->get_result()->fetch_assoc();
$totalIncome = $incomeRow['total_income'] ?? 0;

$expenseStmt = $conn->prepare("SELECT SUM(amount) AS total_expense FROM transactions WHERE type='expense'");
$expenseStmt->execute();
$expenseRow = $expenseStmt->get_result()->fetch_assoc();
$totalExpense = $expenseRow['total_expense'] ?? 0;

$balance = $totalIncome - $totalExpense;

// Limit status
$remainingLimit = 0;
$limitMessage   = '';
if ($expenseLimit > 0) {
    $remainingLimit = $expenseLimit - $totalExpense;
    if ($remainingLimit < 0) {
        $limitMessage = 'Limit exceeded by ₹' . number_format(abs($remainingLimit), 2);
    } else {
        $limitMessage = 'Remaining limit: ₹' . number_format($remainingLimit, 2);
    }
}

// Chart: expenses by category
$catStmt = $conn->prepare("
    SELECT category, SUM(amount) AS total
    FROM transactions
    WHERE type = 'expense'
    GROUP BY category
");
$catStmt->execute();
$catRes     = $catStmt->get_result();
$categories = [];
$catTotals  = [];
while ($r = $catRes->fetch_assoc()) {
    $categories[] = $r['category'];
    $catTotals[]  = (float)$r['total'];
}

// Chart: income vs expense per month (lowercase)
$trendStmt = $conn->prepare("
  SELECT DATE_FORMAT(tran_date, '%Y-%m') AS ym,
         SUM(CASE WHEN type='income' THEN amount ELSE 0 END) AS income,
         SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) AS expense
  FROM transactions
  GROUP BY ym
  ORDER BY ym DESC
  LIMIT 6
");
$trendStmt->execute();
$trendRes      = $trendStmt->get_result();
$trendLabels   = [];
$trendIncome   = [];
$trendExpense  = [];
while ($tr = $trendRes->fetch_assoc()) {
    $trendLabels[]  = $tr['ym'];
    $trendIncome[]  = (float)$tr['income'];
    $trendExpense[] = (float)$tr['expense'];
}
$trendLabels  = array_reverse($trendLabels);
$trendIncome  = array_reverse($trendIncome);
$trendExpense = array_reverse($trendExpense);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Expense Tracker</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header class="topbar">
  <div class="topbar-left">
    <span class="topbar-logo">Expense Tracker</span>
    <span class="topbar-subtitle">Dashboard</span>
  </div>
  <div class="topbar-right">
    <span class="topbar-user"><?= htmlspecialchars($userName) ?></span>
    <a href="logout.php" class="topbar-link">Logout</a>
  </div>
</header>

<div class="layout">
  <aside class="sidebar">
    <div class="sidebar-section">
      <div class="sidebar-title">Navigation</div>
      <a class="sidebar-link active" href="index.php">Dashboard</a>
      <a class="sidebar-link" href="#transactions">Transactions</a>
      <a class="sidebar-link" href="#reports">Reports</a>
    </div>
  </aside>

  <main class="main">
    <section class="cards-row">
      <div class="card stat-card">
        <div class="stat-label">Total Income</div>
        <div class="stat-value stat-income">₹<?= number_format($totalIncome, 2) ?></div>
      </div>
      <div class="card stat-card">
        <div class="stat-label">Total Expense</div>
        <div class="stat-value stat-expense">₹<?= number_format($totalExpense, 2) ?></div>
      </div>
      <div class="card stat-card">
        <div class="stat-label">Balance</div>
        <div class="stat-value"><?= number_format($balance, 2) ?></div>
      </div>
    </section>

    <section class="cards-row" id="settings">
      <div class="card">
        <h2>Monthly Expense Limit (<?= htmlspecialchars($currentMonth) ?>)</h2>
        <form method="post" action="save_limit.php" class="form-inline">
          <input type="number"
                 name="expenseLimit"
                 id="expenseLimit"
                 step="0.01"
                 min="0"
                 value="<?= htmlspecialchars($expenseLimit) ?>"
                 placeholder="Set monthly limit">
          <button type="submit" class="btn-secondary" name="save_limit" value="1">Save Limit</button>
          <?php if ($expenseLimit > 0): ?>
            <button type="submit" class="btn-secondary" name="remove_limit" value="1">Remove Limit</button>
          <?php endif; ?>
        </form>

        <?php if ($expenseLimit > 0): ?>
          <p class="<?= $remainingLimit < 0 ? 'text-danger' : 'text-success' ?> mt-1">
            <?= htmlspecialchars($limitMessage) ?>
          </p>
        <?php else: ?>
          <p class="mt-1">No limit set for this month.</p>
        <?php endif; ?>

        <form action="export_excel.php" method="get" class="mt-1">
          <button type="submit" name="export" value="1" class="btn-secondary">Export to Excel</button>
        </form>
      </div>

      <div class="card">
        <h2>Add Transaction</h2>
        <form method="post" action="add_transaction.php" class="form" id="transactionForm">
          <label for="tran_date">Date</label>
          <input type="date" name="tran_date" id="tran_date" required value="<?= date('Y-m-d') ?>">

          <label for="type">Type</label>
          <select name="type" id="type">
            <option value="expense">Expense</option>
            <option value="income">Income</option>
          </select>

          <label for="category">Category</label>
          <select name="category" id="category">
            <option value="food">Food</option>
            <option value="travel">Travel</option>
            <option value="bills">Bills</option>
            <option value="shopping">Shopping</option>
            <option value="salary">Salary</option>
            <option value="other">Other</option>
          </select>

          <label for="description">Description</label>
          <input type="text" name="description" id="description" placeholder="Specify the description" required>

          <label for="amount">Amount (₹)</label>
          <input type="number" name="amount" id="amount" step="0.01" min="0" required>

          <button type="submit" class="btn-primary mt-1">Add</button>
        </form>
      </div>
    </section>

    <section class="cards-row" id="reports">
      <div class="card">
        <h2>Expenses by Category</h2>
        <canvas id="expenseChart" height="130"></canvas>
      </div>
      <div class="card">
        <h2>Income vs Expense (Monthly)</h2>
        <canvas id="trendChart" height="130"></canvas>
      </div>
    </section>

    <section class="card mt-2" id="transactions">
      <h2>Transaction History</h2>
      <div class="table-wrapper">
        <table class="data-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>Category</th>
              <th>Description</th>
              <th>Amount (₹)</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($transactions->num_rows > 0): ?>
              <?php while ($row = $transactions->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['tran_date']) ?></td>
                  <td class="<?= $row['type'] === 'income' ? 'text-income' : 'text-expense' ?>">
                    <?= htmlspecialchars($row['type']) ?>
                  </td>
                  <td><?= htmlspecialchars($row['category']) ?></td>
                  <td><?= htmlspecialchars($row['description']) ?></td>
                  <td><?= number_format($row['amount'], 2) ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="5">No transactions yet.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>

<script>
const expenseLabels = <?= json_encode($categories) ?>;
const expenseData   = <?= json_encode($catTotals) ?>;
const trendLabels   = <?= json_encode($trendLabels) ?>;
const trendIncome   = <?= json_encode($trendIncome) ?>;
const trendExpense  = <?= json_encode($trendExpense) ?>;
</script>
<script src="main.js"></script>
</body>
</html>
