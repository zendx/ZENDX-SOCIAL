<?php
interface ProviderInterface {
    // publishPost returns ['success'=>bool, 'provider_post_id'=>string, 'error'=>string]
    public function publishPost(array $post): array;
    public function fetchComments(array $opts = []): array;
    public function fetchAnalytics(array $opts = []): array;
}
