<?php
// providers/facebook_oauth.php
define('APP_INIT', true);
require_once __DIR__ . '/../config.php';
require_login();
$secrets = include __DIR__ . '/../config/secrets.php';
$fb = $secrets['facebook'] ?? null;
if (empty($fb['client_id']) || empty($fb['redirect_uri'])) die('Set Facebook client_id and redirect_uri in config/secrets.php');
$state = bin2hex(random_bytes(8)); $_SESSION['fb_state'] = $state;
$scope = 'pages_manage_posts,pages_read_engagement,pages_read_user_content,public_profile,email';
$auth = 'https://www.facebook.com/v15.0/dialog/oauth?client_id='.urlencode($fb['client_id']).'&redirect_uri='.urlencode($fb['redirect_uri']).'&state='.urlencode($state).'&scope='.urlencode($scope);
header('Location: '.$auth);
exit;
