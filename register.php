<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=e.e;charset=utf8', $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur de connexion à la base de données: ' . $e->getMessage());
}

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Vérification si l'email existe déjà
    $stmt = $bdd->prepare("SELECT id FROM membres WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $errorMessage = "Un utilisateur avec cet email existe déjà. <a href='register.php'>Réessayer</a>";
    } else {
        // Insertion de l'utilisateur dans la base de données
        $insertStmt = $bdd->prepare("INSERT INTO membres (pseudo, email, motdepasse) VALUES (?, ?, ?)");
        $result = $insertStmt->execute([$pseudo, $email, $password]);
        if ($result) {
            echo "<script>alert('Inscription réussie ! Redirection vers la page d'ajout...'); window.location='ajoutsecure.php';</script>";
            exit;
        } else {
            $errorMessage = "Erreur lors de l'inscription. <a href='register.php'>Réessayer</a>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h2>Formulaire d'inscription</h2>
    <?php if (!empty($errorMessage)): ?>
    <p style="color: red;"><?= $errorMessage ?></p>
    <?php endif; ?>
    <form action="register.php" method="POST">
        <label for="pseudo">Pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" required>
        <br>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
