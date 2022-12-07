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
    $app     = $pdo->query("SELECT User_id, Pet_id FROM adoptionapplication WHERE Application_id = $application_id")->fetch();
    $user_id = $app['User_id'];
    $pet_id  = $app['Pet_id'];

    $pdo->query("INSERT INTO ownedpets (User_id, Pet_id, ApprovalDate) VALUES ($user_id, $pet_id, CURDATE())");
    $pdo->query("UPDATE pet SET AdoptionStatus = 1 WHERE Pet_id = $pet_id");
}

$pdo->query("UPDATE adoptionapplication SET Status = '$new_status' WHERE Application_id = $application_id");

redirect('admin_approval.php');
