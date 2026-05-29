<?php
// Load .env file if it exists
if (file_exists(__DIR__ . '/.env')) {
    $env_lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($env_lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

session_start();

// Database configuration - support environment variables
define('DB_HOST', $_ENV['DB_HOST'] ?? '127.0.0.1');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'social_scheduler');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// load secrets
$secrets = [];
if (file_exists(__DIR__ . '/config/secrets.php')) {
    $secrets = include __DIR__ . '/config/secrets.php';
}

// PDO connection
try {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    die('DB connection error: ' . $e->getMessage());
}

function current_user() {
    global $pdo;
    if (empty($_SESSION['user_id'])) return null;
    $stmt = $pdo->prepare('SELECT id,name,email,role FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function require_login() {
    if (!current_user()) {
        header('Location: login.php');
        exit;
    }
}

function require_admin() {
    $u = current_user();
    if (!$u) {
        header('Location: login.php');
        exit;
    }
    if (!isset($u['role']) || strtolower($u['role']) !== 'admin') {
        http_response_code(403);
        echo '<h1>Forbidden</h1><p>You must be an admin to access this page.</p>';
        exit;
    }
}

// send_email: simple wrapper using embedded libs/PHPMailer/PHPMailer.php
function send_email($to, $subject, $body) {
    global $secrets;
    require_once __DIR__ . '/libs/PHPMailer/PHPMailer.php';
    require_once __DIR__ . '/libs/PHPMailer/SMTP.php';
    require_once __DIR__ . '/libs/PHPMailer/Exception.php';
    $mail = new PHPMailerLite\PHPMailerLite(true);
    try {
        $smtp = $secrets['smtp'] ?? null;
        if ($smtp) {
            $mail->isSMTP();
            $mail->Host = $smtp['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $smtp['username'];
            $mail->Password = $smtp['password'];
            $mail->SMTPSecure = $smtp['encryption'];
            $mail->Port = $smtp['port'];
            $mail->setFrom($smtp['from_email'], $smtp['from_name']);
        } else {
            $mail->setFrom('no-reply@example.com', 'Social Scheduler');
        }
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $mail->ErrorInfo ?? $e->getMessage()];
    }
}
