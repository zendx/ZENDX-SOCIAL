<?php
define('APP_INIT', true);
require_once 'config.php';
$error = '';
$success = '';
$token = $_GET['token'] ?? ($_POST['token'] ?? '');
if (!$token) { die('Token required'); }
$stmt = $pdo->prepare('SELECT pr.*, u.email FROM password_resets pr JOIN users u ON pr.user_id=u.id WHERE pr.token = ? LIMIT 1');
$stmt->execute([$token]);
$r = $stmt->fetch();
if (!$r) die('Invalid token');
if (strtotime($r['expires_at']) < time()) die('Token expired');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if ($new && $new === $confirm) {
        $hash = password_hash($new, PASSWORD_BCRYPT);
        $u = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        $u->execute([$hash, $r['user_id']]);
        $del = $pdo->prepare('DELETE FROM password_resets WHERE id = ?');
        $del->execute([$r['id']]);
        $success = 'Password updated. You can now <a href="login.php">login</a>.';
    } else $error = 'Passwords do not match';
}

require 'inc/header.php';
?>
<div class="panel">
<h2>Reset Password</h2>
<?php if ($error) echo '<div class="error">'.htmlspecialchars($error).'</div>'; ?>
<?php if ($success) echo '<div class="panel">'. $success .'</div>'; else: ?>
<form method="post" action="reset_password.php">
<input type="hidden" name="token" value="<?=htmlspecialchars($token)?>">
<input name="new_password" type="password" placeholder="New password" required>
<input name="confirm_password" type="password" placeholder="Confirm new password" required>
<button>Set new password</button>
</form>
<?php endif; ?>
</div>
<?php require 'inc/footer.php'; ?>
