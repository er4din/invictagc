# [BUG-001] — User Emails Publicly Visible on Profiles

| Field | Value |
|---|---|
| **ID** | BUG-001 |
| **Filed** | 2026-05-28 |
| **Status** | OPEN |
| **Severity** | MAJOR |
| **Branch** | feature/bug-001-user-emails-publicly-visible |

---

## Bug Description
Registered user email addresses are publicly visible on user profile pages. Any site visitor — including unauthenticated users — can view the email address of any member by navigating to their profile via the Users tab. This is a privacy violation and exposes personal user data to the public.

## Reproduction
**Conditions:** Any visitor, including unauthenticated (logged-out) users.

**Steps:**
1. Navigate to `invictagc.com`
2. Click **Users** in the primary navigation
3. Click on any member's profile
4. Observe that the user's email address is displayed on the profile page

**Expected:** Email addresses are hidden from public view — visible only to the account owner and site admins.  
**Actual:** Email addresses are visible to all visitors including unauthenticated users.

---

## Implementation Plan
1. Check BuddyPress xProfile field visibility settings — set email field visibility to `Admins Only` or `Only Me`
2. Check ProfileGrid profile field display settings — confirm email is not included in public-facing field groups
3. Check WordPress core user settings — confirm `Show email address publicly` is disabled
4. Verify the Users directory page does not surface email in member listings
5. Test as a logged-out user: confirm email is no longer visible on any profile or member listing
6. Test as the account owner: confirm the user can still see their own email on their profile
7. Test as admin: confirm admin can still view user emails via the WP dashboard

---

## Notes
May involve more than one plugin controlling field visibility (BuddyPress + ProfileGrid both manage profile display). Check both independently.
