<?php
require_once '../includes/auth.php';
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header class="header">
    <h1>Bibliothèque — Dashboard</h1>
    <div class="user-info">
        <span>
            <?= htmlspecialchars($user['prenom']) ?>
            <?= htmlspecialchars($user['nom']) ?>
        </span>
        <a href="logout.php" class="logout">Log out</a>
    </div>
</header>

<main class="dashboard">

    <section class="card">
        <h2>📚 Books</h2>
        <p>Manage the library's books</p>
        <a href="livres.php" class="btn">Access</a>
    </section>

    <section class="card">
        <h2>👤 My Profile</h2>
        <p>Edit my personal information</p>
        <a href="profile.php" class="btn">Access</a>
    </section>

</main>

</body>
</html>
