<?php
define('APP_INIT', true);
require_once __DIR__ . '/../config.php';
require_login();
$secrets = include __DIR__ . '/../config/secrets.php';
$tt = $secrets['tiktok'] ?? null;
if (!isset($_GET['code']) || !isset($_GET['state']) || $_GET['state'] !== ($_SESSION['tt_state'] ?? '')) die('Invalid state');
$code = $_GET['code'];
$token_url = 'https://open-api.tiktok.com/oauth/access_token/?' . http_build_query(['client_key'=>$tt['client_key'],'client_secret'=>$tt['client_secret'],'code'=>$code,'grant_type'=>'authorization_code','redirect_uri'=>$tt['redirect_uri']]);
$resp = file_get_contents($token_url); $data = json_decode($resp,true);
$access = $data['data']['access_token'] ?? null; $open_id = $data['data']['open_id'] ?? null;
if (!$access) die('No token: '.htmlspecialchars($resp));
$config = json_encode(['access_token'=>$access,'open_id'=>$open_id,'stored_at'=>date(DATE_ATOM)]);
$stmt = $pdo->prepare('INSERT INTO social_accounts (provider,account_name,config,access_token) VALUES (?,?,?,?)');
$stmt->execute(['tiktok',$open_id??'tiktok_user',$config,$access]);
echo 'TikTok connected.<p><a href="/dashboard.php">Back</a></p>';
