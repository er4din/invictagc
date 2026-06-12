# InvictaGC — Site Structure

## Overview
**Site:** InvictaGC — Invicta Gaming Community  
**Live URL:** https://invictagc.com  
**Platform:** WordPress (self-hosted, GreenGeeks shared hosting)  
**Purpose:** Gaming community hub — league management, tournaments, events, member profiles, leaderboards

---

## Environment

| | Local | Production |
|---|---|---|
| URL | http://localhost/invictagc | https://invictagc.com |
| Files | `C:\laragon\www\invictagc\` | GreenGeeks `public_html/` |
| DB | HeidiSQL → `invictagc` | GreenGeeks cPanel phpMyAdmin |
| Server | Laragon (Apache + MySQL 8.4) | GreenGeeks shared hosting |
| PHP | 8.2.x | 8.x |

---

## Theme

**Active:** PressBook v2.1.8 (`wp-content/themes/pressbook/`)  
**Author:** ScriptsTown  
**Child themes present but inactive:** janey-press, news-host, newsup, prime-fashion-magazine

### Layout
- Dual sidebar support (left/right configurable)
- Three menu locations: primary nav, top bar nav, social links
- Footer widget areas

### Page Templates (`page-templates/`)
| File | Layout |
|---|---|
| `full.php` | Full width, no sidebar |
| `large.php` | Large content area |
| `medium.php` | Medium content area |
| `small.php` | Small content area |
| `sidebar.php` | Sidebar layout |

### Key Theme Files
- `functions.php` — enqueues, theme support declarations
- `header.php` / `footer.php` — global header/footer
- `sidebar.php` / `sidebar-left.php` — sidebar templates
- `inc/core/` — core theme logic
- `inc/libs/` — utility libraries
- `inc/vendor/` — third-party dependencies

---

## Navigation

### Primary Menu
| Item | Notes |
|---|---|
| Home | Front page |
| My Profile | Dropdown — user profile pages (ProfileGrid) |
| Users | Dropdown — member directory |
| Games | Dropdown — game-specific pages |
| Schedule | Events calendar |
| About | Dropdown — site info pages |
| Rules | Static page |
| Links | Static page |
| Registration | Member registration |

### Top Bar
- BGA Group (external link)
- Discord Server (external link — WP Discord Invite)

---

## Plugin Inventory

### Community & Profiles
| Plugin | Slug | Role |
|---|---|---|
| BuddyPress | `buddypress` | Core social layer — activity streams, groups, members |
| ProfileGrid | `profilegrid-user-profiles-groups-and-communities` | User profiles, groups, communities |
| ProfileGrid — Custom Tabs | `...-profilegrid-custom-profile-tabs` | Adds custom tabs to profiles |
| ProfileGrid — Display Name | `...-profilegrid-display-name` | Custom display name logic |
| ProfileGrid — Frontend Group Manager | `...-profilegrid-frontend-group-manager` | Frontend group admin |
| ProfileGrid — Instagram | `...-profilegrid-instagram-integration` | Instagram feed on profiles |
| ProfileGrid — myCred | `...-ProfileGrid-myCred` | Points integration bridge |
| ProfileGrid — Profile Visitors | `...-profilegrid-profile-visitors` | Track profile views |
| ProfileGrid — Social Connect | `...-profilegrid-social-connect` | Social login/connect |
| FrontPage Buddy | `frontpage-buddy` | BuddyPress front page customisation |
| MediaPress | `mediapress` | Media galleries for BuddyPress members |
| BP Better Messages | `bp-better-messages` | Private messaging for BuddyPress |

### Gamification & Points
| Plugin | Slug | Role |
|---|---|---|
| myCred | `mycred` | Points/credits engine |
| myCred — Badges | (addon) | Badge awards |
| myCred — Ranks | (addon) | Rank system |
| myCred — Banking | (addon) | Interest/banking on points |
| myCred — Notifications | (addon) | Point event notifications |
| myCred — Email Notices | (addon) | Email on point events |
| myCred — Stats | (addon) | Points statistics |

### Tournaments & Leagues
| Plugin | Slug | Role |
|---|---|---|
| Tournamatch | `tournamatch` | Tournament & league management |
| Tournamatch ProfileGrid Bridge | `tournamatch-profilegrid-bridge` | Links Tournamatch to ProfileGrid profiles |
| TRN Profile Social Icons | `trn-profile-social-icons` | Social icons on tournament profiles |

### Events
| Plugin | Slug | Role |
|---|---|---|
| The Events Calendar | `the-events-calendar` | Event creation and display |
| Event Tickets | `event-tickets` | Ticketing for events |
| Events Happening Now | `tribe-ext-events-happening-now-1.1.1` | Extension: highlights active events |

### Authentication & Security
| Plugin | Slug | Role |
|---|---|---|
| Loginizer | `loginizer` | Brute force protection, login logs |
| Login Security reCAPTCHA | `login-security-recaptcha` | reCAPTCHA on login |
| Login Customizer | `login-customizer` | Custom login page appearance |
| Akismet | `akismet` | Spam filtering |

### Content & UI
| Plugin | Slug | Role |
|---|---|---|
| Advanced iFrame | `advanced-iframe` | Configurable iframe embeds |
| Advanced iFrame Custom | `advanced-iframe-custom` | Custom files folder for above (not a real plugin) |
| WP Table Builder | `wp-table-builder` | Drag-and-drop table builder |
| Better YouTube Embed Block | `better-youtube-embed-block` | Lite YouTube embeds |
| Classic Editor Addon | `classic-editor-addon` | Classic editor compatibility |
| Contact Form 7 | `contact-form-7` | Form builder |
| Contact Form Query | `contact-form-query` | CF7 query extension |
| Insert Headers and Footers | `insert-headers-and-footers` | Custom code injection (head/body/footer) |
| Kirki | `kirki` | Theme Customizer framework (used by PressBook) |

### Integrations
| Plugin | Slug | Role |
|---|---|---|
| WP Discord Invite | `wp-discord-invite` | Discord invite button/widget (top bar) |

### Performance & Analytics
| Plugin | Slug | Role |
|---|---|---|
| LiteSpeed Cache | `litespeed-cache` | Full-page cache — **disabled locally** |
| WP Statistics | `wp-statistics` | Privacy-friendly analytics |

### Migration / Backup
| Plugin | Slug | Role |
|---|---|---|
| WP Migrate Lite | `wp-migrate-db` | Database export/import with URL find-replace between environments |
| All-in-One WP Migration | `all-in-one-wp-migration` | Site export/import |
| Migrate Guru | `migrate-guru` | Large site migration |

### Must-Use Plugins (`mu-plugins/`)
| File | Role |
|---|---|
| `hostinger-auto-updates.php` | Server auto-update hook (GreenGeeks/Hostinger artefact) |
| `hostinger-preview-domain.php` | Preview domain handling (server artefact) |

---

## Database

**Prefix:** `wp_`  
**Engine:** InnoDB throughout

### Notable Tables
| Table | Owner | Contents |
|---|---|---|
| `wp_options` | WordPress core | Site config, active plugins, theme mods |
| `wp_posts` / `wp_postmeta` | WordPress core | All content and metadata |
| `wp_users` / `wp_usermeta` | WordPress core | Users and profile metadata |
| `wp_mycred_log` | myCred | Points transaction log |
| `wp_tec_events` | The Events Calendar | Event records |
| `wp_tec_occurrences` | The Events Calendar | Recurring event instances |
| `wp_tec_kv_cache` | The Events Calendar | TEC key-value cache |
| `wp_loginizer_*` | Loginizer | Login attempt logs |
| `wp_statistics_*` | WP Statistics | Analytics data |
| `wp_signups` | BuddyPress/WordPress | Pending user registrations |
| `wp_social_users` | ProfileGrid Social Connect | OAuth social login mappings |
| `wp_mpp_logs` | MediaPress | Media activity logs |
| `wp_rsvp*` | Event Tickets | RSVP/ticket data |
| `wp_shepherd_*` | (shepherd plugin) | Onboarding/tour step data |
| `wp_stcfq_*` | Contact Form Query | CF7 form submission records |
| `wp_promag_*` | (promag plugin) | Magazine/content layout data |
| `wp_kirki_forms_*` | Kirki | Customizer form state |

---

## Custom Code

### `advanced-iframe-custom` (`wp-content/plugins/advanced-iframe-custom/`)
- `advanced-iframe-custom.php` — placeholder plugin wrapper (required by Advanced iFrame for custom file storage)
- `hide_fullscreen.html` — custom HTML injected by Advanced iFrame to suppress fullscreen button

### `.htaccess`
Standard WordPress rewrites. `RewriteBase` set to `/invictagc/` — **must be changed to `/` before deploying to production** or updated to match the live server root.

---

## Key Integrations

| Integration | Mechanism |
|---|---|
| Discord | WP Discord Invite widget in top bar; GamiPress Discord API (log table present) |
| BGA (Board Game Arena) | External link in top bar |
| Instagram | ProfileGrid Instagram Integration addon |
| Social Login | ProfileGrid Social Connect addon |
| reCAPTCHA | Login Security reCAPTCHA plugin (Google reCAPTCHA v2/v3) |

---

## Sidebar Widgets (Front Page)
- **Upcoming Events** — The Events Calendar widget
- **Latest Matches** — Tournamatch widget
- **League Standings** — Tournamatch widget
- **Login** — WordPress default login widget
- **Leaderboard** — myCred leaderboard widget

---

## Local Config Notes
- `wp-config.php` is excluded from version control
- Local `wp-config.php` sets `WP_HTTP_BLOCK_EXTERNAL=true` and `DISABLE_WP_CRON=true` for performance
- `.htaccess` `RewriteBase` is `/invictagc/` locally — production value should be `/`
- LiteSpeed Cache is deactivated locally (irrelevant without LiteSpeed server)
