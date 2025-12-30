<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: livres.php');
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
$stmt->execute([$id]);

header('Location: livres.php');
exit;
