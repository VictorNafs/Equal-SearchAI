<?php
session_start();
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=e.e;charset=utf8', $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if (!isset($_SESSION['current_step'])) {
    resetSession();
    echo "Souhaitez-vous ajouter une chose que vous recherchez ou souhaitez-vous ajouter une chose que vous proposez? (recherche/proposition)";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        handleImageUpload(); // Appel à la fonction de gestion de l'upload d'image
    }
    if (isset($_POST['message'])) {
        $message = trim($_POST['message']);
        processInput($message);
    }
}

function resetSession() {
    $_SESSION['current_step'] = 0;
    $_SESSION['responses'] = [];
    $_SESSION['mode'] = '';
    $_SESSION['awaiting_confirmation'] = false;
    $_SESSION['image_uploaded'] = false;
}

function processInput($message) {
    global $bdd;
    if ($_SESSION['current_step'] === 0) {
        if (in_array(strtolower($message), ['recherche', 'proposition'])) {
            $_SESSION['mode'] = strtolower($message);
            $_SESSION['current_step']++;
            askNextQuestion();
        } else {
            echo "Veuillez répondre par 'recherche' ou 'proposition'.";
        }
    } elseif ($_SESSION['current_step'] > 0 && $_SESSION['current_step'] <= 6) {
        $_SESSION['responses'][$_SESSION['current_step']] = $message;
        $_SESSION['current_step']++;
        askNextQuestion();
    } elseif ($_SESSION['current_step'] === 7) {
        if (strtolower($message) === 'oui') {
            insertIntoDatabase($bdd);
            echo "Les informations ont été enregistrées avec succès.";
            resetSession();
        } else {
            echo "Annulation confirmée. Les informations n'ont pas été enregistrées.";
            resetSession();
        }
    }
}

function askNextQuestion() {
    if ($_SESSION['current_step'] < 7) {
        echo getQuestionsBasedOnMode($_SESSION['mode'])[$_SESSION['current_step']];
    } else {
        $_SESSION['awaiting_confirmation'] = true;
        echo recapitulatif() . " Confirmez-vous ces informations ? (oui/non) Afficher l'onglet d'ajout de photo";
    }
}

function handleImageUpload() {
    $uploadsDir = 'images/'; // Chemin vers le dossier images
    $fileName = time() . '_' . basename($_FILES['image']['name']);
    $targetPath = $uploadsDir . $fileName;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $_SESSION['image_path'] = 'images/' . $fileName; // Stockage du chemin relatif pour une utilisation ultérieure
        echo "Image téléchargée avec succès; ";
    } else {
        echo "Erreur lors de l'upload de l'image.";
    }
}

function getQuestionsBasedOnMode($mode) {
    $questionsRecherche = [
        1 => "Quel est le nom de ce que vous recherchez?",
        2 => "Avez-vous une URL à communiquer?",
        3 => "Pouvez-vous décrire ce que vous recherchez?",
        4 => "Quelles quantités recherchez-vous?",
        5 => "Quel est votre budget?",
        6 => "Y a-t-il un lieu précis pour cette recherche?",
        7 => "Confirmez-vous ces informations ? (oui/non)"
    ];
    $questionsProposition = [
        1 => "Quel est le nom que vous souhaitez ajouter?",
        2 => "Avez-vous une URL à communiquer?",
        3 => "Pouvez-vous décrire ce que vous ajoutez?",
        4 => "Quelles quantités proposez-vous?",
        5 => "À quel prix?",
        6 => "Y a-t-il un lieu à préciser pour cette proposition?",
        7 => "Confirmez-vous ces informations ? (oui/non)"
    ];
    return $mode === 'recherche' ? $questionsRecherche : $questionsProposition;
}

function recapitulatif() {
    $recap = "Récapitulatif des réponses:<br>";
    foreach ($_SESSION['responses'] as $step => $response) {
        $question = getQuestionsBasedOnMode($_SESSION['mode'])[$step];
        $recap .= "{$question} Réponse: {$response}<br>";
    }
    return $recap . " Confirmez-vous ces informations ? (oui/non)";
}

function insertIntoDatabase($bdd) {
    $responses = $_SESSION['responses'];
    $mode = $_SESSION['mode'];
    $membre_id = $_SESSION['user_id']; // Utilisation de l'ID utilisateur stocké dans la session
    $categorie_id = isset($_POST['categorie']) ? intval($_POST['categorie']) : null; // Récupération de la catégorie depuis le formulaire
    $imagePath = isset($_SESSION['image_path']) ? $_SESSION['image_path'] : null;

    $query = "INSERT INTO produits_services (membre_id, categorie_id, nom, description, quantite, prix, lieu, url, date_ajout, type, chemin_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
    try {
        $stmt = $bdd->prepare($query);
        $stmt->execute([
            $membre_id,
            $categorie_id,
            $responses[1], // nom
            $responses[3], // description
            intval($responses[4]), // quantite
            floatval($responses[5]), // prix
            $responses[6], // lieu
            $responses[2], // url
            $mode, // type
            $imagePath // chemin_image
        ]);
        echo "Les informations et l'image ont été enregistrées avec succès.";
    } catch (PDOException $e) {
        echo "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}

?>
