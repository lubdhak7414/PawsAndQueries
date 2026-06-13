<?php

/**
 * Database configuration.
 *
 * Credentials are read from environment variables when present (handy for
 * Docker or any hosted environment) and otherwise fall back to the defaults
 * below, which match a stock XAMPP/MariaDB install so the project runs out of
 * the box for local development.
 */

declare(strict_types=1);

return [
    'host'    => getenv('DB_HOST') ?: 'localhost',
    'name'    => getenv('DB_NAME') ?: 'petshel',
    'user'    => getenv('DB_USER') ?: 'root',
    'pass'    => getenv('DB_PASS') ?: '',
    'charset' => 'utf8mb4',
];
