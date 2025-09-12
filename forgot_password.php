<?php
define('APP_INIT', true);
require_once 'config.php';
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if ($email) {
        $stmt = $pdo->prepare('SELECT id,name,email FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $u = $stmt->fetch();
        if ($u) {
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', time() + 3600);
            $ins = $pdo->prepare('INSERT INTO password_resets (user_id, token, expires_at) VALUES (?,?,?)');
            $ins->execute([$u['id'], $token, $expires]);
            $reset_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on'?'https':'http') . '://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;
            $body = '<p>Hello '.htmlspecialchars($u['name']).',</p><p>Click the link below to reset your password (expires in 1 hour):</p><p><a href="'.htmlspecialchars($reset_link).'">'.htmlspecialchars($reset_link).'</a></p>';
            $res = send_email($u['email'], 'Password reset', $body);
            if ($res['success']) $success = 'Reset email sent. Check your inbox.'; else $error = 'Could not send email: ' . ($res['error'] ?? 'unknown');
        } else {
            $error = 'No account found with that email';
        }
    } else $error = 'Enter your email';
}
require 'inc/header.php';
?>
<div class="panel">
<h2>Forgot Password</h2>
<?php if ($error) echo '<div class="error">'.htmlspecialchars($error).'</div>'; ?>
<?php if ($success) echo '<div class="panel">'.htmlspecialchars($success).'</div>'; ?>
<form method="post" action="forgot_password.php">
<input name="email" type="email" placeholder="Your account email" required>
<button>Send reset email</button>
</form>
</div>
<?php require 'inc/footer.php'; ?>
