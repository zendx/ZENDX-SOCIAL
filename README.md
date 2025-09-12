Social Scheduler - Full scaffold (fresh build)

Steps to run:
1. Import init_db.sql into your MySQL server (creates database and tables):
   mysql -u root -p < init_db.sql
2. Run setup.php once (visit /setup.php) to create a default admin: admin@example.com / admin123
3. Edit config/secrets.php with your provider credentials and SMTP settings.
4. Ensure config.php DB settings match your environment.
5. Connect social accounts via the provider OAuth links in the nav (Facebook, Twitter, TikTok, LinkedIn).
6. Use Dashboard to create posts and select accounts to post to. Run schedule_worker.php via cron to process scheduled posts.

Notes:
- Embedded a tiny PHPMailer-like implementation (libs/PHPMailer) that can send via SMTP and returns errors when failing.
- For production: secure tokens, encrypt access tokens, implement full error handling, rate limiting and retries, and use validated SDKs for each provider.
