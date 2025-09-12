<?php
return [
  'facebook' => [
    'client_id' => '1504504320702865',
    'client_secret' => '10ef79d69bd6a440a16e79dcbea0546d',
    'redirect_uri' => 'https://facebook.com/providers/facebook_callback.php'
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
    'username' => 'you@example.com',
    'password' => 'YOUR_SMTP_PASSWORD',
    'port' => 587,
    'encryption' => 'tls',
    'from_email' => 'no-reply@yourdomain.com',
    'from_name' => 'Social Scheduler'
  ]
];
