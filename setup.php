<?php
require_once __DIR__ . '/config.php';
// create default admin if users table empty
try {
    $count = $pdo->query('SELECT COUNT(*) AS c FROM users')->fetch();
    if ($count && intval($count['c']) === 0) {
        $email = 'admin@example.com';
        $pass = 'admin123';
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,?)');
        $stmt->execute(['Admin User',$email,$hash,'admin']);
        echo 'Default admin created: ' . $email . ' / ' . $pass;
    } else {
        echo 'Users already exist. No action taken.';
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>