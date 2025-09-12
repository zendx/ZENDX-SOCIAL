<?php
define('APP_INIT', true);
require_once 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($name && $email && strlen($password) >= 6) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email already registered';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,?)');
            $stmt->execute([$name,$email,$hash,'editor']);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            header('Location: dashboard.php');
            exit;
        }
    } else {
        $error = 'All fields required; password must be at least 6 characters';
    }
}
require 'inc/header.php';
?>
<div class="panel">
<h2>Register</h2>
<?php if ($error) echo '<div class="error">'.htmlspecialchars($error).'</div>'; ?>
<form method="post" action="register.php">
<input name="name" placeholder="Full name" required>
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password (min 6 chars)" required>
<button>Create account</button>
</form>
</div>
<?php require 'inc/footer.php'; ?>
