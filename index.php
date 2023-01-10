<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawsAndQueries — A Loving Home Awaits</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'homeheader.php'; ?>

<div class="container py-5">
    <section class="hero mb-4">
        <h1 class="display-4 fw-bold">A Loving Home Awaits</h1>
        <p class="fs-5 col-md-8 mx-auto">
            We are dedicated to finding loving families for pets in need.
            Browse adoptable pets, manage shelters, and help reunite lost pets with their owners.
        </p>
        <div class="mt-4">
            <a href="register.php" class="btn btn-light btn-lg px-4 me-2">Get Started</a>
            <a href="login.php" class="btn btn-outline-light btn-lg px-4">Login</a>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
