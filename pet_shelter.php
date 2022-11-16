<?php

declare(strict_types=1);

require 'db.php';
require 'helpers.php';

session_start();

if (!isset($_SESSION['user'])) {
    redirect('index.php');
}

$_SESSION['message'] ??= [];

$user    = $_SESSION['user'];
$user_id = $user['User_id'];

// Handle "send a pet to a shelter".
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_to_shelter'])) {
    $shelter_id = $_POST['shelter_id'];
    $pet_id     = $_POST['pet_id'];

    $owns = $pdo->query("SELECT * FROM ownedpets WHERE Pet_id = $pet_id AND User_id = $user_id")->fetch();

    if (!$owns) {
        $_SESSION['message']['error'] = "Pet ID $pet_id is not owned by you.";
    } elseif ($pdo->query("SELECT * FROM shelters WHERE Pet_id = $pet_id")->fetch()) {
        $_SESSION['message']['error'] = 'Pet already in a shelter.';
    } else {
        $pet_type = $pdo->query("SELECT Type FROM pet WHERE Pet_id = $pet_id")->fetchColumn();
        $shelter  = $pdo->query("SELECT ShelterSeat, ShelterType FROM petshelter WHERE Listing_id = $shelter_id")->fetch();

        if ($shelter && $shelter['ShelterSeat'] > 0 && $shelter['ShelterType'] === $pet_type) {
            $pdo->query("INSERT INTO shelters (Listing_id, Pet_id) VALUES ($shelter_id, $pet_id)");
            $pdo->query("UPDATE petshelter SET ShelterSeat = ShelterSeat - 1 WHERE Listing_id = $shelter_id");
            $_SESSION['message']['success'] = 'Pet successfully added to the shelter.';
        } else {
            $_SESSION['message']['error'] = 'No seats available, or the shelter type does not match the pet type.';
        }
    }

    redirect($_SERVER['PHP_SELF']);
}

// Handle "get a pet back from a shelter".
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $pet_id     = $_POST['pet_id'];
    $shelter_id = $pdo->query("SELECT Listing_id FROM shelters WHERE Pet_id = $pet_id")->fetchColumn();

    $pdo->query("DELETE FROM shelters WHERE Pet_id = $pet_id AND Listing_id = $shelter_id");
    $pdo->query("UPDATE petshelter SET ShelterSeat = ShelterSeat + 1 WHERE Listing_id = $shelter_id");
    $_SESSION['message']['success'] = 'Pet removed from the shelter.';

    redirect($_SERVER['PHP_SELF']);
}

$shelters  = $pdo->query("SELECT * FROM petshelter")->fetchAll();
$ownedPets = $pdo->query("SELECT p.Pet_id, p.Name FROM ownedpets op JOIN pet p ON op.Pet_id = p.Pet_id WHERE op.User_id = $user_id")->fetchAll();
$inShelter = $pdo->query("
    SELECT p.Pet_id, p.Name, s.Listing_id AS Shelter_id
    FROM ownedpets op
    JOIN pet p ON op.Pet_id = p.Pet_id
    JOIN shelters s ON s.Pet_id = p.Pet_id
    WHERE op.User_id = $user_id
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Shelters — PawsAndQueries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include 'user_header.php'; ?>

<div class="container py-5">

    <?php if (!empty($_SESSION['message']['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= e($_SESSION['message']['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['message']['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= e($_SESSION['message']['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']['error']); ?>
    <?php endif; ?>

    <h2 class="mb-4">Pet Shelters</h2>
    <div class="table-responsive mb-5">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Shelter ID</th>
                    <th>Property Name</th>
                    <th>Address</th>
                    <th>Pet Policy</th>
                    <th>Shelter Type</th>
                    <th>Seats Available</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($shelters as $shelter): ?>
                    <tr>
                        <td><?= e((string) $shelter['Listing_id']) ?></td>
                        <td><?= e($shelter['PropertyName']) ?></td>
                        <td><?= e($shelter['Address']) ?></td>
                        <td><?= e($shelter['PetPolicy']) ?></td>
                        <td><?= e($shelter['ShelterType']) ?></td>
                        <td><?= e((string) $shelter['ShelterSeat']) ?></td>
                        <td><?= e($shelter['ContactInformation']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <h4>Send a Pet to a Shelter</h4>
            <form method="POST">
                <div class="mb-3">
                    <label for="shelter_id" class="form-label">Shelter ID</label>
                    <input type="number" id="shelter_id" name="shelter_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="pet_id" class="form-label">Select Pet</label>
                    <select name="pet_id" id="pet_id" class="form-select" required>
                        <option value="">Choose your pet</option>
                        <?php foreach ($ownedPets as $pet): ?>
                            <option value="<?= e((string) $pet['Pet_id']) ?>"><?= e($pet['Name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="send_to_shelter" class="btn btn-dark">Send</button>
            </form>
        </div>

        <div class="col-md-6 mb-4">
            <h4>Pets Currently in a Shelter</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Pet Name</th>
                            <th>Pet ID</th>
                            <th>Shelter ID</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inShelter as $row): ?>
                            <tr>
                                <td><?= e($row['Name']) ?></td>
                                <td><?= e((string) $row['Pet_id']) ?></td>
                                <td><?= e((string) $row['Shelter_id']) ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="pet_id" value="<?= e((string) $row['Pet_id']) ?>">
                                        <button type="submit" name="action" value="retrieve" class="btn btn-sm btn-outline-dark">Retrieve</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
