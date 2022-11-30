<?php

declare(strict_types=1);

require 'db.php';
require 'helpers.php';

session_start();

if (!isset($_SESSION['admin'])) {
    redirect('login.php');
}

$adopted = $pdo->query("
    SELECT p.Name AS PetName, p.Type AS PetType, p.Breed AS PetBreed,
           u.Name AS OwnerName, u.Email AS OwnerEmail, u.ContactNumber AS OwnerContact,
           op.ApprovalDate AS AdoptionDate
    FROM ownedpets op
    JOIN pet p  ON op.Pet_id = p.Pet_id
    JOIN user u ON op.User_id = u.User_id
    WHERE p.AdoptionStatus = 1
    ORDER BY op.ApprovalDate DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopted Pets — Admin — PawsAndQueries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include 'admin_header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Adopted Pets</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Pet Name</th>
                    <th>Type</th>
                    <th>Breed</th>
                    <th>Owner</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Adoption Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($adopted) > 0): ?>
                    <?php foreach ($adopted as $row): ?>
                        <tr>
                            <td><?= e($row['PetName']) ?></td>
                            <td><?= e($row['PetType']) ?></td>
                            <td><?= e($row['PetBreed']) ?></td>
                            <td><?= e($row['OwnerName']) ?></td>
                            <td><?= e($row['OwnerEmail']) ?></td>
                            <td><?= e($row['OwnerContact']) ?></td>
                            <td><?= e((string) $row['AdoptionDate']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-muted">No adopted pets yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
