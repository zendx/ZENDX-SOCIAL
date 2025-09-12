<?php
define('APP_INIT', true);
require_once 'config.php';
if (!empty($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $u = $stmt->fetch();
        if ($u && password_verify($password, $u['password_hash'])) {
            $_SESSION['user_id'] = $u['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid email or password';
        }
    } else {
        $error = 'Email and password required';
    }
}
require 'inc/header.php';
?>
<div class="panel">
<h2>Login</h2>
<?php if ($error) echo '<div class="error">'.htmlspecialchars($error).'</div>'; ?>
<form method="post" action="login.php">
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password" required>
<button>Login</button>
</form>
<p><a href="forgot_password.php">Forgot password?</a></p>
</div>
<?php require 'inc/footer.php'; ?>
