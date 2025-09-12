<?php
require_once __DIR__ . '/ProviderInterface.php';
require_once __DIR__ . '/FacebookProvider.php';
require_once __DIR__ . '/InstagramProvider.php';
require_once __DIR__ . '/TwitterProvider.php';
require_once __DIR__ . '/TikTokProvider.php';
require_once __DIR__ . '/LinkedInProvider.php';

class ProviderFactory {
    public static function providerFor(string $provider, array $config) {
        $p = strtolower($provider);
        switch($p) {
            case 'facebook': return new FacebookProvider($config);
            case 'instagram': return new InstagramProvider($config);
            case 'twitter': return new TwitterProvider($config);
            case 'tiktok': return new TikTokProvider($config);
            case 'linkedin': return new LinkedInProvider($config);
            default: return null;
        }
    }
}
