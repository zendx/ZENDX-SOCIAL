<?php
require_once __DIR__ . '/ProviderInterface.php';
class TikTokProvider implements ProviderInterface {
    private $config;
    public function __construct(array $config) { $this->config = $config; }
    public function publishPost(array $post): array {
        if (empty($this->config['access_token'])) return ['success'=>false,'error'=>'missing_access_token'];
        if (empty($post['media_url'])) return ['success'=>false,'error'=>'tiktok_requires_media_url'];
        $url = 'https://open-api.tiktok.com/video/upload/?access_token=' . urlencode($this->config['access_token']);
        $payload = json_encode(['source_type'=>'PULL_FROM_URL','video_url'=>$post['media_url']]);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $resp = curl_exec($ch); $err = curl_error($ch); curl_close($ch);
        if ($err) return ['success'=>false,'error'=>$err];
        $data = json_decode($resp,true); $id = $data['data']['item_id'] ?? ($data['data']['publish_id'] ?? null);
        if ($id) return ['success'=>true,'provider_post_id'=>$id];
        return ['success'=>false,'error'=>'upload_failed'];
    }
    public function fetchComments(array $opts = []): array {
        if (empty($this->config['access_token'])) return [];
        $external = $opts['provider_post_id'] ?? null; if (!$external) return [];
        $url = 'https://open.tiktokapis.com/v2/research/video/comment/list/';
        $payload = json_encode(['video_id'=>$external,'cursor'=>0,'count'=>50]);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$this->config['access_token'],'Content-Type: application/json']);
        $resp = curl_exec($ch); $err = curl_error($ch); curl_close($ch);
        if ($err) return [];
        $data = json_decode($resp,true); $out=[];
        foreach (($data['data']['comments'] ?? []) as $c) { $out[]=['id'=>$c['comment_id'],'from'=>$c['author_display_name'],'message'=>$c['text'],'created_time'=>$c['create_time']]; }
        return $out;
    }
    public function fetchAnalytics(array $opts = []): array { return []; }
}
