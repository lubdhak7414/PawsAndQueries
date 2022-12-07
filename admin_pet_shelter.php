<?php

declare(strict_types=1);

require 'db.php';
require 'helpers.php';

session_start();

if (!isset($_SESSION['admin'])) {
    redirect('index.php');
}

// Retrieve a pet from a shelter (delete the boarding row, free up a seat).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['retrieve_pet'])) {
    $pet_id     = $_POST['pet_id'];
    $shelter_id = $_POST['shelter_id'];

    $pdo->query("DELETE FROM shelters WHERE Pet_id = $pet_id");
    $pdo->query("UPDATE petshelter SET ShelterSeat = ShelterSeat + 1 WHERE Listing_id = $shelter_id");
    $_SESSION['message'] = 'Pet successfully retrieved from the shelter.';

    redirect($_SERVER['PHP_SELF']);
}

$rows = $pdo->query("
    SELECT s.Listing_id, p.Pet_id, p.Name, ps.ShelterType, ps.ShelterSeat, ps.PropertyName, ps.ContactInformation
    FROM shelters s
    JOIN pet p ON s.Pet_id = p.Pet_id
    JOIN petshelter ps ON s.Listing_id = ps.Listing_id
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Shelters — Admin — PawsAndQueries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include 'admin_header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Manage Pet Shelters</h2>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= e($_SESSION['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Shelter ID</th>
                    <th>Pet ID</th>
                    <th>Pet Name</th>
                    <th>Shelter Type</th>
                    <th>Seats Available</th>
                    <th>Property Name</th>
                    <th>Contact</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($rows) > 0): ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e((string) $row['Listing_id']) ?></td>
                            <td><?= e((string) $row['Pet_id']) ?></td>
                            <td><?= e($row['Name']) ?></td>
                            <td><?= e($row['ShelterType']) ?></td>
                            <td><?= e((string) $row['ShelterSeat']) ?></td>
                            <td><?= e($row['PropertyName']) ?></td>
                            <td><?= e($row['ContactInformation']) ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="pet_id" value="<?= e((string) $row['Pet_id']) ?>">
                                    <input type="hidden" name="shelter_id" value="<?= e((string) $row['Listing_id']) ?>">
                                    <button type="submit" name="retrieve_pet" class="btn btn-sm btn-dark">Retrieve Pet</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-muted">No pets are currently in a shelter.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
