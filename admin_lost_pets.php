<?php

declare(strict_types=1);

require 'db.php';
require 'helpers.php';

session_start();

if (!isset($_SESSION['admin'])) {
    redirect('login.php');
}

$reports = $pdo->query("
    SELECT lf.Report_id, lf.Status, lf.ReportDate, lf.Description, r.Pet_id
    FROM lostandfound lf
    JOIN reports r ON lf.Report_id = r.Report_id
    WHERE lf.Status = 'Lost'
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost Pets — Admin — PawsAndQueries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include 'admin_header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Lost Pet Reports</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Report ID</th>
                    <th>Pet ID</th>
                    <th>Status</th>
                    <th>Report Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($reports) > 0): ?>
                    <?php foreach ($reports as $row): ?>
                        <tr>
                            <td><?= e((string) $row['Report_id']) ?></td>
                            <td><?= e((string) $row['Pet_id']) ?></td>
                            <td><?= e((string) $row['Status']) ?></td>
                            <td><?= e((string) $row['ReportDate']) ?></td>
                            <td><?= e((string) $row['Description']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-muted">No lost pet reports found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
