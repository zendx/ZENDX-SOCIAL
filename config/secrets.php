<?php
/**
 * This file is excluded from version control (.gitignore)
 * Copy config/secrets.example.php to config/secrets.php
 * Then add your actual OAuth credentials and SMTP settings
 */

return [
  'facebook' => [
    'client_id' => 'YOUR_FACEBOOK_APP_ID',
    'client_secret' => 'YOUR_FACEBOOK_APP_SECRET',
    'redirect_uri' => 'https://yourdomain.com/providers/facebook_callback.php'
  ],
  'instagram' => [
    'client_id' => 'YOUR_INSTAGRAM_APP_ID',
    'client_secret' => 'YOUR_INSTAGRAM_APP_SECRET',
    'redirect_uri' => 'https://yourdomain.com/providers/instagram_callback.php'
  ],
  'linkedin' => [
    'client_id' => 'YOUR_LINKEDIN_CLIENT_ID',
    'client_secret' => 'YOUR_LINKEDIN_CLIENT_SECRET',
    'redirect_uri' => 'https://yourdomain.com/providers/linkedin_callback.php'
  ],
  'twitter' => [
    'client_id' => 'YOUR_TWITTER_CLIENT_ID',
    'client_secret' => 'YOUR_TWITTER_CLIENT_SECRET',
    'redirect_uri' => 'https://yourdomain.com/providers/twitter_callback.php'
  ],
  'tiktok' => [
    'client_key' => 'YOUR_TIKTOK_CLIENT_KEY',
    'client_secret' => 'YOUR_TIKTOK_CLIENT_SECRET',
    'redirect_uri' => 'https://yourdomain.com/providers/tiktok_callback.php'
  ],
  'smtp' => [
    'host' => 'smtp.example.com',
    'username' => 'your_email@example.com',
    'password' => 'YOUR_SMTP_PASSWORD',
    'port' => 587,
    'encryption' => 'tls',
    'from_email' => 'noreply@yourdomain.com',
    'from_name' => 'Social Scheduler'
  ]
];
