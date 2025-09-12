<?php
// providers/facebook_callback.php - exchange code and store page token
define('APP_INIT', true);
require_once __DIR__ . '/../config.php';
require_login();
$secrets = include __DIR__ . '/../config/secrets.php';
$fb = $secrets['facebook'] ?? null;
if (!isset($_GET['code']) || !isset($_GET['state']) || $_GET['state'] !== ($_SESSION['fb_state'] ?? '')) die('Invalid state');
$code = $_GET['code'];
$token_url = 'https://graph.facebook.com/v15.0/oauth/access_token?client_id=' . urlencode($fb['client_id']) . '&redirect_uri=' . urlencode($fb['redirect_uri']) . '&client_secret=' . urlencode($fb['client_secret']) . '&code=' . urlencode($code);
$resp = file_get_contents($token_url); $data = json_decode($resp,true);
if (empty($data['access_token'])) die('No token: '.htmlspecialchars($resp));
$short = $data['access_token'];
$long = file_get_contents('https://graph.facebook.com/v15.0/oauth/access_token?grant_type=fb_exchange_token&client_id=' . urlencode($fb['client_id']) . '&client_secret=' . urlencode($fb['client_secret']) . '&fb_exchange_token=' . urlencode($short));
$long = json_decode($long,true); $long_token = $long['access_token'] ?? $short;
$pages = file_get_contents('https://graph.facebook.com/v15.0/me/accounts?access_token=' . urlencode($long_token));
$pages = json_decode($pages,true);
if (empty($pages['data'])) die('No pages found');
$page = $pages['data'][0];
$config = json_encode(['access_token'=>$long_token,'page_id'=>$page['id'],'page_access_token'=>$page['access_token'],'stored_at'=>date(DATE_ATOM)]);
$stmt = $pdo->prepare('INSERT INTO social_accounts (provider,account_name,config,access_token) VALUES (?,?,?,?)');
$stmt->execute(['facebook',$page['name'],$config,$long_token]);
echo 'Facebook connected: '.htmlspecialchars($page['name']).'<p><a href="/dashboard.php">Back</a></p>';
