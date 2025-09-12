<?php
define('APP_INIT', true);
require_once 'config.php';
require_login();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if ($new !== $confirm) { $message = 'New passwords do not match'; }
    else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if ($user && password_verify($current, $user['password_hash'])) {
            $hash = password_hash($new, PASSWORD_BCRYPT);
            $u = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            $u->execute([$hash, $_SESSION['user_id']]);
            $message = 'Password updated';
        } else $message = 'Current password incorrect';
    }
}
require 'inc/header.php';
?>
<div class="panel">
<h2>Change Password</h2>
<?php if ($message) echo '<div class="panel">'.htmlspecialchars($message).'</div>'; ?>
<form method="post" action="change_password.php">
<input type="password" name="current_password" placeholder="Current password" required>
<input type="password" name="new_password" placeholder="New password" required>
<input type="password" name="confirm_password" placeholder="Confirm new password" required>
<button>Update Password</button>
</form>
</div>
<?php require 'inc/footer.php'; ?>
