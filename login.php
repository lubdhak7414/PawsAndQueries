<?php

declare(strict_types=1);

require 'db.php';
require 'helpers.php';

session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = md5($_POST['password']);

    // Try the admin table first, then the user table.
    $admin = $pdo->query("SELECT * FROM admin WHERE Email = '$email' AND Password = '$password'")->fetch();
    if ($admin) {
        $_SESSION['admin'] = $admin;
        redirect('admin_approval.php');
    }

    $user = $pdo->query("SELECT * FROM user WHERE Email = '$email' AND Password = '$password'")->fetch();
    if ($user) {
        $_SESSION['user'] = $user;
        redirect('my_pets.php');
    }

    $error = 'Invalid login credentials.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — PawsAndQueries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include 'homeheader.php'; ?>

<div class="container d-flex align-items-center justify-content-center py-5">
    <div class="card shadow-sm" style="width: 400px;">
        <div class="card-body p-4">
            <h2 class="text-center mb-4">Login</h2>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">Login</button>
            </form>

            <a href="register.php" class="d-block text-center mt-3">Don't have an account? Register here</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
