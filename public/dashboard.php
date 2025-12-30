<?php
require_once '../includes/auth.php';
$user = $_SESSION['user'];
?>

<h1>Dashboard</h1>
<p>Welcome <?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?></p>

<a href="logout.php">Log out</a>
