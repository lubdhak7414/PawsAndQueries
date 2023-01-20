<?php

declare(strict_types=1);

require 'db.php';
require 'helpers.php';

session_start();

if (!isset($_SESSION['user'])) {
    redirect('index.php');
}

$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_id          = $_POST['pet_id'];
    $user_id         = $_SESSION['user']['User_id'];
    $applicationDate = date('Y-m-d');

    try {
        $stmt = $pdo->prepare(
            'INSERT INTO adoptionapplication (ApplicationDate, User_id, Pet_id, Status)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$applicationDate, $user_id, $pet_id, 'Pending']);
        $notice = 'Application submitted successfully.';
    } catch (PDOException $e) {
        $notice = 'Error: ' . $e->getMessage();
    }
}

// Pets that are still available and have no pending application against them.
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
    <title>Available Pets — PawsAndQueries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">
<?php include 'user_header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Available Pets for Adoption</h2>

    <?php if ($notice !== ''): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= e($notice) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($pets as $pet): ?>
            <div class="col-md-4 mb-4">
                <div class="card pet-card shadow-sm h-100">
                    <img src=".<?= e($pet['Image_url']) ?>"
                         alt="Picture of <?= e($pet['Name']) ?>"
                         class="card-img-top">
                    <div class="card-body text-center">
                        <h4 class="card-title mb-3"><?= e($pet['Name']) ?></h4>
                        <p class="card-text mb-1"><strong>Type:</strong> <?= e($pet['Type']) ?></p>
                        <p class="card-text mb-1"><strong>Breed:</strong> <?= e($pet['Breed']) ?></p>
                        <p class="card-text mb-3"><strong>Age:</strong> <?= e((string) $pet['Age']) ?></p>
                        <form method="POST">
                            <input type="hidden" name="pet_id" value="<?= e((string) $pet['Pet_id']) ?>">
                            <button type="submit" class="btn btn-adopt w-100">Apply to Adopt</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (count($pets) === 0): ?>
            <p class="text-muted">There are no pets available for adoption right now. Check back soon!</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
