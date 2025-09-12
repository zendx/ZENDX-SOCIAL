<?php
require_once __DIR__ . '/ProviderInterface.php';
class TwitterProvider implements ProviderInterface {
    private $config;
    public function __construct(array $config) { $this->config = $config; }
    public function publishPost(array $post): array {
        if (empty($this->config['access_token'])) return ['success'=>false,'error'=>'missing_access_token'];
        $text = $post['body'] ?? '';
        $payload = json_encode(['text'=>$text]);
        $ch = curl_init('https://api.twitter.com/2/tweets');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$this->config['access_token'],'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch); $err = curl_error($ch); curl_close($ch);
        if ($err) return ['success'=>false,'error'=>$err];
        $data = json_decode($resp,true);
        if (!empty($data['data']['id'])) return ['success'=>true,'provider_post_id'=>$data['data']['id']];
        return ['success'=>false,'error'=>'post_failed'];
    }
    public function fetchComments(array $opts = []): array {
        if (empty($this->config['access_token'])) return [];
        $external = $opts['provider_post_id'] ?? null; if (!$external) return [];
        $query = 'conversation_id:'.$external.' -is:retweet';
        $url = 'https://api.twitter.com/2/tweets/search/recent?query='.urlencode($query).'&tweet.fields=author_id,created_at';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$this->config['access_token']]);
        $resp = curl_exec($ch); $err = curl_error($ch); curl_close($ch);
        if ($err) return [];
        $data = json_decode($resp,true); $out=[];
        foreach (($data['data'] ?? []) as $t) { $out[]=['id'=>$t['id'],'author'=>$t['author_id'],'text'=>$t['text'] ?? '','created_at'=>$t['created_at'] ?? '']; }
        return $out;
    }
    public function fetchAnalytics(array $opts = []): array { return []; }
}
