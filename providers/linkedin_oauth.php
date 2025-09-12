<?php
define('APP_INIT', true);
require_once __DIR__ . '/../config.php';
require_login();
$secrets = include __DIR__ . '/../config/secrets.php';
$li = $secrets['linkedin'] ?? null;
if (empty($li['client_id']) || empty($li['redirect_uri'])) die('Set linkedin client_id and redirect_uri');
$state = bin2hex(random_bytes(8)); $_SESSION['li_state'] = $state;
$scope = 'r_liteprofile r_emailaddress w_member_social';
$auth = 'https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id='.urlencode($li['client_id']).'&redirect_uri='.urlencode($li['redirect_uri']).'&state='.urlencode($state).'&scope='.urlencode($scope);
header('Location: '.$auth);
exit;
