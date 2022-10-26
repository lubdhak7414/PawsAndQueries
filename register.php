<?php

declare(strict_types=1);

require 'db.php';
require 'helpers.php';

session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name          = $_POST['name'];
    $email         = $_POST['email'];
    $password      = md5($_POST['password']);
    $contactNumber = $_POST['contactNumber'];
    $address       = $_POST['address'];

    try {
        $pdo->query(
            "INSERT INTO user (Name, Password, ContactNumber, Address, Email, Owned_pets)
             VALUES ('$name', '$password', '$contactNumber', '$address', '$email', 0)"
        );
        redirect('login.php');
    } catch (PDOException $e) {
        $error = 'Could not register: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — PawsAndQueries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include 'homeheader.php'; ?>

<div class="container d-flex align-items-center justify-content-center py-5">
    <div class="card shadow-sm" style="width: 420px;">
        <div class="card-body p-4">
            <h2 class="text-center mb-4">Create an Account</h2>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="contactNumber" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contactNumber" name="contactNumber" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">Register</button>
            </form>

            <a href="login.php" class="d-block text-center mt-3">Already have an account? Login here</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
