# [BUG-002] — Registration Form Provides No Feedback on Submission

| Field | Value |
|---|---|
| **ID** | BUG-002 |
| **Filed** | 2026-06-15 |
| **Status** | OPEN |
| **Severity** | MAJOR |
| **Branch** | feature/bug-002-registration-form-no-feedback |

---

## Bug Description

After a user submits the front-end registration form (`[youzify_register]` shortcode, page `/register-2/`), the form silently resets to empty with no confirmation message, no error message, and no redirect. Users have no indication that their registration was received or that they should check their email for an activation link. This affects all visitors attempting to register via the site's registration page.

A secondary failure was also observed: if a user account is deleted via WP-Admin → Users and the same username/email is immediately re-used for registration, the form silently fails and no record is written to `wp_signups`. The cause is unconfirmed but may involve orphaned records in BuddyPress extended profile tables or a plugin-level block.

---

## Background — Issues Discovered and Resolved During Investigation

The following were identified and fixed during diagnosis on 2026-06-15. They are documented here as context:

- **Youzify Membership System was disabled** (caused by a local→live database import overwriting the setting). Fixed by re-enabling via WP-Admin → Youzify → Membership System Settings. This is now documented as Step 8 of the development workflow memo.
- **`wp_signups` table was missing from the live database** (pre-existing gap — the table was never created as BuddyPress registration had not been previously exercised). Fixed by manually creating the table via phpMyAdmin. See Notes for SQL.
- **Birthday field blocked HTML5 form validation** (Youzify hides native `<select>` elements but leaves `required` attribute on them). Fixed by adding a JavaScript snippet via WPCode that removes `required` from hidden selects on submit.

These fixes enabled registration to function end-to-end — confirmed by a successful test registration (user created in `wp_signups`, activation email received and clicked, user active in `wp_users`). The missing feedback message remains the outstanding issue.

---

## Reproduction

**Conditions:** Any unauthenticated visitor. Observed on desktop (Chrome).

**Steps:**
1. Navigate to `invictagc.com/register-2/`
2. Fill in all required fields (username, email, password, name, birthday, hometown)
3. Click **Sign Up**

**Expected:** A confirmation message is displayed — e.g. "An activation email has been sent to your address. Please click the link to activate your account."  
**Actual:** The form fields clear and the page returns to the empty registration form. No message of any kind is shown. The registration may or may not have succeeded — the user has no way to tell.

---

## Implementation Plan

1. Attempt registration with completely fresh credentials (not previously used on the site) and observe whether a success message appears — this will confirm whether the issue is universal or conditional
2. Check Youzify settings for a registration success redirect page or confirmation message configuration (Youzify → General Settings / Membership System Settings)
3. If no Youzify setting is available, investigate whether the success state handler in Youzify's registration JavaScript is firing correctly — check browser console after a successful submission
4. As a fallback: configure a registration redirect via Youzify settings or add a WPCode snippet to redirect to a custom "check your email" page after form submission
5. Investigate the re-registration failure after account deletion — check BuddyPress extended profile tables (`bp_xprofile_data`) for orphaned records tied to the deleted user's ID, and check whether Loginizer or Login Security Recaptcha is throttling repeated attempts from the same IP
6. Test end-to-end: register → receive activation email → click link → confirm user appears in WP-Admin → Users → confirm user can log in

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

This table must not be overwritten by future local→live database imports. Add to the post-import checklist in the development workflow memo if wp_signups is absent after any future import.
