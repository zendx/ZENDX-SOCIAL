# ZENDX Social Scheduler

ZENDX Social Scheduler is a plain PHP and MySQL social media scheduling app. It lets an admin connect social accounts, create posts, target one or more connected accounts, and publish scheduled posts through provider adapters.

## Features

- Admin login and session-based authentication
- Initial admin creation through `setup.php`
- Social account connection flow for Facebook, Instagram, Twitter/X, TikTok, and LinkedIn
- Create draft or scheduled posts from the dashboard
- Target a post to multiple connected social accounts
- Background worker for publishing pending scheduled posts
- Comment and analytics data tables with API endpoints for account lists and live comments
- Customer message page
- Password reset and password change flows
- SMTP email support through the bundled PHPMailer-compatible library

## Requirements

- PHP 7.4 or newer
- MySQL 5.7+ or MariaDB 10.3+
- PHP PDO MySQL extension
- A web server that can run PHP, such as Apache through XAMPP
- OAuth app credentials for any social providers you want to connect
- SMTP credentials if you want email delivery

## Project Structure

```text
.
|-- api/                         API endpoints
|-- config/                      Secret configuration templates
|   |-- secrets.example.php
|   `-- secrets.php              Local OAuth and SMTP credentials, not committed
|-- inc/                         Shared header and footer includes
|-- libs/PHPMailer/              Bundled email library
|-- providers/                   Social provider OAuth and publishing adapters
|-- CHANGELOG.md
|-- CONTRIBUTING.md
|-- SECURITY.md
|-- config.example.php           Main config template
|-- config.php                   Local runtime config
|-- dashboard.php                Admin post creation and schedule view
|-- init_db.sql                  Database schema
|-- login.php
|-- register.php
|-- schedule_worker.php          Scheduled publishing worker
|-- setup.php                    First-run admin creation script
`-- style.css
```

## Setup

### 1. Clone or Copy the Project

Place the project in your web server document root. For XAMPP on Windows, this is commonly:

```powershell
C:\xampp\htdocs\zendx_socials\ZENDX-SOCIAL
```

### 2. Create the Database

Import the schema:

```bash
mysql -u root -p < init_db.sql
```

The script creates a database named `social_scheduler` and all required tables.

### 3. Configure the App

Copy the example files:

```bash
cp config.example.php config.php
cp config/secrets.example.php config/secrets.php
```

On Windows PowerShell:

```powershell
Copy-Item config.example.php config.php
Copy-Item config\secrets.example.php config\secrets.php
```

Edit `config.php` for your database connection:

```php
define('DB_HOST', $_ENV['DB_HOST'] ?? '127.0.0.1');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'social_scheduler');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
```

Edit `config/secrets.php` for OAuth and SMTP credentials.

Note: `.env.example` is included for reference, but this project does not load `.env` files by itself. Either edit `config.php`, configure real server environment variables, or add a dotenv loader.

### 4. Create the First Admin User

Open this URL in your browser:

```text
http://localhost/zendx_socials/ZENDX-SOCIAL/setup.php
```

If you use a different virtual host or folder, adjust the URL accordingly.

`setup.php` creates an admin account only when the `users` table is empty. It prints a random password one time. Save it immediately.

After setup, delete or protect `setup.php` before using the app outside local development.

### 5. Log In

Open:

```text
http://localhost/zendx_socials/ZENDX-SOCIAL/login.php
```

Use the admin email shown by setup, normally `admin@example.com`, and the generated password.

## OAuth Configuration

Add provider credentials in `config/secrets.php`.

### Facebook

- Create an app at the Meta developer portal.
- Set the redirect URI to:

```text
https://yourdomain.com/providers/facebook_callback.php
```

### Instagram

- Configure the Instagram app credentials.
- Set the redirect URI to:

```text
https://yourdomain.com/providers/instagram_callback.php
```

### Twitter/X

- Create an app in the X developer portal.
- Set the redirect URI to:

```text
https://yourdomain.com/providers/twitter_callback.php
```

### TikTok

- Create an app in the TikTok developer portal.
- Set the redirect URI to:

```text
https://yourdomain.com/providers/tiktok_callback.php
```

### LinkedIn

- Create an app in the LinkedIn developer portal.
- Set the redirect URI to:

```text
https://yourdomain.com/providers/linkedin_callback.php
```

For local development, use the same path with your local host name if the provider supports localhost redirect URIs.

## SMTP Configuration

Configure SMTP in `config/secrets.php`:

```php
'smtp' => [
    'host' => 'smtp.gmail.com',
    'username' => 'your_email@gmail.com',
    'password' => 'your_app_specific_password',
    'port' => 587,
    'encryption' => 'tls',
    'from_email' => 'noreply@yourdomain.com',
    'from_name' => 'Social Scheduler'
]
```

For Gmail, use an app password rather than your normal account password.

## Running Scheduled Publishing

The dashboard stores scheduled posts and creates pending `post_targets`. Run the worker regularly to publish due targets:

```bash
php schedule_worker.php
```

Example cron entry for Linux:

```cron
*/5 * * * * php /path/to/ZENDX-SOCIAL/schedule_worker.php
```

For Windows development, you can run the command manually or configure it with Task Scheduler.

## Main Pages

- `dashboard.php` - create posts and view recent posts
- `social_accounts_activities.php` - manage connected social accounts
- `customers.php` - view customer messages
- `change_password.php` - change the current user's password
- `forgot_password.php` / `reset_password.php` - password reset flow
- `setup.php` - first-run admin setup

## API Endpoints

- `GET /api/accounts_list.php` - list connected social accounts
- `GET /api/fetch_comments_live.php` - fetch live comments for posts

## Database Tables

- `users`
- `social_accounts`
- `posts`
- `post_targets`
- `post_comments`
- `analytics`
- `password_resets`
- `customer_chats`
- `calendar_events`
- `campaigns`
- `campaign_posts`

## Security Notes

Before production use:

- Delete or protect `setup.php` after creating the first admin.
- Do not commit `config.php`, `config/secrets.php`, `.env`, database dumps, or credentials.
- Use HTTPS for all OAuth redirects and authenticated pages.
- Review provider token storage before production. Tokens are stored in the database and should be encrypted for a real deployment.
- Add CSRF protection to forms.
- Add login rate limiting.
- Keep PHP, MySQL/MariaDB, and server packages patched.
- Configure production error logging so stack traces and secrets are not exposed to users.

## Troubleshooting

### Database Connection Error

- Confirm MySQL or MariaDB is running.
- Check `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS` in `config.php`.
- Confirm the schema was imported successfully.

### Setup Does Nothing

`setup.php` only creates an admin if the `users` table is empty. If users already exist, it exits without creating another admin.

### OAuth Redirect URI Mismatch

- Make sure the provider app redirect URI exactly matches the callback URL.
- Check `http` vs `https`.
- Check the domain, folder path, and callback filename.

### Email Not Sending

- Check SMTP settings in `config/secrets.php`.
- For Gmail, use an app password.
- Confirm your server allows outbound SMTP connections.

### Scheduled Posts Do Not Publish

- Run `php schedule_worker.php` and check the output.
- Confirm the post has target accounts.
- Confirm the scheduled time is due.
- Reconnect the social account if tokens expired.

## Contributing

See `CONTRIBUTING.md` for contribution guidelines.

## Security Policy

See `SECURITY.md` for supported security practices and reporting guidance.

## License

This project is provided as-is for educational and commercial use. Add a formal license file if you need explicit open-source licensing terms.
