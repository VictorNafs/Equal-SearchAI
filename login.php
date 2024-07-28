<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start(); // Démarrage de la session

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Connexion à la base de données
    try {
        $bdd = new PDO('mysql:host=127.0.0.1;dbname=e.e;charset=utf8', $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérification des identifiants
        $stmt = $bdd->prepare("SELECT * FROM membres WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['motdepasse'])) {
            // Connexion réussie
            $_SESSION['user_id'] = $user['id']; // Stocker l'ID utilisateur dans la session
            echo "Connexion réussie. Bienvenue " . $user['pseudo'] . "!";
            // Rediriger vers une page sécurisée
            header("Location: ajoutsecure.php");
            exit;
        } else {
            // Identifiants incorrects
            echo "Identifiants incorrects. <a href='login.html'>Réessayer</a>";
        }
    } catch (PDOException $e) {
        die('Erreur de connexion à la base de données: ' . $e->getMessage());
    }
} else {
    // Redirection vers le formulaire de connexion si la requête n'est pas POST
    header("Location: login.html");
    exit;
}
?>
