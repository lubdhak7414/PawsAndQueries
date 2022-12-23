<?php

declare(strict_types=1);

require 'db.php';
require 'helpers.php';

session_start();

if (!isset($_SESSION['admin'])) {
    redirect('index.php');
}

$action         = $_POST['action'] ?? '';
$application_id = $_POST['application_id'] ?? '';

$new_status = match ($action) {
    'approve' => 'Approved',
    'reject'  => 'Rejected',
    default   => null,
};

if ($new_status === null) {
    exit('Invalid action.');
}

// On approval, record ownership and mark the pet as adopted.
if ($action === 'approve') {
    $stmt = $pdo->prepare('SELECT User_id, Pet_id FROM adoptionapplication WHERE Application_id = ?');
    $stmt->execute([$application_id]);
    $app     = $stmt->fetch();
    $user_id = $app['User_id'];
    $pet_id  = $app['Pet_id'];

    $pdo->prepare('INSERT INTO ownedpets (User_id, Pet_id, ApprovalDate) VALUES (?, ?, CURDATE())')
        ->execute([$user_id, $pet_id]);
    $pdo->prepare('UPDATE pet SET AdoptionStatus = 1 WHERE Pet_id = ?')->execute([$pet_id]);
}

$pdo->prepare('UPDATE adoptionapplication SET Status = ? WHERE Application_id = ?')
    ->execute([$new_status, $application_id]);

redirect('admin_approval.php');
