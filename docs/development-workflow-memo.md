# INTERNAL MEMO — Local/Live Development Workflow

**Purpose:** Explains how and why we use a local development environment before pushing changes to the live site.

---

## Why we work this way

All development is done on a local copy of the site running at `http://localhost/invictagc`. This lets us troubleshoot problems, test new features, and try different layouts or configurations without any risk to the live site. Only once changes are tested locally are they pushed to `invictagc.com`.

There are two things that get deployed separately:
- **Code changes** (themes, plugins) — managed via git and deployed manually via FTP
- **Database changes** (site configuration, layout settings, plugin settings) — exported via WP Migrate and imported manually through phpMyAdmin in GreenGeeks cPanel

---

## The core limitation

Any activity that happens on the live site after you export the database — new user registrations, posts, points earned, match results — will not exist in your local copy. If you push a locally-modified database back to the live site, that activity is overwritten and lost.

---

## How to minimise data loss during a database deployment

Once you have decided on and tested your changes locally, follow this sequence:

1. Write down exactly what changes need to be made (the specific settings, tabs, configurations you modified).
2. Announce to users that the site will be down for maintenance for an estimated number of minutes.
3. Enable WordPress maintenance mode on the live site — upload your `.maintenance` file to the `public_html/` root via GreenGeeks cPanel File Manager. This activates the branded maintenance page (`wp-content/maintenance.php`) for all visitors while allowing the database swap to proceed.

   **How to upload the `.maintenance` file via GreenGeeks cPanel:**
   - **Step 3a.** In the GreenGeeks sidebar, expand **Site** and click **File Manager**
   - **Step 3b.** Navigate to `public_html/` in the left panel, then click **Upload** in the toolbar
   - **Step 3c.** Check **Overwrite existing files**, then drag your `.maintenance` file into the drop zone or click **Select File**

   Make sure you navigate to `public_html/` — not `wp-content/` — before clicking Upload. The `.maintenance` file must sit at the root of `public_html/` for WordPress to detect it.

4. Export a fresh copy of the live database via WP Migrate (live → local) and import it locally.
5. Quickly recreate your noted changes on this fresh local copy.
6. Export the updated local database via WP Migrate (local → live) and import it via phpMyAdmin in GreenGeeks cPanel.
7. Remove the `.maintenance` file from `public_html/` via GreenGeeks cPanel File Manager — the site is back online.

The maintenance window should be as short as possible. Having your changes written down in advance (step 1) is what makes steps 5 and 6 fast.

---

## WP Migrate — Find / Replace fields

Every time you export a database with WP Migrate, you must fill in the Find and Replace fields. These tell WP Migrate to rewrite all URLs stored in the database so they match the destination environment. If this is skipped or filled in incorrectly, the imported site will break — pages will fail to load, styles will disappear, or the site will redirect to the wrong URL.

### Live → Local (pulling live data to your machine)

| Field | Value |
|---|---|
| Find | `https://invictagc.com` |
| Replace | `http://localhost/invictagc` |

### Local → Live (pushing your changes to the live site)

| Field | Value |
|---|---|
| Find | `http://localhost/invictagc` |
| Replace | `https://invictagc.com` |

### Common mistake

A common mistake at this step is entering `https://` instead of `http://` in the Replace field for localhost. Since localhost has no SSL certificate, this will cause an SSL error on every page. If this happens, just export the database again with the correct values in the Find and Replace fields.
