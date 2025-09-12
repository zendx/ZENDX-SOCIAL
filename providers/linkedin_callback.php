<?php
define('APP_INIT', true);
require_once __DIR__ . '/../config.php';
require_login();
$secrets = include __DIR__ . '/../config/secrets.php';
$li = $secrets['linkedin'] ?? null;
if (!isset($_GET['code']) || !isset($_GET['state']) || $_GET['state'] !== ($_SESSION['li_state'] ?? '')) die('Invalid state');
$code = $_GET['code'];
$token_url = 'https://www.linkedin.com/oauth/v2/accessToken';
$post = http_build_query(['grant_type'=>'authorization_code','code'=>$code,'redirect_uri'=>$li['redirect_uri'],'client_id'=>$li['client_id'],'client_secret'=>$li['client_secret']]);
$ch = curl_init($token_url); curl_setopt($ch, CURLOPT_POST, true); curl_setopt($ch, CURLOPT_POSTFIELDS, $post); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']); $resp = curl_exec($ch); curl_close($ch); $data = json_decode($resp,true);
$access = $data['access_token'] ?? null; if (!$access) die('No token');
$ch = curl_init('https://api.linkedin.com/v2/me'); curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$access]); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); $profile = curl_exec($ch); curl_close($ch); $pd = json_decode($profile,true); $lid = $pd['id'] ?? null; $config = json_encode(['access_token'=>$access,'linkedin_id'=>$lid,'stored_at'=>date(DATE_ATOM)]);
$stmt = $pdo->prepare('INSERT INTO social_accounts (provider,account_name,config,access_token) VALUES (?,?,?,?)');
$stmt->execute(['linkedin',$pd['localizedFirstName']??'LinkedIn',$config,$access]);
echo 'LinkedIn connected.<p><a href="/dashboard.php">Back</a></p>';
