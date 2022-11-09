<?php

declare(strict_types=1);

require 'db.php';
require 'helpers.php';

session_start();

if (!isset($_SESSION['user'])) {
    redirect('index.php');
}

$user_id = $_SESSION['user']['User_id'];

$pets = $pdo->query("
    SELECT p.Pet_id, p.Name, p.Breed, p.Age, p.Type, p.Image_url, op.ApprovalDate
    FROM pet p
    INNER JOIN ownedpets op ON p.Pet_id = op.Pet_id
    WHERE op.User_id = $user_id
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Adopted Pets — PawsAndQueries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include 'user_header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">My Adopted Pets</h2>

    <div class="row">
        <?php if (count($pets) > 0): ?>
            <?php foreach ($pets as $pet): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="http://localhost/pet3<?= e($pet['Image_url']) ?>"
                             alt="Picture of <?= e($pet['Name']) ?>"
                             class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h4 class="card-title"><?= e($pet['Name']) ?></h4>
                            <h6 class="card-subtitle mb-2 text-muted">Pet ID: <?= e((string) $pet['Pet_id']) ?></h6>
                            <p class="card-text mb-1"><strong>Breed:</strong> <?= e($pet['Breed']) ?></p>
                            <p class="card-text mb-1"><strong>Age:</strong> <?= e((string) $pet['Age']) ?></p>
                            <p class="card-text mb-1"><strong>Type:</strong> <?= e($pet['Type']) ?></p>
                            <p class="card-text mb-0"><strong>Adopted On:</strong> <?= e((string) $pet['ApprovalDate']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">You haven't adopted any pets yet.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
