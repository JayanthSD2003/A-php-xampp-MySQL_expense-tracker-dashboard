<?php
require 'db.php';
require 'auth.php';

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass1 = $_POST['password'] ?? '';
    $pass2 = $_POST['password_confirm'] ?? '';

    if ($name === '')   $errors[] = 'Name is required';
    if ($email === '')  $errors[] = 'Email is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
    if ($pass1 === '' || $pass2 === '') $errors[] = 'Password and confirmation are required';
    if ($pass1 !== $pass2) $errors[] = 'Passwords do not match';

    if (empty($errors)) {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $errors[] = 'Email already registered';
        } else {
            $hash = password_hash($pass1, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $name, $email, $hash);
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['user_name'] = $name;
                header('Location: index.php');
                exit;
            } else {
                $errors[] = 'Registration failed. Try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Expense Tracker</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
<div class="auth-container">
  <div class="auth-card">
    <h1 class="auth-title">Expense Tracker</h1>
    <h2 class="auth-subtitle">Create Account</h2>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <?php foreach ($errors as $e): ?>
          <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="post" class="form">
      <label for="name">Full name</label>
      <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>

      <label for="email">Email address</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <label for="password_confirm">Confirm password</label>
      <input type="password" id="password_confirm" name="password_confirm" required>

      <button type="submit" class="btn-primary">Register</button>
    </form>

    <p class="auth-switch">
      Already have an account?
      <a href="login.php">Login here</a>
    </p>
  </div>
</div>
</body>
</html>
