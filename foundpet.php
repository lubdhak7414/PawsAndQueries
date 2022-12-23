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
    $petId = $_POST['pet_id'];

    $stmt = $pdo->prepare('SELECT Report_id FROM reports WHERE Pet_id = ? AND User_id = ?');
    $stmt->execute([$petId, $userId]);
    $report = $stmt->fetch();

    if ($report) {
        $reportId = $report['Report_id'];
        $pdo->prepare("UPDATE lostandfound SET Status = 'Found' WHERE Report_id = ?")->execute([$reportId]);
        $pdo->prepare('DELETE FROM reports WHERE Report_id = ?')->execute([$reportId]);
        $message = '<div class="alert alert-success">The pet has been marked as found and the report closed.</div>';
    } else {
        $message = '<div class="alert alert-danger">No matching lost report was found for that pet.</div>';
    }
}

// The user's pets that are currently flagged as lost.
$stmt = $pdo->prepare("
    SELECT p.Pet_id, p.Name
    FROM lostandfound lf
    JOIN reports r ON lf.Report_id = r.Report_id
    JOIN pet p ON r.Pet_id = p.Pet_id
    WHERE r.User_id = ? AND lf.Status = 'Lost'
");
$stmt->execute([$userId]);
$pets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report a Found Pet — PawsAndQueries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include 'user_header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Report a Found Pet</h4>
                </div>
                <div class="card-body">
                    <?= $message ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="pet_id" class="form-label">Select Pet</label>
                            <select class="form-select" id="pet_id" name="pet_id" required>
                                <option value="" disabled selected>Select your lost pet</option>
                                <?php foreach ($pets as $pet): ?>
                                    <option value="<?= e((string) $pet['Pet_id']) ?>"><?= e($pet['Name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark">Mark as Found</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
