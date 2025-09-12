<?php
define('APP_INIT', true);
require_once __DIR__ . '/../config.php';
require_login();
$secrets = include __DIR__ . '/../config/secrets.php';
$tw = $secrets['twitter'] ?? null;
if (!isset($_GET['code']) || !isset($_GET['state']) || $_GET['state'] !== ($_SESSION['tw_state'] ?? '')) die('Invalid state');
$code = $_GET['code'];
// exchange - simplified; requires client_secret etc.
$token_url = 'https://api.twitter.com/2/oauth2/token';
$post = http_build_query(['client_id'=>$tw['client_id'],'client_secret'=>$tw['client_secret'],'code'=>$code,'grant_type'=>'authorization_code','redirect_uri'=>$tw['redirect_uri'],'code_verifier'=>'challenge']);
$ch = curl_init($token_url); curl_setopt($ch, CURLOPT_POST, true); curl_setopt($ch, CURLOPT_POSTFIELDS, $post); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLOPT_HTTPHEADER,['Content-Type: application/x-www-form-urlencoded']); $resp = curl_exec($ch); $err = curl_error($ch); curl_close($ch);
if ($err) die('Token error: '.$err);
$data = json_decode($resp,true); if (empty($data['access_token'])) die('No token: '.htmlspecialchars($resp));
$access = $data['access_token']; $refresh = $data['refresh_token'] ?? null;
// get profile
$ch = curl_init('https://api.twitter.com/2/users/me'); curl_setopt($ch, CURLOPT_HTTPHEADER,['Authorization: Bearer '.$access]); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); $p = curl_exec($ch); curl_close($ch); $pd = json_decode($p,true);
$uname = $pd['data']['username'] ?? ('twitter_user');
$config = json_encode(['access_token'=>$access,'refresh_token'=>$refresh,'twitter_id'=>$pd['data']['id'] ?? null,'stored_at'=>date(DATE_ATOM)]);
$stmt = $pdo->prepare('INSERT INTO social_accounts (provider,account_name,config,access_token) VALUES (?,?,?,?)');
$stmt->execute(['twitter',$uname,$config,$access]);
echo 'Twitter connected: '.htmlspecialchars($uname).'<p><a href="/dashboard.php">Back</a></p>';
