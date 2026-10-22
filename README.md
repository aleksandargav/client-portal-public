# Client Portal

A PHP/MySQL client portal with role-based access (`super_admin`, `account_manager`, `specialist`, `client`), Argon2id password hashing, PDO prepared queries, CSRF checks on most state-changing handlers, and an activity log. Work in progress: see Status / Roadmap.

## Features present in the code

- Login and logout flow (`public/Login.php`, `public/logout.php`, `src/classes/Auth.php`).
- Role-to-action permission mapping for `super_admin`, `account_manager`, `specialist`, and `client` (`src/classes/Permission.php`).
- Client, task, comment, and user database operations (`src/classes/Client.php`, `Task.php`, `Comment.php`, `User.php`).
- PHP action handlers for creating clients (boards) and tasks, adding comments, and changing task status (`actions/`).
- Activity-log database writes for task creation and status changes (`src/classes/Logger.php`, `actions/task_create_action.php`, `actions/task_move_status_action.php`).

Some linked board and task pages are not included, so these code-level operations do not amount to a complete, usable portal interface.

## Technology and structure

- PHP with PDO and MySQL.
- `public/` contains the web entry points.
- `actions/` contains request handlers.
- `database/schema.sql` defines the MySQL tables and local demo accounts.
- `src/classes/`, `src/helpers/`, and `src/views/` contain data access, authentication/permission logic, helpers, and view fragments.

## Security notes

- Role-based permission checks are mapped in `src/classes/Permission.php` and enforced through `src/classes/Auth.php` and selected handlers in `actions/`.
- CSRF token generation and comparison are implemented in `src/helpers/Security.php`; the login, client creation, task creation, and comment handlers use the checks.
- Password registration hashes with Argon2id and login verifies the stored hash in `src/classes/Auth.php`.
- PDO connection options and prepared queries are used in `src/config/Database.php` and the database classes in `src/classes/`.
- Session cookie settings are configured in `src/config/config.php`; login regenerates the session ID in `src/classes/Auth.php`. The current `session.cookie_secure` value is `0`; set it to `1` when serving the application over HTTPS.
- Activity-log writes are implemented in `src/classes/Logger.php` for task creation and status changes. This is not a general audit trail.

These are implementation notes, not a security audit or a claim that every endpoint is protected.

## Local installation

Requirements: PHP with PDO MySQL and Argon2id support, MySQL, and Git.

1. Clone the repository and enter its directory:

   ```sh
   git clone https://github.com/aleksandargav/client-portal-public.git client-portal-public
   cd client-portal-public
   ```

2. Copy the environment example to the path currently read by the application, then set the database credentials:

   ```sh
   cp .env.example src/config/.env
   ```

   Set `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS` in `src/config/.env`.

3. Create the database named `client_portal` (or adjust `DB_NAME` to match your database), then import the schema:

   ```sh
   mysql -u root -p -e "CREATE DATABASE client_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   mysql -u your_db_user -p client_portal < database/schema.sql
   ```

4. Start PHP's built-in development server:

   ```sh
   php -S localhost:8000 -t public
   ```

5. Open `http://localhost:8000/` and use the local demo account:

   - Admin: `admin@example.com`
   - Manager: `manager@example.com`
   - Password for both: `demo1234`

   The demo password is for local testing only; change it before any shared or deployed use.

No `composer.json` is present, so Composer installation is not currently part of this project setup.

## Status / Roadmap

This project is in development and is not production-ready. Stylesheet files are not included in this repository; the current templates expect `/css/style.css` from `public/dashboard.php` and `public/Login.php`. Before production, reconcile the database schema with application behavior and complete/verify the missing pages, routing, and endpoint protections. `actions/task_move_status_action.php` does not yet validate a CSRF token.

## Screenshots

Screenshots will be added here.

## Kratak rezime

PHP/MySQL portal je u razvoju; nedostaju stilovi i pojedine stranice, a pre produkcije potrebno je uskladiti šemu i aplikacionu logiku.
