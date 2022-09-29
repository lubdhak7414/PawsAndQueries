<?php

/**
 * Database configuration.
 *
 * For a real deployment, copy this file to `config.local.php` (which is
 * git-ignored) and override the credentials there, or read them from
 * environment variables. The defaults below match a stock XAMPP/MariaDB
 * install so the project runs out of the box for local development.
 */

declare(strict_types=1);

return [
    'host'    => 'localhost',
    'name'    => 'petshel',
    'user'    => 'root',
    'pass'    => '',
    'charset' => 'utf8mb4',
];
