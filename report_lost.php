<?php

declare(strict_types=1);

require 'db.php';
require 'helpers.php';

session_start();

if (!isset($_SESSION['user'])) {
    redirect('index.php');
}

$userId  = $_SESSION['user']['User_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $petId       = $_POST['pet_id'];
    $reportDate  = date('Y-m-d');

    $alreadyLost = $pdo->query("
        SELECT 1 FROM lostandfound lf
        JOIN reports r ON lf.Report_id = r.Report_id
        WHERE r.Pet_id = $petId AND lf.Status = 'Lost'
    ")->fetch();

    $ownsPet = $pdo->query("SELECT 1 FROM ownedpets WHERE Pet_id = $petId AND User_id = $userId")->fetch();

    if ($alreadyLost) {
        $message = '<div class="alert alert-warning">This pet is already registered as lost.</div>';
    } elseif (!$ownsPet) {
        $message = '<div class="alert alert-danger">That pet is not in your adopted pet list.</div>';
    } else {
        $pdo->query("INSERT INTO lostandfound (Status, ReportDate, Description) VALUES ('Lost', '$reportDate', '$description')");
        $reportId = $pdo->lastInsertId();
        $pdo->query("INSERT INTO reports (User_id, Report_id, Pet_id) VALUES ($userId, $reportId, $petId)");
        $message = '<div class="alert alert-success">Report submitted successfully.</div>';
    }
}

// Pets the user owns that are not already flagged as lost.
$pets = $pdo->query("
    SELECT p.Pet_id, p.Name
    FROM ownedpets op
    JOIN pet p ON op.Pet_id = p.Pet_id
    LEFT JOIN reports r ON p.Pet_id = r.Pet_id
    LEFT JOIN lostandfound lf ON r.Report_id = lf.Report_id AND lf.Status = 'Lost'
    WHERE op.User_id = $userId AND lf.Report_id IS NULL
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report a Lost Pet — PawsAndQueries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include 'user_header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Report a Lost Pet</h4>
                </div>
                <div class="card-body">
                    <?= $message ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description" required>
                        </div>
                        <div class="mb-3">
                            <label for="pet_id" class="form-label">Select Pet</label>
                            <select class="form-select" id="pet_id" name="pet_id" required>
                                <option value="" disabled selected>Select your pet</option>
                                <?php foreach ($pets as $pet): ?>
                                    <option value="<?= e((string) $pet['Pet_id']) ?>"><?= e($pet['Name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark">Submit Report</button>
                    </form>
                    <div class="mt-3">
                        Did you find your pet?
                        <a href="foundpet.php">Mark it as found here.</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
