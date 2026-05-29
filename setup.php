<?php
require_once __DIR__ . '/config.php';

/**
 * Setup script - create default admin user on first run
 * 
 * SECURITY: This script should be deleted after first run
 * or protected with a setup token in production
 */

try {
    $count = $pdo->query('SELECT COUNT(*) AS c FROM users')->fetch();
    if ($count && intval($count['c']) === 0) {
        // Generate random credentials for security
        $email = 'admin@example.com';
        $pass = bin2hex(random_bytes(8)); // Random 16-char password
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        
        $stmt = $pdo->prepare('INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,?)');
        $stmt->execute(['Admin User', $email, $hash, 'admin']);
        
        echo '<!DOCTYPE html><html><head><title>Setup Complete</title></head><body>';
        echo '<h1>Setup Complete</h1>';
        echo '<p><strong>Default admin user created:</strong></p>';
        echo '<p>Email: <code>' . htmlspecialchars($email) . '</code></p>';
        echo '<p>Password: <code>' . htmlspecialchars($pass) . '</code></p>';
        echo '<p style="color: red;"><strong>Important:</strong> Save these credentials securely. This is the only time they will be displayed.</p>';
        echo '<p><strong>Next steps:</strong></p>';
        echo '<ol>';
        echo '<li>Log in with the credentials above</li>';
        echo '<li>Copy <code>config/secrets.example.php</code> to <code>config/secrets.php</code></li>';
        echo '<li>Edit <code>config/secrets.php</code> with your OAuth credentials and SMTP settings</li>';
        echo '<li>Delete or protect this <code>setup.php</code> file</li>';
        echo '<li>Create new admin users via the admin panel</li>';
        echo '</ol>';
        echo '</body></html>';
    } else {
        echo '<!DOCTYPE html><html><head><title>Setup</title></head><body>';
        echo '<h1>Setup</h1>';
        echo '<p>Users already exist in the database. No action taken.</p>';
        echo '<p>If you need to reset, delete all users and run setup again.</p>';
        echo '</body></html>';
    }
} catch (Exception $e) {
    echo '<h1>Error</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>