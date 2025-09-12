<?php
require_once __DIR__ . '/ProviderInterface.php';
class FacebookProvider implements ProviderInterface {
    private $config;
    public function __construct(array $config) { $this->config = $config; }
    public function publishPost(array $post): array {
        if (empty($this->config['page_access_token']) || empty($this->config['page_id'])) return ['success'=>false,'error'=>'missing_token_or_page'];
        $endpoint = 'https://graph.facebook.com/v15.0/'.$this->config['page_id'].'/feed';
        $fields = http_build_query(['message' => $post['body'] ?? '', 'access_token' => $this->config['page_access_token']]);
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch); $err = curl_error($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
        if ($err) return ['success'=>false,'error'=>$err];
        $data = json_decode($resp,true);
        if (!empty($data['id'])) return ['success'=>true,'provider_post_id'=>$data['id']];
        return ['success'=>false,'error'=>'HTTP '.$code.' - '.$resp];
    }
    public function fetchComments(array $opts = []): array {
        if (empty($this->config['page_access_token'])) return [];
        $external = $opts['provider_post_id'] ?? null; if (!$external) return [];
        $endpoint = 'https://graph.facebook.com/v15.0/'.urlencode($external).'/comments?access_token='.urlencode($this->config['page_access_token']);
        $resp = file_get_contents($endpoint); $data = json_decode($resp, true); $out=[];
        foreach (($data['data'] ?? []) as $c) { $out[]=['id'=>$c['id'],'from'=>$c['from']['name']??'','message'=>$c['message']??'','created_time'=>$c['created_time']??'']; }
        return $out;
    }
    public function fetchAnalytics(array $opts = []): array { return []; }
}
