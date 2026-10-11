# Tasks for Today — TSA2

IT0049: Tasks for Today Management System, Full CRUD and Authentication.
An extension of the TSA1 CodeIgniter 4 project.

## Submission details

- Developer: replace with your full name.
- Section: replace with your section.
- GitHub repository: add your repository URL.
- Hosted application: add your working URL.

Your About page uses `developer.name` and `developer.section` from `.env`.

## Requirements implemented

| Requirement | Implementation |
|---|---|
| Hashed password | users.password VARCHAR(255); password_hash / password_verify |
| Login and logout | GET/POST /login and POST /logout; session ID regenerated on login |
| Public pages | GET /, /tasks, /profile, /about |
| Create | GET /tasks/new and POST /tasks |
| Edit/update | GET /tasks/{id}/edit and POST /tasks/{id}/update |
| Soft delete | POST /tasks/{id}/delete sets is_archived=1; keeps the row |
| Exclude archives | Both listing methods include WHERE is_archived=0 |
| Validation | Required title and date, maximum 150-character title, valid YYYY-MM-DD date, allowed status values |
| Access control | AuthFilter protects every management route; automatic routing disabled |
| Form protection | Session-based CSRF; all state changes use POST; view output escaped |

Code layout: Dashboard renders public pages. Auth handles login/logout.
TaskManager validates task input and calls TaskModel. UserModel reads users.
The public Profile query excludes the password column.

## Upgrade an existing TSA1 installation

1. Stop the PHP development server with Ctrl+C.
2. Copy your complete existing project to a separate backup folder. Export its
   database in phpMyAdmin (select database > Export > Quick > SQL > Export).
3. Copy the CONTENTS of the kit's COPY-INTO-PROJECT folder into the existing
   project root (the folder containing `spark`). Merge folders and replace
   matching files. Do not replace `.env`; this kit does not contain one.
4. Start MySQL. In a terminal at the existing project root, run:

```sh
php spark migrate
php spark db:seed Tsa2Seeder
php spark serve
```

With the default Windows XAMPP path, replace `php` with
`C:\xampp\php\php.exe` in each command.

The migration adds password and is_archived without dropping existing rows.
The seeder adds a password to the existing first demo user. It creates sample
records only when the relevant table is empty. Existing task dates, names,
statuses, and IDs remain intact. The migration is forward-only; use your
backup if you need to revert the schema.

First-time local credentials for the original TSA1 seed:

- Username: `demo_user`
- Password: `Tsa2Demo123!`

If you previously changed the username, use the username in your users table.
An existing hashed password is preserved on reruns unless demo.password is set.
To choose/reset a password, add `demo.password = 'YOUR PRIVATE PASSWORD'` to
`.env`, run `php spark db:seed Tsa2Seeder`, then remove that line. Use 8–72 bytes.
Choose a private password before hosting; the published demo password is for
local setup. Share any assessment login credentials using your course's
submission method rather than committing a private password.

## Fresh installation / cloning the complete repository

Requirements: compatible PHP, Composer, MySQL/MariaDB, and the extensions
required by CodeIgniter, including the MySQL driver. Use CodeIgniter 4.5+.

For a complete project cloned from your submitted repository:

```sh
composer install
```

Copy `env.example` to `.env`. Create an empty MySQL database named tasks_today.
Set database credentials in `.env`, then run the migration and seeder above.
No SQL import is required: migration + seeder fulfill the database submission.

If starting only from this overlay kit, first create the framework project:

```sh
composer create-project codeigniter4/appstarter tasks-for-today --no-dev --prefer-dist
```

Merge COPY-INTO-PROJECT into that directory, then follow the fresh setup steps.

## Manual verification

Follow TEST-CHECKLIST.md. It includes the required logged-out/logged-in access
checks and SQL verification that deleted records remain in the database.

An optional Python 3 HTTP smoke test is included:

```sh
python tests/tsa2_smoke.py
```

Run against your local development server with its default demo credentials.
For a different password, set TSA2_PASSWORD in the testing process environment.
The test creates one uniquely named test task, updates it, and archives it;
the archived test row remains in the database. A failed test may leave it active.
This script needs no third-party Python packages. It does not replace database
inspection of password hashing and archive retention.

Today uses Asia/Manila from `.env`. A date with no tasks correctly shows an
empty dashboard. To deliberately refresh sample dates for a later presentation,
run database/refresh_demo_dates.sql in phpMyAdmin. It shifts all task dates
(including archived rows); it is never run automatically.

## GitHub and deployment

Submit your complete updated project, not only the overlay. Include app/,
public/, spark, composer.json, composer.lock, env.example, migrations, seeder,
README, and test checklist. Keep the starter .gitignore and writable placeholders.
Do not commit `.env`, database credentials, writable session/cache data, or vendor/.
The TSA1 SQL export alone does not contain the TSA2 schema; include the new
migration and seeder files with the repository.

For PHP + MySQL hosting:

1. Deploy the same complete project and run `composer install --no-dev --optimize-autoloader`.
   If Composer is unavailable on the host, upload locally installed vendor/ too.
2. Configure a private `.env` with host DB credentials, real HTTPS base URL,
   `CI_ENVIRONMENT = production`, and `app.appTimezone = 'Asia/Manila'`.
   Retain `app.indexPage = ''` and set your developer details.
3. Point the web document root at public/. Keep app/, vendor/, writable/, and
   .env outside that root. Allow the app to write to writable/.
4. Run migrations and seeder through the host's terminal. If no terminal is
   available, export the already migrated local database and import it into
   the host database. Do not expose a web-accessible migration script.
5. Set a private demo password before deployment, and verify all checklist
   items using the hosted URL.
6. Add repository and hosted URLs to your README and submission.

GitHub Pages cannot run this PHP/MySQL application.

## Documentation

- https://codeigniter4.github.io/userguide/incoming/filters.html
- https://codeigniter4.github.io/userguide/libraries/validation.html
- https://codeigniter4.github.io/userguide/libraries/security.html
- https://codeigniter4.github.io/userguide/dbmgmt/migration.html
