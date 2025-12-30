<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

$user = $_SESSION['user'];
$success = '';
$error = '';

// UPDATE PROFIL (infos)
if (isset($_POST['update_profile'])) {
    $nom     = trim($_POST['nom']);
    $prenom  = trim($_POST['prenom']);
    $email   = trim($_POST['email']);

    if ($nom && $prenom && $email) {
        $stmt = $pdo->prepare(
            "UPDATE users SET nom = ?, prenom = ?, email = ? WHERE id = ?"
        );
        $stmt->execute([$nom, $prenom, $email, $user['id']]);

        $_SESSION['user']['nom'] = $nom;
        $_SESSION['user']['prenom'] = $prenom;
        $_SESSION['user']['email'] = $email;

        $success = "Profil mis à jour";
    }
}

// UPLOAD PHOTO
if (isset($_POST['upload_photo']) && isset($_FILES['photo'])) {
    if ($_FILES['photo']['error'] === 0) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array(strtolower($ext), $allowed)) {
            $filename = 'user_' . $user['id'] . '.' . $ext;
            $path = '../uploads/' . $filename;

            move_uploaded_file($_FILES['photo']['tmp_name'], $path);

            $stmt = $pdo->prepare("UPDATE users SET photo = ? WHERE id = ?");
            $stmt->execute([$filename, $user['id']]);

            $_SESSION['user']['photo'] = $filename;
            $success = "Photo mise à jour";
        } else {
            $error = "Format de photo invalide";
        }
    }
}

// CHANGE PASSWORD
if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];

    if (password_verify($current, $user['password'])) {
        $hash = password_hash($new, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hash, $user['id']]);

        $_SESSION['user']['password'] = $hash;
        $success = "Mot de passe modifié";
    } else {
        $error = "Mot de passe actuel incorrect";
    }
}
// DELETE PHOTO
if (isset($_POST['delete_photo']) && $user['photo']) {
    $filePath = '../uploads/' . $user['photo'];

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    $stmt = $pdo->prepare("UPDATE users SET photo = NULL WHERE id = ?");
    $stmt->execute([$user['id']]);

    $_SESSION['user']['photo'] = null;
    $success = "Photo supprimée";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="../assets/css/user.css">
</head>
<body>

<h1>My Profile</h1>
<a href="dashboard.php">⬅ back to dashboard</a>

<?php if ($success): ?>
    <p class="success"><?= $success ?></p>
<?php endif; ?>

<?php if ($error): ?>
    <p class="error"><?= $error ?></p>
<?php endif; ?>

<hr>

<h2>personnal informations</h2>
<form method="POST">
    <input type="hidden" name="update_profile">
    <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required><br><br>
    <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required><br><br>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>
    <button type="submit">Save</button>
</form>

<hr>

<h2>profil picture</h2>
<?php if ($user['photo']): ?>
    <div class="profile-photo">
        <img src="../uploads/<?= htmlspecialchars($user['photo']) ?>">
        <form method="POST">
            <input type="hidden" name="delete_photo">
            <button type="submit" class="danger">Supprimer la photo</button>
        </form>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="upload_photo">
    <input type="file" name="photo" required>
    <button type="submit">Uploader</button>
</form>

<hr>

<h2>Change Password</h2>
<form method="POST">
    <input type="hidden" name="change_password">
    <input type="password" name="current_password" placeholder="Mot de passe actuel" required><br><br>
    <input type="password" name="new_password" placeholder="Nouveau mot de passe" required><br><br>
    <button type="submit">Change</button>
</form>

</body>
</html>