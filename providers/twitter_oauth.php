<?php
define('APP_INIT', true);
require_once __DIR__ . '/../config.php';
require_login();
$secrets = include __DIR__ . '/../config/secrets.php';
$tw = $secrets['twitter'] ?? null;
if (empty($tw['client_id']) || empty($tw['redirect_uri'])) die('Set twitter client_id and redirect_uri');
$state = bin2hex(random_bytes(8)); $_SESSION['tw_state'] = $state;
$scope = 'tweet.read tweet.write users.read offline.access';
$auth = 'https://twitter.com/i/oauth2/authorize?response_type=code&client_id='.urlencode($tw['client_id']).'&redirect_uri='.urlencode($tw['redirect_uri']).'&scope='.urlencode($scope).'&state='.urlencode($state).'&code_challenge=challenge&code_challenge_method=plain';
header('Location: '.$auth);
exit;
