<?php
define('APP_INIT', true);
require_once 'config.php';
require_admin();
require 'inc/header.php';
$accounts = $pdo->query('SELECT * FROM social_accounts ORDER BY created_at DESC')->fetchAll();
$comments = $pdo->query('SELECT pc.*, pt.provider_post_id, sa.provider FROM post_comments pc LEFT JOIN post_targets pt ON pc.post_target_id = pt.id LEFT JOIN social_accounts sa ON pt.social_account_id = sa.id ORDER BY pc.created_at DESC LIMIT 200')->fetchAll();
?>
<div class="panel"><h2>Connected Accounts</h2>
<?php if (empty($accounts)) echo '<p>No connected accounts. Use the providers links to connect.</p>'; else foreach($accounts as $a): ?>
<div style="padding:8px;border-bottom:1px solid #eee;"><strong><?=htmlspecialchars($a['provider'].' / '.$a['account_name'])?></strong><div style="font-size:12px;color:#666"><?=htmlspecialchars($a['created_at'])?></div></div>
<?php endforeach; ?>
</div>

<div class="panel"><h2>Comments (unified)</h2>
<?php if (empty($comments)) echo '<p>No comments yet.</p>'; else foreach($comments as $c): ?>
<div style="padding:8px;border-bottom:1px solid #eee;"><strong><?=htmlspecialchars($c['commenter_name']?:'Anonymous')?></strong> — <?=htmlspecialchars($c['created_at'])?><div><?=nl2br(htmlspecialchars($c['comment']))?></div></div>
<?php endforeach; ?>
</div>

<?php require 'inc/footer.php'; ?>
