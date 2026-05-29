<?php
/**
 * OAuth and SMTP Configuration Template
 * 
 * IMPORTANT: Copy this file to secrets.php and fill in your actual credentials
 * NEVER commit secrets.php to version control (it's in .gitignore)
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
    'host' => 'smtp.gmail.com',
    'username' => 'your_email@gmail.com',
    'password' => 'your_app_specific_password',
    'port' => 587,
    'encryption' => 'tls',
    'from_email' => 'noreply@yourdomain.com',
    'from_name' => 'Social Scheduler'
  ]
];
