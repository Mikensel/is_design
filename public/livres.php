<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

// AJOUT D'UN LIVRE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre  = $_POST['titre'] ?? '';
    $auteur = $_POST['auteur'] ?? '';
    $annee  = $_POST['annee'] ?? null;
    $isbn   = $_POST['isbn'] ?? null;

    if ($titre && $auteur) {
        $stmt = $pdo->prepare(
            "INSERT INTO books (titre, auteur, annee, isbn)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$titre, $auteur, $annee, $isbn]);

        header('Location: livres.php');
        exit;
    }
}

// LECTURE DES LIVRES
$stmt = $pdo->query("SELECT * FROM books ORDER BY created_at DESC");
$books = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Books</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>Books Management</h1>

<a href="dashboard.php">⬅ back to dashboard</a>

<hr>

<h2>Add a book</h2>

<form method="POST">
    <input type="text" name="titre" placeholder="Titre" required><br><br>
    <input type="text" name="auteur" placeholder="Auteur" required><br><br>
    <input type="number" name="annee" placeholder="Année"><br><br>
    <input type="text" name="isbn" placeholder="ISBN"><br><br>
    <button type="submit">Add</button>
</form>

<hr>

<h2>List of books</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>Titre</th>
        <th>Auteur</th>
        <th>Année</th>
        <th>ISBN</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($books as $book): ?>
        <tr>
            <td><?= htmlspecialchars($book['titre']) ?></td>
            <td><?= htmlspecialchars($book['auteur']) ?></td>
            <td><?= htmlspecialchars($book['annee']) ?></td>
            <td><?= htmlspecialchars($book['isbn']) ?></td>
            <td>
                <a href="livre_edit.php?id=<?= $book['id'] ?>">✏️</a>
                <a href="livre_delete.php?id=<?= $book['id'] ?>"
                     onclick="return confirm('Are you sure you want to delete this book?');">🗑️
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
