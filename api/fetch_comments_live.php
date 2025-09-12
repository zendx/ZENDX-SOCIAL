<?php
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../providers/ProviderFactory.php';
header('Content-Type: application/json');
$out = [];
// fetch recent post_targets and try to get comments using providers
$rows = $pdo->query('SELECT pt.*, sa.provider, sa.config FROM post_targets pt JOIN social_accounts sa ON pt.social_account_id = sa.id ORDER BY pt.created_at DESC LIMIT 50')->fetchAll();
foreach ($rows as $r) {
    $cfg = json_decode($r['config'] ?? '{}', true);
    $prov = ProviderFactory::providerFor($r['provider'], $cfg);
    if ($prov && !empty($r['provider_post_id'])) {
        $comments = $prov->fetchComments(['provider_post_id' => $r['provider_post_id']]);
        foreach ($comments as $c) {
            $out[] = ['provider'=>$r['provider'],'post_target_id'=>$r['id'],'comment'=>$c];
        }
    }
}
echo json_encode($out);
?>