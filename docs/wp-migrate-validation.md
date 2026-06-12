# WP Migrate — Phase 5 Validation

End-to-end test to confirm the full Live → Local → Live database workflow is reliable before relying on it for development.

## Steps

1. Export live DB via WP Migrate (live→local find/replace) and import locally — ensures test starts from latest live data
2. Make a visible configuration change locally (e.g. add a test tab to a profile group)
3. Export local DB via WP Migrate (local→live find/replace)
4. Import to live site via phpMyAdmin
5. Confirm the change appears on `invictagc.com`
6. Reverse the test change locally and repeat the export/import cycle to confirm clean rollback

## After completing

- Record any issues encountered as a BUG or TWK ticket
- If the workflow is validated without issues, mark Phase 5 complete in `ROADMAP.md` and delete this file
