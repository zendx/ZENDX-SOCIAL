<?php
require_once __DIR__ . '/ProviderInterface.php';
class LinkedInProvider implements ProviderInterface {
    private $config;
    public function __construct(array $config) { $this->config = $config; }
    public function publishPost(array $post): array {
        if (empty($this->config['access_token']) || empty($this->config['linkedin_id'])) return ['success'=>false,'error'=>'missing_token_or_id'];
        $access_token = $this->config['access_token']; $author = 'urn:li:person:'.$this->config['linkedin_id'];
        $payload = ['author'=>$author,'lifecycleState'=>'PUBLISHED','specificContent'=>['com.linkedin.ugc.ShareContent'=>['shareCommentary'=>['text'=>$post['body'] ?? ''],'shareMediaCategory'=>'NONE']],'visibility'=>['com.linkedin.ugc.MemberNetworkVisibility'=>'PUBLIC']];
        $ch = curl_init('https://api.linkedin.com/v2/ugcPosts');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$access_token,'Content-Type: application/json','X-Restli-Protocol-Version: 2.0.0']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $resp = curl_exec($ch); $err = curl_error($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
        if ($err) return ['success'=>false,'error'=>$err];
        if ($code>=200 && $code<300) return ['success'=>true,'provider_post_id'=>json_decode($resp,true)];
        return ['success'=>false,'error'=>'HTTP '.$code.' - '.$resp];
    }
    public function fetchComments(array $opts = []): array { return []; }
    public function fetchAnalytics(array $opts = []): array { return []; }
}
