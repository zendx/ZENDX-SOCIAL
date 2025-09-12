<?php
define('APP_INIT', true);
require_once 'config.php';
require_admin();
require 'inc/header.php';
$chats = $pdo->query('SELECT * FROM customer_chats ORDER BY created_at DESC LIMIT 200')->fetchAll();
?>
<div class="panel"><h2>Customer Chats</h2>
<?php if (empty($chats)) echo '<p>No chats yet.</p>'; else foreach($chats as $c): ?>
<div style="padding:8px;border-bottom:1px solid #eee;"><strong><?=htmlspecialchars($c['customer_name']?:$c['customer_email'])?></strong><div><?=nl2br(htmlspecialchars($c['message']))?></div><div style="font-size:12px;color:#666"><?=htmlspecialchars($c['created_at'])?></div></div>
<?php endforeach; ?>
</div>
<?php require 'inc/footer.php'; ?>
