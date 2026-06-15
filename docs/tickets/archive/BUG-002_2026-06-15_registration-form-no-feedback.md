# [BUG-002] — Registration Form Provides No Feedback on Submission

| Field | Value |
|---|---|
| **ID** | BUG-002 |
| **Filed** | 2026-06-15 |
| **Status** | RESOLVED |
| **Resolved** | 2026-06-15 |
| **Severity** | MAJOR |
| **Branch** | feature/bug-002-registration-form-no-feedback |

---

## Bug Description

The front-end registration form at `/register-2/` has never successfully registered a user through the BuddyPress pipeline. Every attempt results in the form silently resetting with no confirmation message, no error, and no record written to `wp_signups` or `wp_users`. All confirmed successful registrations during investigation were made via a workaround (navigating to `invictagc.com/wp-admin`, which surfaces the WordPress native login page with a Register link that bypasses the Youzify/BuddyPress pipeline).

**Root cause identified:** BuddyPress is configured to process registrations at `/register/`. The Youzify `[youzify_register]` shortcode was placed on a separate page at `/register-2/`. When the form on `/register-2/` submits, BuddyPress does not recognise the request as a registration and silently ignores it — nothing is written to the database.

Navigating directly to `/register/` renders the identical Youzify registration form (Youzify's Membership System overrides BuddyPress's template on the designated registration page). Registration submitted from `/register/` is expected to be processed correctly by BuddyPress. This has not yet been confirmed by a successful test at the time of filing.

---

## Background — Issues Discovered and Resolved During Investigation

The following were identified and fixed during diagnosis on 2026-06-15. They are documented here as context:

- **Youzify Membership System was disabled** — caused by a local→live database import overwriting the setting. Fixed by re-enabling via WP-Admin → Youzify → Membership System Settings. Now documented as Step 8 of the development workflow memo.
- **`wp_signups` table was missing from the live database** — pre-existing gap; the table was never created as BuddyPress registration had never been exercised on this site. Fixed by manually creating the table via phpMyAdmin. See Notes for SQL.
- **Birthday field blocked HTML5 form validation** — Youzify hides native `<select>` elements but leaves the `required` attribute on them. When the form submitted, the browser found hidden required fields it could not focus, and blocked submission. Fixed by adding a JavaScript snippet via WPCode that removes `required` from hidden selects on submit.
- **BuddyPress slug conflict** — Attempting to change the BuddyPress registration slug to `register-2` resulted in `register-2-2` (BuddyPress auto-increments to avoid conflict with the existing WordPress page at that slug). This approach was abandoned.

Note: two accounts (NoFearOfFire, Evilbrennan) appear in `wp_signups` with `active=1`. Both were registered via the wp-admin workaround, not the Youzify front-end form. These are not evidence that the front-end form worked.

---

## Reproduction

**Conditions:** Any unauthenticated visitor. Observed on desktop (Chrome).

**Steps:**
1. Navigate to `invictagc.com/register-2/`
2. Fill in all required fields (username, email, password, name, birthday, hometown)
3. Click **Sign Up**

**Expected:** Registration is processed, a record is written to `wp_signups`, and a confirmation message or activation email is sent.
**Actual:** The form fields clear, no record is created anywhere in the database, and no message is shown. The failure is silent and universal — affects all users, all credentials.

---

## Investigation Findings — BuddyPress Pipeline Abandoned

Further testing on 2026-06-15 confirmed that registration also fails silently when submitted from `/register/` (the BuddyPress-designated page). Additional checks ruled out:

- **"Anyone can register" disabled** — confirmed ON in WP-Admin → Settings → General
- **BuddyPress Register component inactive** — no such component exists separately in this version; all 10 components are active

The Youzify/BuddyPress registration pipeline has never successfully created an account on this site through any front-end path. The root cause of the AJAX-level failure was not fully identified. Given the number of layers involved (Youzify Membership System → Youzify AJAX handler → BuddyPress `bp_core_register_account()` → `wp_signups`), the decision was made to replace the entire pipeline rather than continue diagnosing it.

**Decision:** Replace the Youzify/BuddyPress registration and login pipeline with WordPress native authentication, surfaced on the front end via the **Theme My Login** plugin. WordPress native registration is confirmed working (both existing accounts were created via this path). BuddyPress automatically picks up any WordPress-registered user as a community member via the `user_register` hook — profiles, activity streams, and friend connections are unaffected.

---

## Implementation Plan

### Phase 1 — Install Theme My Login
1. WP-Admin → Plugins → Add New → search **Theme My Login** → Install → Activate
2. Note the URLs of the login and register pages it creates automatically (typically `/login/` and `/register/`)

### Phase 2 — Replace shortcodes on existing pages
3. Edit the existing Login page: remove `[youzify_login]`, replace with Theme My Login's login shortcode (`[theme-my-login]` or as documented by the plugin)
4. Edit or delete `/register-2/`: replace content with Theme My Login's register shortcode, or delete the page entirely and use the page Theme My Login created
5. Update BuddyPress page assignment: WP-Admin → Settings → BuddyPress → Pages → set **Register** to the Theme My Login register page

### Phase 3 — Update navigation
6. WP-Admin → Appearance → Menus → update the **Login** and **Register** nav links to point to the new Theme My Login page URLs

### Phase 4 — Disable Youzify Membership System
7. WP-Admin → Youzify → Membership System Settings → toggle **Activate Membership System** OFF → Save Changes
8. Update Step 8 in `docs/development-workflow-memo.md` — this step is no longer required after database imports; remove or revise it to reflect that Youzify Membership System should remain OFF

### Phase 5 — Test
9. Register a new account via the front-end register page — confirm the account appears immediately in WP-Admin → Users (WordPress native registration does not require email activation; the user receives a generated password by email)
10. Log in via the front-end login page — confirm successful authentication
11. Confirm a BuddyPress member profile was created for the new account
12. Test as a logged-out visitor: confirm login and register pages are accessible and functional

### Phase 6 — Cleanup
13. Delete the `/register-2/` page if it still exists
14. Close this ticket and update Status to RESOLVED

---

## Notes

SQL used to recreate the missing `wp_signups` table (run in phpMyAdmin against `teamninj_wp830`):

```sql
CREATE TABLE `wp_signups` (
  `signup_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `domain` varchar(200) NOT NULL DEFAULT '',
  `path` varchar(100) NOT NULL DEFAULT '',
  `title` longtext NOT NULL,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `activation_key` varchar(50) NOT NULL DEFAULT '',
  `meta` longtext,
  PRIMARY KEY (`signup_id`),
  KEY `activation_key` (`activation_key`),
  KEY `user_email` (`user_email`),
  KEY `user_login_email` (`user_login`,`user_email`),
  KEY `domain_path` (`domain`(140),`path`(51))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

This table must not be overwritten by future local→live database imports. If `wp_signups` is absent after any future import, recreate it using the above SQL before testing registration.
