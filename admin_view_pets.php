<?php

declare(strict_types=1);

require 'db.php';
require 'helpers.php';

session_start();

if (!isset($_SESSION['admin'])) {
    redirect('login.php');
}

$pets = $pdo->query("
    SELECT DISTINCT p.Pet_id, p.Name, p.Type, p.Breed, p.Age, p.Image_url,
           latest.Status AS Last_Status
    FROM pet p
    LEFT JOIN (
        SELECT Pet_id, Status
        FROM adoptionapplication aa
        WHERE Application_id = (
            SELECT MAX(Application_id)
            FROM adoptionapplication
            WHERE Pet_id = aa.Pet_id
        )
    ) latest ON p.Pet_id = latest.Pet_id
    WHERE p.AdoptionStatus = 0
      AND (latest.Status IS NULL OR latest.Status <> 'Pending')
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Pets — Admin — PawsAndQueries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">
<?php include 'admin_header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Pets Available for Adoption</h2>
    <div class="row">
        <?php foreach ($pets as $pet): ?>
            <div class="col-md-4 mb-4">
                <div class="card pet-card shadow-sm h-100">
                    <img src=".<?= e($pet['Image_url']) ?>"
                         alt="Picture of <?= e($pet['Name']) ?>"
                         class="card-img-top"
                         onerror="this.onerror=null; this.src='images/placeholder.svg';">
                    <div class="card-body text-center">
                        <h4 class="card-title mb-3"><?= e($pet['Name']) ?></h4>
                        <p class="card-text mb-1"><strong>Type:</strong> <?= e($pet['Type']) ?></p>
                        <p class="card-text mb-1"><strong>Breed:</strong> <?= e($pet['Breed']) ?></p>
                        <p class="card-text mb-0"><strong>Age:</strong> <?= e((string) $pet['Age']) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
