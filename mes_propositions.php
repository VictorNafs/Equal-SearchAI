<?php
session_start();

require 'vendor/autoload.php'; // Assurez-vous que ce chemin est correct
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=e.e;charset=utf8', $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        // Si non connecté, rediriger vers la page de connexion
        header('Location: login.php');
        exit;
    }

    // Récupérer les propositions ou recherches du membre connecté
    $stmt = $bdd->prepare("SELECT ps.*, c.nom AS categorie_nom FROM produits_services ps LEFT JOIN categories c ON ps.categorie_id = c.id WHERE membre_id = :membre_id ORDER BY date_ajout DESC");
    $stmt->execute(['membre_id' => $_SESSION['user_id']]);
    $activites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Activités</title>
</head>
<body>
    <h1>Mes Activités</h1>
    <?php if (empty($activites)): ?>
        <p>Aucune activité enregistrée.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($activites as $activite): ?>
                <li>
                    <h2><?php echo htmlspecialchars($activite['nom']); ?></h2>
                    <p>Catégorie: <?php echo htmlspecialchars($activite['categorie_nom']); ?></p>
                    <p>Description: <?php echo htmlspecialchars($activite['description']); ?></p>
                    <p>Quantité: <?php echo htmlspecialchars($activite['quantite']); ?></p>
                    <p>Prix: <?php echo htmlspecialchars($activite['prix']); ?></p>
                    <p>Lieu: <?php echo htmlspecialchars($activite['lieu']); ?></p>
                    <p>Type: <?php echo htmlspecialchars($activite['type']); ?></p>
                    <!-- Vous pouvez ajouter plus de détails ici -->
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <a href="logout.php">Déconnexion</a> | <a href="index.php">Retour à l'accueil</a>
</body>
</html>
