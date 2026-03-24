<?php
require 'db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email and password are required.';
    } else {
        // users table: id, name, email, password_hash
        $stmt = $conn->prepare("SELECT id, name, password_hash FROM users WHERE email = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                // Verify against hashed password
                if (password_verify($password, $row['password_hash'])) {
                    $_SESSION['user_id']   = $row['id'];
                    $_SESSION['user_name'] = $row['name'];
                    header('Location: index.php');
                    exit;
                }
            }
            $error = 'Invalid email or password.';
        } else {
            $error = 'Login error. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Expense Tracker</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
  <div class="auth-card">
    <h1>Login</h1>
    <?php if ($error): ?>
      <p class="text-danger"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" action="login.php">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" required>

      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>

      <button type="submit" class="btn-primary mt-1">Login</button>
    </form>
    <p class="mt-1">
      Don't have an account?
      <a href="register.php">Register</a>
    </p>
  </div>
</body>
</html>
