<?php

declare(strict_types=1);

require 'db.php';
require 'helpers.php';

session_start();

if (!isset($_SESSION['admin'])) {
    redirect('login.php');
}

$applications = $pdo->query("
    SELECT aa.Application_id, aa.ApplicationDate, aa.Status,
           u.Name AS userName, p.Name AS petName
    FROM adoptionapplication aa
    LEFT JOIN user u ON aa.User_id = u.User_id
    LEFT JOIN pet p  ON aa.Pet_id = p.Pet_id
    WHERE aa.Status = 'Pending'
    ORDER BY aa.Application_id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption Applications — Admin — PawsAndQueries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include 'admin_header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Pending Adoption Applications</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Application ID</th>
                    <th>User</th>
                    <th>Pet</th>
                    <th>Application Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($applications) > 0): ?>
                    <?php foreach ($applications as $row): ?>
                        <tr>
                            <td><?= e((string) $row['Application_id']) ?></td>
                            <td><?= e($row['userName']) ?></td>
                            <td><?= e($row['petName']) ?></td>
                            <td><?= e((string) $row['ApplicationDate']) ?></td>
                            <td>
                                <form method="POST" action="admin_action.php" class="d-inline">
                                    <input type="hidden" name="application_id" value="<?= e((string) $row['Application_id']) ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-muted">No pending applications.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
