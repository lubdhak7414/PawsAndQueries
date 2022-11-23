<?php

declare(strict_types=1);

require 'db.php';
require 'helpers.php';

session_start();

if (!isset($_SESSION['user'])) {
    redirect('index.php');
}

$reports = $pdo->query("
    SELECT lf.Report_id, lf.Status, lf.ReportDate, lf.Description, r.Pet_id, p.Name
    FROM lostandfound lf
    JOIN reports r ON lf.Report_id = r.Report_id
    JOIN pet p ON r.Pet_id = p.Pet_id
    WHERE lf.Status = 'Lost'
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost Pets — PawsAndQueries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include 'user_header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Lost Pet Reports</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Pet Name</th>
                    <th>Report Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($reports) > 0): ?>
                    <?php foreach ($reports as $row): ?>
                        <tr>
                            <td><?= e($row['Name']) ?></td>
                            <td><?= e((string) $row['ReportDate']) ?></td>
                            <td><?= e((string) $row['Description']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-muted">No lost pets reported right now.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
