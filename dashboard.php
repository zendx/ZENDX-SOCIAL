<?php
define('APP_INIT', true);
require_once 'config.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_post'])) {
    $title = $_POST['title'] ?? '';
    $body = $_POST['body'] ?? '';
    $media = $_POST['media_url'] ?? null;
    $scheduled_at = $_POST['scheduled_at'] ?: null;
    $status = $scheduled_at ? 'scheduled' : 'draft';
    $stmt = $pdo->prepare('INSERT INTO posts (author_id,title,body,media_url,scheduled_at,status) VALUES (?,?,?,?,?,?)');
    $stmt->execute([$_SESSION['user_id'],$title,$body,$media,$scheduled_at,$status]);
    $post_id = $pdo->lastInsertId();
    if (!empty($_POST['social_account_ids']) && is_array($_POST['social_account_ids'])) {
        $ins = $pdo->prepare('INSERT INTO post_targets (post_id,social_account_id) VALUES (?,?)');
        foreach ($_POST['social_account_ids'] as $sid) { $ins->execute([$post_id,intval($sid)]); }
    }
    header('Location: dashboard.php');
    exit;
}
$accounts = $pdo->query('SELECT id,provider,account_name FROM social_accounts ORDER BY created_at DESC')->fetchAll();
$posts = $pdo->query('SELECT * FROM posts ORDER BY created_at DESC')->fetchAll();
require 'inc/header.php';
?>
<div class="panel">
<h2>Create / Schedule Post</h2>
<form method="post" action="dashboard.php">
<input name="title" placeholder="Title">
<textarea name="body" placeholder="Message body"></textarea>
<input name="media_url" placeholder="Optional media URL (image/video)">
<input name="scheduled_at" placeholder="YYYY-MM-DD HH:MM">
<label>Publish to accounts</label>
<div class="accounts-list">
<?php if (empty($accounts)) echo '<p>No connected accounts</p>'; else foreach($accounts as $a): ?>
<label class="acct"><input type="checkbox" name="social_account_ids[]" value="<?=intval($a['id'])?>"> <?=htmlspecialchars($a['provider'].' / '.$a['account_name'])?></label>
<?php endforeach; ?>
</div>
<button name="save_post">Save & Schedule</button>
</form>
</div>

<div class="panel">
<h2>Recent posts</h2>
<table class="list"><thead><tr><th>ID</th><th>Title</th><th>Scheduled</th><th>Status</th></tr></thead><tbody>
<?php foreach($posts as $p): ?>
<tr><td><?=intval($p['id'])?></td><td><?=htmlspecialchars($p['title'])?></td><td><?=htmlspecialchars($p['scheduled_at'])?></td><td><?=htmlspecialchars($p['status'])?></td></tr>
<?php endforeach; ?>
</tbody></table>
</div>

<?php require 'inc/footer.php'; ?>
