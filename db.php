<?php

/**
 * PDO database connection.
 *
 * Every page includes this file to obtain a single, shared `$pdo` instance.
 * The connection is configured to:
 *   - throw exceptions on error (so failures are never silently ignored),
 *   - return associative arrays by default,
 *   - use real (non-emulated) prepared statements.
 */

declare(strict_types=1);

$config = require __DIR__ . '/config.php';

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=%s',
    $config['host'],
    $config['name'],
    $config['charset']
);

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $config['user'], $config['pass'], $options);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed: ' . $e->getMessage());
}
