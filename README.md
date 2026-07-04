# Smart Logistics Tracking Web Application (PHP version)

A browser-based shipment tracking system built with PHP, MySQL, and plain HTML/CSS.

## Modules
1. Authentication Module — admin login/logout (`login.php`, `logout.php`)
2. Shipment Module — create shipments with an auto-generated tracking ID (`create_shipment.php`)
3. Tracking Module — public page for customers to track a shipment (`track_shipment.php`)
4. Status Update Module — admin updates shipment status + remarks (`update_status.php`)
5. Dashboard Module — overview stats and shipment list (`dashboard.php`)

## Tech stack
- Backend: PHP (PDO + MySQL)
- Frontend: HTML, CSS (no framework)
- Database: MySQL

## Folder structure
```
smart-logistics/
├── index.php
├── login.php
├── logout.php
├── dashboard.php
├── create_shipment.php
├── track_shipment.php
├── update_status.php
├── includes/
│   ├── config.php      (DB connection — edit your credentials here)
│   ├── auth.php         (login helper functions)
│   ├── header.php
│   └── footer.php
├── assets/css/style.css
├── setup/create_admin.php   (run once, then delete)
└── sql/schema.sql
```

## Setup (using XAMPP)
1. Start Apache and MySQL in XAMPP.
2. Copy the `smart-logistics` folder into `htdocs`.
3. Open phpMyAdmin and import `sql/schema.sql` (this creates the `smart_logistics` database and its tables).
4. Check `includes/config.php` — the defaults (`root` / no password / `localhost`) match a stock XAMPP install. Change them if yours is different.
5. Visit `http://localhost/smart-logistics/setup/create_admin.php` in your browser and create your admin account.
6. Delete `setup/create_admin.php` once the account is created.
7. Visit `http://localhost/smart-logistics/` to use the app.

## Notes
- Passwords are hashed with PHP's `password_hash()` / verified with `password_verify()` — never stored in plain text.
- All database queries use PDO prepared statements to avoid SQL injection.
- Tracking IDs look like `SL26A1B2C` (SL + year + random code) and are unique.
- The tracking page (`track_shipment.php`) is public on purpose, matching the original workflow: *Admin → Create Shipment → Database → Customer Tracks Shipment*.

## Future scope (from original plan)
- GPS live tracking
- SMS notifications
- Mobile app integration
