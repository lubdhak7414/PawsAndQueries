<?php

/**
 * Quick dev helper: verifies that the database connection works and that the
 * pet image directory is present. Handy while setting the project up locally.
 * Remove (or restrict) before deploying anywhere public.
 */

declare(strict_types=1);

require 'db.php';

header('Content-Type: text/plain; charset=utf-8');

// 1. Database connectivity check.
try {
    $count = $pdo->query('SELECT COUNT(*) FROM pet')->fetchColumn();
    echo "Database connection OK — {$count} pets in the catalogue.\n";
} catch (PDOException $e) {
    echo 'Database check failed: ' . $e->getMessage() . "\n";
}

// 2. Image directory check.
$directory = __DIR__ . '/images';
if (is_dir($directory)) {
    $files = array_filter(scandir($directory), static fn ($f) => !in_array($f, ['.', '..'], true));
    echo 'Image directory OK — ' . count($files) . " files found.\n";
} else {
    echo "Image directory '{$directory}' does not exist.\n";
}
