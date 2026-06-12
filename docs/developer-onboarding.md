# Developer Onboarding — Local Environment Setup

This guide sets up a local development environment for InvictaGC from scratch. By the end you will have a fully functional local copy of the site running at `http://localhost/invictagc`.

---

## Prerequisites

- Git installed and access to the GitHub repository (`Er4din/invictaGC`)
- Access to the live site WP-Admin (`invictagc.com/wp-admin`) — needed to export the database

---

## Step 1 — Install Laragon

1. Download Laragon from [laragon.org](https://laragon.org/download/) — use the **Full** version
2. Run the installer as Administrator
3. Accept all defaults — install to `C:\laragon\`
4. Launch Laragon and click **Start All**

Laragon provides Apache, MySQL, PHP, and HeidiSQL. No further configuration is needed at this stage.

---

## Step 2 — Clone the repository

Clone into Laragon's web root so the site is served at `http://localhost/invictagc`:

```
git clone https://github.com/Er4din/invictaGC.git C:\laragon\www\invictagc
```

Then switch to the `development` branch:

```
cd C:\laragon\www\invictagc
git checkout development
```

---

## Step 3 — Download WordPress core

The repository only contains `wp-content`. WordPress core files are excluded from git and must be downloaded separately.

1. Download WordPress from [wordpress.org/download](https://wordpress.org/download/) — get the `.zip`
2. Extract the zip — you will get a `wordpress/` folder
3. Copy everything **except** the `wp-content/` folder from the extracted `wordpress/` folder into `C:\laragon\www\invictagc\`

The result should be that `C:\laragon\www\invictagc\` contains both the WordPress core files (`wp-admin/`, `wp-includes/`, `wp-login.php`, etc.) and the `wp-content/` folder from the repository.

---

## Step 4 — Create wp-config.php

Create a new file at `C:\laragon\www\invictagc\wp-config.php` with the following content. Replace the `AUTH_KEY` and related salt values by generating fresh ones at [api.wordpress.org/secret-key/1.1/salt/](https://api.wordpress.org/secret-key/1.1/salt/).

```php
<?php
define( 'DB_NAME', 'invictagc' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// Generate fresh values at https://api.wordpress.org/secret-key/1.1/salt/
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

$table_prefix = 'wp_';

define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', true );

// Prevents plugins from making external HTTP requests — dramatically reduces local load time
define('WP_HTTP_BLOCK_EXTERNAL', true);
define('DISABLE_WP_CRON', true);

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}
require_once ABSPATH . 'wp-settings.php';
```

---

## Step 5 — Increase PHP memory limit

1. Open Laragon → right-click the tray icon → **PHP** → **php.ini**
2. Find the line `memory_limit` and set it to:
   ```
   memory_limit = 1024M
   ```
3. Save the file and restart Laragon (**Stop All** → **Start All**)

---

## Step 6 — Create the local database

1. Open HeidiSQL (available from the Laragon menu)
2. Connect using the default session (host: `127.0.0.1`, user: `root`, password: empty)
3. Right-click the connection → **Create new** → **Database**
4. Name it `invictagc`, set collation to `utf8mb4_unicode_ci` → **OK**

---

## Step 7 — Import the database

An existing developer must export the current live database via WP Migrate and share the `.sql.gz` file with you.

**Existing developer:** on the live site go to WP-Admin → Tools → Migrate DB → Export, set Find: `https://invictagc.com` / Replace: `http://localhost/invictagc`, export and share the file.

**You:** once you have the file:
1. In HeidiSQL, select the empty `invictagc` database
2. **File** → **Run SQL file** → select the `.sql.gz` file
3. Confirm auto-detect encoding if prompted

---

## Step 8 — Activate WP Migrate Lite locally

1. Navigate to `http://localhost/invictagc/wp-admin`
2. Log in with your WordPress credentials
3. Go to **Plugins** → find **WP Migrate** → click **Activate**

---

## Step 9 — Disable LiteSpeed Cache HTTPS redirect

LiteSpeed Cache forces HTTPS on the live site, which breaks local navigation. Disable it locally:

1. In WP-Admin → **Plugins** → deactivate **LiteSpeed Cache**

The plugin remains in the codebase and active on the live site — only deactivate it in your local WordPress admin.

---

## Step 10 — Verify

Navigate to `http://localhost/invictagc`. The site should load correctly and all internal links should work without SSL errors.

---

## You are now set up

Refer to `CLAUDE.md` in the repository root for the full development workflow, branching strategy, and ticketing system.
