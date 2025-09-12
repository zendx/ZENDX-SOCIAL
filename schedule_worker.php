<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/providers/ProviderFactory.php';

$stmt = $pdo->prepare("SELECT pt.*, p.title, p.body, p.media_url, sa.provider, sa.config AS sa_config FROM post_targets pt JOIN posts p ON pt.post_id = p.id JOIN social_accounts sa ON pt.social_account_id = sa.id WHERE pt.status = 'pending' AND (p.scheduled_at IS NULL OR p.scheduled_at <= NOW()) LIMIT 50");
$stmt->execute();
$rows = $stmt->fetchAll();
foreach ($rows as $r) {
    echo '['.date('c').'] Processing pt '.$r['id'].' for provider '.$r['provider']."\n";
    $cfg = json_decode($r['sa_config'] ?? '{}', true);
    $prov = ProviderFactory::providerFor($r['provider'], $cfg);
    if (!$prov) { $u = $pdo->prepare('UPDATE post_targets SET status=?, error_message=? WHERE id=?'); $u->execute(['failed','provider_not_found',$r['id']]); continue; }
    $post = ['title'=>$r['title'],'body'=>$r['body'],'media_url'=>$r['media_url']];
    $res = $prov->publishPost($post);
    if ($res['success']) {
        $u = $pdo->prepare('UPDATE post_targets SET status=?, provider_post_id=?, published_at=? WHERE id=?');
        $u->execute(['published',$res['provider_post_id'] ?? null, date('Y-m-d H:i:s'), $r['id']]);
        echo 'Published pt '.$r['id']."\n";
    } else {
        $u = $pdo->prepare('UPDATE post_targets SET status=?, error_message=? WHERE id=?');
        $u->execute(['failed',$res['error'] ?? 'unknown',$r['id']]);
        echo 'Failed pt '.$r['id'].' - '.($res['error'] ?? 'unknown')."\n";
    }
}
?>