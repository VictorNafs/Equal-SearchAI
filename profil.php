<?php
session_start();
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=e.e;charset=utf8', $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$stmt = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING);
    $motdepasse = $_POST['motdepasse'];
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    $photo_profil = $user['photo_profil'];

    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === 0) {
        $uploadsDir = 'img_profil/';
        $fileName = uniqid('profil_') . '.' . pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION);
        $targetPath = $uploadsDir . $fileName;

        if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $targetPath)) {
            $photo_profil = $fileName;
        }
    }

    if (!empty($motdepasse)) {
        $motdepasse = password_hash($motdepasse, PASSWORD_DEFAULT);
    } else {
        $motdepasse = $user['motdepasse'];
    }

    try {
        $query = "UPDATE membres SET pseudo = ?, motdepasse = ?, description = ?, photo_profil = ? WHERE id = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$pseudo, $motdepasse, $description, $photo_profil, $userId]);
        echo "<script>alert('Profil mis à jour avec succès.'); window.location='profil.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Erreur lors de la mise à jour du profil.'); window.location='profil.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil de <?= htmlspecialchars($user['pseudo']) ?></title>
</head>
<body>

<a href="logout.php">Déconnexion</a> | <a href="mes_propositions.php">Mes ajouts</a>

<h1>Profil de <?= htmlspecialchars($user['pseudo']) ?></h1>

<div>
    <?php if (!empty($user['photo_de_profil'])): ?>
        <img src="img_profil/<?= htmlspecialchars($user['photo_de_profil']) ?>" alt="Photo de profil" style="max-width: 150px;">
    <?php endif; ?>
    <p>Description: <?= htmlspecialchars($user['description'] ?? 'Non fournie') ?></p>
    <p>Coordonnées: <?= htmlspecialchars($user['coordonnees'] ?? 'Non fournies') ?></p>
</div>

<form action="ajoutsecure.php" method="post" enctype="multipart/form-data">
    <div>
        <label for="pseudo">Modifier pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($user['pseudo']) ?>">
    </div>
    <div>
        <label for="motdepasse">Modifier mot de passe :</label>
        <input type="password" id="motdepasse" name="motdepasse">
    </div>
    <div>
        <label for="description">Modifier description :</label>
        <textarea id="description" name="description"><?= htmlspecialchars($user['description']) ?></textarea>
    </div>
    <div>
        <label for="photo_profil">Ajouter une photo de profil :</label>
        <input type="file" id="photo_profil" name="photo_profil">
    </div>
    <button type="submit">Mettre à jour</button>
</form>

</body>
</html>
