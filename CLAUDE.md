# InvictaGC Development Guidelines

## Stack
- WordPress site hosted on GreenGeeks
- Local development via Laragon at `http://localhost/invictagc`
- Local files at `C:\laragon\www\invictagc\`
- Database managed via HeidiSQL (Laragon), database name: `invictagc`
- Backups saved to `C:\Users\danil\Downloads\`

## Branch Structure
- `main` — production-ready code, deployed to live site via GitHub Actions on push
- `development` — integration branch, stable enough for internal use
- `feature/*` — individual feature or task branches, branched from `development`

---

## Workflow Rules

### 1. Starting any new feature or task
- Create a new branch from `development` named after the task (e.g. `feature/registration-form-fix`)
- Sync it with `development` before starting work
- All changes are made and tested locally at `http://localhost/invictagc` before committing

### 2. During development
- Commit regularly as the feature progresses — do not batch all work into a single commit
- Commit messages must be descriptive and reflect the specific change made

### 3. Pre-merge review (before merging feature branch into development)

**Step A — Artifact review**
Review all modified files and check for:
- Placeholder values (e.g. `TODO`, `FIXME`, `REPLACE_WITH_*`, hardcoded test data)
- Temporary files created during development
- Debug code, console logs, or test snippets not needed in production
- Commented-out code left over from testing

If any artifacts are found, warn the developer and list them explicitly before proceeding. Do not merge until the developer approves.

**Step B — Documentation review**
- Check for documentation files describing the internal and external structure of the site
- Review modified code against existing documentation
- Update documentation to reflect any changes made in the feature branch
- Documentation must be current before merging

### 4. Pre-merge backup
After the developer approves merging but before the merge is executed, create local backups:

**Database backup:**
- Export the `invictagc` database from HeidiSQL as a `.sql` file
- Save to `C:\Users\danil\Downloads\`
- Name: `[YYYY-MM-DD] InvictaGC Database`

**Website files backup:**
- Zip the `wp-content/` folder (themes, plugins — all non-generic site files)
- Save to `C:\Users\danil\Downloads\`
- Name: `[YYYY-MM-DD] InvictaGC Website`

### 5. Merging
- Merge the feature branch into `development`
- Delete the feature branch after a successful merge

---

## Promoting Development to Main

- `main` lags behind `development` intentionally
- Only promote `development` to `main` when it is deemed sufficiently stable (no known breaking bugs, features tested end-to-end)
- Before merging `development` into `main`, warn the developer of any known instabilities or unresolved issues on the `development` branch and require explicit approval before proceeding
- Merging `development` into `main` triggers automatic deployment to the live site via GitHub Actions — treat it as a production release

---

## Ticketing System

### Folder structure
```
docs/tickets/
  bugs/       → BUG-XXX_YYYY-MM-DD_ticket-name.md
  tweaks/     → TWK-XXX_YYYY-MM-DD_ticket-name.md
  templates/  → source templates (do not edit directly)
```

### ID format
- Bugs: `BUG-001`, `BUG-002` ... (increment from highest existing ID)
- Tweaks: `TWK-001`, `TWK-002` ... (increment from highest existing ID)

### Filename format
`BUG-003_2026-05-28_login-button-missing.md`  
`TWK-002_2026-05-28_update-leaderboard-styling.md`

### Workflow
- When a bug or tweak is identified, create a ticket using the appropriate template before beginning work
- The ticket's branch name must match the `Branch` field in the ticket header
- Update ticket `Status` field as work progresses: `OPEN → IN PROGRESS → RESOLVED`
- Resolved tickets remain in their folder — do not delete them

### Loading tickets
- Only load the specific ticket being worked on — never load the full tickets directory
- Reference related tickets by ID in the Notes field rather than loading them

---

## Deployment
- Pushing to `main` automatically deploys to GreenGeeks via FTP (GitHub Actions workflow at `.github/workflows/deploy.yml`)
- FTP credentials are stored as GitHub repository secrets: `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`
- Never commit credentials or environment-specific config to the repository
- `wp-config.php` is excluded from version control
