<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

// Vérification ID
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: livres.php');
    exit;
}

$id = (int) $_GET['id'];

// Récupération du livre
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    header('Location: livres.php');
    exit;
}

// Mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre  = trim($_POST['titre'] ?? '');
    $auteur = trim($_POST['auteur'] ?? '');
    $annee  = !empty($_POST['annee']) ? (int)$_POST['annee'] : null;
    $isbn   = trim($_POST['isbn'] ?? '');

    if ($titre !== '' && $auteur !== '') {
        $stmt = $pdo->prepare(
            "UPDATE books
             SET titre = ?, auteur = ?, annee = ?, isbn = ?
             WHERE id = ?"
        );
        $stmt->execute([$titre, $auteur, $annee, $isbn, $id]);

        header('Location: livres.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un livre</title>
    <link rel="stylesheet" href="../assets/css/edit.css">
</head>
<body>

<h1>Edit Book</h1>

<a href="livres.php">⬅ back to books</a>

<form method="POST">
    <input type="text" name="titre" value="<?= htmlspecialchars($book['titre']) ?>" required><br><br>
    <input type="text" name="auteur" value="<?= htmlspecialchars($book['auteur']) ?>" required><br><br>
    <input type="number" name="annee" value="<?= htmlspecialchars($book['annee']) ?>"><br><br>
    <input type="text" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>"><br><br>

    <button type="submit">Save changes</button>
</form>

</body>
</html>
