<?php
require_once __DIR__ . '/ProviderInterface.php';
class InstagramProvider implements ProviderInterface {
    private $config;
    public function __construct(array $config) { $this->config = $config; }
    public function publishPost(array $post): array {
        // requires instagram_business_id and page_access_token in config
        if (empty($this->config['instagram_business_id']) || empty($this->config['page_access_token'])) return ['success'=>false,'error'=>'missing_instagram_config'];
        $ig = $this->config['instagram_business_id']; $token = $this->config['page_access_token'];
        // Create media container (for images/videos an image_url/video_url is required)
        if (empty($post['media_url'])) return ['success'=>false,'error'=>'instagram_requires_media_url'];
        $endpoint = 'https://graph.facebook.com/v15.0/'.$ig.'/media';
        $fields = http_build_query(['image_url'=>$post['media_url'],'caption'=>$post['body'] ?? '','access_token'=>$token]);
        $resp = file_get_contents($endpoint.'?'.$fields);
        $data = json_decode($resp,true);
        if (empty($data['id'])) return ['success'=>false,'error'=>'media_creation_failed'];
        $creation_id = $data['id'];
        $publish_endpoint = 'https://graph.facebook.com/v15.0/'.$ig.'/media_publish';
        $resp2 = file_get_contents($publish_endpoint.'?'.http_build_query(['creation_id'=>$creation_id,'access_token'=>$token]));
        $d2 = json_decode($resp2,true);
        if (!empty($d2['id'])) return ['success'=>true,'provider_post_id'=>$d2['id']];
        return ['success'=>false,'error'=>'publish_failed'];
    }
    public function fetchComments(array $opts = []): array {
        if (empty($this->config['page_access_token'])) return [];
        $external = $opts['provider_post_id'] ?? null; if (!$external) return [];
        $endpoint = 'https://graph.facebook.com/v15.0/'.urlencode($external).'/comments?access_token='.urlencode($this->config['page_access_token']);
        $resp = file_get_contents($endpoint); $data = json_decode($resp,true); $out=[];
        foreach (($data['data'] ?? []) as $c) { $out[]=['id'=>$c['id'],'from'=>$c['username']??'','message'=>$c['text']??'','created_time'=>$c['timestamp']??'']; }
        return $out;
    }
    public function fetchAnalytics(array $opts = []): array { return []; }
}
