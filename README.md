# Refugee Needs Assessment System

A web-based platform for recording, prioritising, and tracking the humanitarian needs of refugees in a camp setting. Built as a Final Year Project for the BSc in Information and Communication Technology (ICTB) programme at Mzumbe University.

---

## Features

- **Refugee self-registration** — phone number + OTP verification + strong password
- **Refugee login** — phone number and password (no email required)
- **Needs assessment** — staff record needs across five categories with a weighted priority score
- **Priority scoring** — formula-driven score combining urgency, vulnerability indicators, and category weight (max 292.50)
- **Role-based access control** — Admin and Aid Worker roles via Spatie Laravel Permission
- **Reports and analytics** — needs breakdown by category, urgency, and top priority cases
- **Audit trail** — every create / update / delete / login / logout is logged with actor and IP
- **Admin user management** — admins can create and remove staff accounts
- **Staff authentication** — separate email + password login for admin and aid worker accounts

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 (PHP 8.2) |
| Frontend | Blade + Tailwind CSS v4 (via `@tailwindcss/vite`) |
| Build tool | Vite 7 |
| Database | PostgreSQL |
| RBAC | Spatie Laravel Permission v6 |
| JS libraries | jQuery 4, Axios |

## Quick Start

```bash
git clone <repo-url> refugee_needs_system
cd refugee_needs_system

composer install
cp .env.example .env
php artisan key:generate

# configure .env (DB credentials, SMS_BYPASS=true for dev)

php artisan migrate
php artisan db:seed

npm install
composer dev       # starts PHP server + Vite + queue listener concurrently
```

Default admin account created by the seeder:

| Field | Value |
|---|---|
| Email | `admin@refugeesystem.local` |
| Password | `Admin@1234` |

Full setup instructions: see [DEVELOPMENT.md](DEVELOPMENT.md)

## Roles

| Role | Access |
|---|---|
| `admin` | Dashboard, Needs, Reports, Audit Logs, User Management |
| `aid_worker` | Dashboard, Needs, Reports |

## Needs Categories

`food` · `shelter` · `healthcare` · `education` · `protection`

## License

MIT
