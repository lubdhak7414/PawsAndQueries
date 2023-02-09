<div align="center">

# 🐾 PawsAndQueries

**A raw PHP + MySQL pet adoption platform — built to practice relational SQL.**

![PHP](https://img.shields.io/badge/PHP-8.1-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![PDO](https://img.shields.io/badge/PDO-Prepared%20Statements-success)
![License](https://img.shields.io/badge/License-MIT-blue)

</div>

---

## About

**PawsAndQueries** is a small pet adoption system written in plain PHP — no framework,
no ORM, no magic. It was built as a hands-on exercise in writing **raw SQL** against a
properly normalised relational schema, and it deliberately keeps the data layer front
and centre.

The project demonstrates:

- **Full CRUD** — create accounts and adoption applications, read pet/shelter listings,
  update application status and shelter occupancy, delete lost-pet reports once resolved.
- **Raw SQL relationships** — users, pets, applications, shelters, and lost/found reports
  are joined across nine tables with foreign keys, junction tables, and correlated
  sub-queries (no query builder involved).
- **Secure database practices** — every query that touches user input is a **PDO
  prepared statement** with bound parameters, passwords are hashed with `password_hash()`,
  and all dynamic output is escaped on the way out.

It is intentionally simple and readable — the kind of codebase you can open and follow
end to end.

### Features

| Role  | Capabilities                                                                        |
|-------|-------------------------------------------------------------------------------------|
| User  | Register / log in, browse adoptable pets, apply to adopt, view adopted pets, send pets to / retrieve from shelters, report a lost pet, mark a pet as found |
| Admin | Review and approve / reject applications, manage shelter occupancy, view available, adopted, and lost pets |

---

## Tech Stack

- **PHP 8.1** (typed functions, `match` expressions, `never` return type, null-coalescing assignment)
- **MySQL 8.0 / MariaDB 10.4+**
- **PDO** for all database access
- **Bootstrap 5.2** for layout (loaded via CDN) + a small custom `styles.css`

---

## Prerequisites

- PHP **8.1** or newer
- MySQL **8.0+** or MariaDB **10.4+**
- A web server able to run PHP — Apache/Nginx, a local stack such as **XAMPP** /
  **MAMP** / **Laragon**, or simply PHP's built-in dev server

---

## Database Setup

The full schema and sample data live in [`database.sql`](database.sql). It creates the
`petshel` database, all nine tables (with their foreign keys), and seeds them with demo
pets, users, and shelters.

**Option A — command line**

```bash
mysql -u root -p < database.sql
```

**Option B — phpMyAdmin**

1. Open phpMyAdmin and go to the **Import** tab.
2. Choose `database.sql` and click **Go**.

### Demo credentials

Passwords in the seed data are stored as bcrypt hashes. Use these to log in:

| Role  | Email                        | Password      |
|-------|------------------------------|---------------|
| Admin | `admin@pawsandqueries.test`  | `admin123`    |
| User  | `alice@example.com`          | `password123` |

---

## Installation

```bash
# 1. Clone the repository
git clone https://github.com/<your-username>/PawsAndQueries.git
cd PawsAndQueries

# 2. Import the schema + sample data
mysql -u root -p < database.sql

# 3. (Optional) point the app at your own database
#    Edit config.php, or copy it to config.local.php and override there.

# 4. Serve it with PHP's built-in server
php -S localhost:8000
```

Then open <http://localhost:8000> in your browser.

> **Configuration:** database credentials live in [`config.php`](config.php). The defaults
> (`root` / no password / `localhost`) match a stock XAMPP install. For anything beyond
> local development, copy it to `config.local.php` (git-ignored) and override the values.

---

## How the SQL works

All database access goes through a single shared PDO connection (`db.php`) that is
configured for exceptions, associative fetches, and **real** prepared statements:

```php
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new PDO($dsn, $config['user'], $config['pass'], $options);
```

**Inserting** a new adoption application — values are bound, never concatenated:

```php
$stmt = $pdo->prepare(
    'INSERT INTO adoptionapplication (ApplicationDate, User_id, Pet_id, Status)
     VALUES (?, ?, ?, ?)'
);
$stmt->execute([date('Y-m-d'), $user_id, $pet_id, 'Pending']);
```

**Reading** a user's adopted pets with a join:

```php
$stmt = $pdo->prepare('
    SELECT p.Name, p.Breed, p.Type, op.ApprovalDate
    FROM pet p
    INNER JOIN ownedpets op ON p.Pet_id = op.Pet_id
    WHERE op.User_id = ?
');
$stmt->execute([$user_id]);
$pets = $stmt->fetchAll();
```

**Updating** application status, mapped from a request action with a `match`:

```php
$new_status = match ($action) {
    'approve' => 'Approved',
    'reject'  => 'Rejected',
    default   => null,
};

$stmt = $pdo->prepare('UPDATE adoptionapplication SET Status = ? WHERE Application_id = ?');
$stmt->execute([$new_status, $application_id]);
```

---

## Project Structure

```
.
├── config.php              # Database credentials
├── db.php                  # Shared PDO connection
├── helpers.php             # e() escaping + redirect() helpers
├── database.sql            # Schema + sample data
├── index.php               # Landing page
├── login.php / register.php / logout.php
├── adopt_pet.php           # Browse & apply to adopt (INSERT)
├── my_pets.php             # A user's adopted pets (SELECT + JOIN)
├── pet_shelter.php         # Send / retrieve pets from shelters
├── report_lost.php         # Report a lost pet (INSERT)
├── foundpet.php            # Mark a pet as found (UPDATE + DELETE)
├── lostpets.php            # Public lost-pet board
├── admin_*.php             # Admin dashboard pages
├── images/                 # Pet photos
└── styles.css              # Custom styles on top of Bootstrap
```

### Schema at a glance

```
user ──< adoptionapplication >── pet
user ──< ownedpets          >── pet
pet  ──< shelters           >── petshelter
user ──< reports >── lostandfound
reports ── pet
```

---

## Security Notes

- **SQL injection** — all queries built from request data use bound parameters via PDO
  prepared statements.
- **Password storage** — passwords are hashed with `password_hash()` (bcrypt) and checked
  with `password_verify()`.
- **XSS** — database values are escaped with `htmlspecialchars()` before being rendered.

This is a learning project, so it stops there — production use would also want CSRF
tokens, server-side authorisation checks on every action, and rate limiting.

---

## License

Released under the [MIT License](LICENSE).
