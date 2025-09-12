<?php
define('APP_INIT', true);
require_once __DIR__ . '/../config.php';
require_login();
$secrets = include __DIR__ . '/../config/secrets.php';
$tt = $secrets['tiktok'] ?? null;
if (empty($tt['client_key']) || empty($tt['redirect_uri'])) die('Set tiktok client_key and redirect_uri');
$state = bin2hex(random_bytes(8)); $_SESSION['tt_state'] = $state;
$scope = 'user.info.basic,video.list,video.comment';
$auth = 'https://open-api.tiktok.com/platform/oauth/connect/?client_key='.urlencode($tt['client_key']).'&response_type=code&scope='.urlencode($scope).'&redirect_uri='.urlencode($tt['redirect_uri']).'&state='.urlencode($state);
header('Location: '.$auth);
exit;
