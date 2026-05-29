# InvictaGC — WP Migrate DB Integration Roadmap

## Objective
Enable version-controlled syncing of WordPress database configuration between local (Laragon) and live (GreenGeeks) environments, so that database-level changes (plugin settings, profile tab structure, menu config, widget settings, etc.) can be tested locally and deployed to production reliably.

---

## Background
Git tracks code (wp-content/themes, wp-content/plugins). It does not track database state. Configuration changes made through the WordPress admin (ProfileGrid tabs, BuddyPress settings, menu structure, etc.) live in the database and are currently invisible to version control. WP Migrate DB solves this by providing controlled, URL-aware database export/import between environments.

---

## Decision: Free vs Pro

| | Free | Pro |
|---|---|---|
| Export DB with find/replace | Yes | Yes |
| Import via phpMyAdmin (manual) | Yes | Yes |
| Direct push/pull between sites | No | Yes |
| Selective table migration | No | Yes |
| Media file migration | No | Yes |
| CLI support | No | Yes |

**Recommendation:** Start with the free version to validate the workflow. Upgrade to Pro if the manual import step becomes a bottleneck.

---

## Phase 1 — Installation

### Step 1 — Install on local site
1. Download WP Migrate DB from wordpress.org/plugins/wp-migrate-db
2. Place the plugin folder in `C:\laragon\www\invictagc\wp-content\plugins\`
3. In HeidiSQL → `wp_options` → confirm `siteurl` and `home` are set to `http://localhost/invictagc`
4. Navigate to `http://localhost/invictagc/wp-admin` → Plugins → activate WP Migrate DB

### Step 2 — Install on live site
1. Log into `invictagc.com/wp-admin`
2. Plugins → Add New → search "WP Migrate DB" → Install → Activate

---

## Phase 2 — Workflow: Live → Local (pulling live config to local)

Use this when starting a new feature branch and you need the latest live configuration locally.

### Step 3 — Export from live site
1. On live site: WP-Admin → Migrate DB → Export
2. Under **Find**, enter: `https://invictagc.com`
3. Under **Replace**, enter: `http://localhost/invictagc`
4. Add a second find/replace if needed for any hardcoded media paths
5. Click **Export** — downloads a `.sql.gz` file

### Step 4 — Import to local
1. Open HeidiSQL → right-click `invictagc` → **Drop** → confirm
2. Right-click connection → **Create new** → **Database** → name `invictagc`
3. Select the empty `invictagc` database → **File** → **Run SQL file** → select the exported `.sql.gz` file
4. Verify the site loads correctly at `http://localhost/invictagc`

---

## Phase 3 — Workflow: Local → Live (deploying config changes to live)

Use this after a feature branch has been tested locally and is ready to deploy.

### Step 5 — Export from local site
1. On local site: WP-Admin → Migrate DB → Export
2. Under **Find**, enter: `http://localhost/invictagc`
3. Under **Replace**, enter: `https://invictagc.com`
4. Click **Export** — downloads a `.sql.gz` file

### Step 6 — Import to live site
1. Log into GreenGeeks cPanel → phpMyAdmin
2. Select the live database → **Import** tab
3. Choose the exported `.sql.gz` file → click **Go**
4. Verify the live site at `https://invictagc.com` looks correct

---

## Phase 4 — Integrate into development workflow

### Step 7 — Update CLAUDE.md
Update the implementation workflow in CLAUDE.md to replace the manual HeidiSQL URL-swap steps with WP Migrate DB export/import:

- **Start of feature branch:** export live DB via WP Migrate DB (live→local) and import locally instead of manually editing `siteurl`/`home`
- **Pre-merge:** export local DB via WP Migrate DB (local→live) as the deployment artefact, replacing the manual URL revert step
- The pre-merge database backup step remains — export a plain `.sql` backup to `C:\Users\danil\Downloads\` before any import to live

### Step 8 — Update site-structure.md
Add WP Migrate DB to the plugin inventory in `docs/site-structure.md`.

---

## Phase 5 — Validation

### Step 9 — End-to-end test
1. Make a visible configuration change locally (e.g. add a test tab to a profile group)
2. Export local DB via WP Migrate DB (local→live find/replace)
3. Import to live site via phpMyAdmin
4. Confirm the change appears on `invictagc.com`
5. Reverse the test change and repeat the export/import cycle to confirm clean rollback

### Step 10 — Document results
Record any issues encountered during the test in a BUG or TWK ticket as appropriate. If the workflow is validated, mark this roadmap as complete.

---

## Status
- [ ] Phase 1 — Installation
- [ ] Phase 2 — Live → Local workflow validated
- [ ] Phase 3 — Local → Live workflow validated
- [ ] Phase 4 — CLAUDE.md and docs updated
- [ ] Phase 5 — End-to-end test passed
