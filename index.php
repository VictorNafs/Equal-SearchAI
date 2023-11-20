<?php 
set_time_limit(25); // Limite de temps d'exécution du script

require 'vendor/autoload.php';
require_once 'openai_api.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$googleApiKey = $_ENV['GOOGLE_API_KEY'];
$googleSearchEngineId = $_ENV['GOOGLE_SEARCH_ENGINE_ID'];

try {
    $bdd = new PDO('mysql:host=localhost;dbname=e.e;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch(Exception $e) {
    error_log($e->getMessage());
    die('Erreur de connexion à la base de données.');
}

function rechercheGoogle($query, $start = 1) {
    $apiKey = $_ENV['GOOGLE_API_KEY'];
    $searchEngineId = $_ENV['GOOGLE_SEARCH_ENGINE_ID'];
    $url = "https://www.googleapis.com/customsearch/v1?key=" . $apiKey . "&cx=" . $searchEngineId . "&q=" . urlencode($query) . "&start=" . $start . "&num=10";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Limite de temps pour la requête
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode != 200) {
        error_log("Google API returned HTTP code $httpCode for query: $query");
        curl_close($ch);
        return [];
    }

    if (curl_errno($ch)) {
        error_log("cURL error: " . curl_error($ch));
        curl_close($ch);
        return [];
    }

    curl_close($ch);
    return json_decode($response, true);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="path/to/script.js"></script>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="shortcut icon" href="EQEX.ico" type="image/x-icon" />
    <meta property="og:image" content="EQEX.ico" />
    <meta name="description" content="Explorez une nouvelle dimension de la recherche avec Equal-Exchange – où l'IA rencontre Google. Notre moteur de recherche révolutionnaire analyse vos requêtes avec l'intelligence d'OpenAI pour des résultats Google affinés. Cherchez autrement et découvrez des réponses pertinentes là où les recherches traditionnelles atteignent leurs limites. Rejoignez-nous dans cette aventure novatrice pour une recherche sur Internet plus intuitive et efficace.">
	<meta name="keywords" content="recherche assistée par IA, optimisation de recherche par IA, moteur de recherche hybride, technologie de recherche avancée, recherche intelligente sur internet, moteur de recherche révolutionnaire, intégration OpenAI Google, recherche web nouvelle génération, système de recherche intuitif, pertinence accrue en recherche web, analyse IA des requêtes, expérience de recherche améliorée, filtrage IA de recherches Google, innovation en recherche web">
    <meta name="author" content="Victor DUPREZ - Equal-Exchange">

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6308308780527097"
     crossorigin="anonymous"></script>

    <title>Equal-Exchange</title>
</head>
<body>

<div id="loadingIndicator" style="display: none;">
    <img src="loading.gif" alt="Chargement..."/>
</div>

    <header>
    <article class="invest-section">
        <p>Envie d'investir dans Equal-Exchange ? <a href="https://trade.kanga.exchange/market/EQEX-ETH" target="_blank">En savoir plus sur les tokens EQEX</a>.</p>
    </article>

    <h1>Equal-Exchange</h1>
    <p>Version bêta</p>
</header>

 <main>

    <section>
        <form action="index.php" method="post">
            <input type="text" name="recherche" placeholder="Rechercher avec IA...">
            <button type="submit">Rechercher</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recherche'])) {
            $recherche = $_POST['recherche'];
            $messages = [
                ["role" => "system", "content" => "Répondez à la demande de l'utilisateur en utilisant uniquement des mots-clés. En plus de cette réponse, sélectionnez au maximum trois termes pertinents extraits de la requête. Si vous ne connaissez pas de réponse, sélectionnez uniquement des mots-clés de cette requête."],
                ["role" => "user", "content" => $recherche]
            ];

            try {
                $response = $openAI->queryGPT($messages);
                if (!$response || !isset($response['choices'][0]['message']['content'])) {
                    throw new Exception('Aucune réponse de l\'IA');
                }
                $keyConcepts = $response['choices'][0]['message']['content'];
                echo "<div class='reponse-openai'>";
                echo "<p>Concepts clés identifiés par l'IA : " . htmlspecialchars($keyConcepts) . "</p>";
                echo "</div>";

                $resultatsGooglePremierePage = rechercheGoogle($keyConcepts, 1);
                $resultatsGoogleDeuxiemePage = rechercheGoogle($keyConcepts, 11);
                $resultatsGoogle = [];

                if (isset($resultatsGooglePremierePage['items']) && isset($resultatsGoogleDeuxiemePage['items'])) {
                    $resultatsGoogle = array_merge($resultatsGooglePremierePage['items'], $resultatsGoogleDeuxiemePage['items']);
                }

                if (!empty($resultatsGoogle)) {
                    echo "<h2>Résultats de la recherche Google :</h2>";
                    foreach ($resultatsGoogle as $item) {
                        echo "<a href='" . htmlspecialchars($item['link']) . "' class='result-link'>";
                        echo "<div>";
                        echo "<h3>" . htmlspecialchars($item['title']) . "</h3>";
                        echo "<p>" . htmlspecialchars($item['snippet']) . "</p>";
                        echo "</div>";
                        echo "</a>";
                    }
                } else {
                    echo "<p>Aucun résultat trouvé ou erreur lors de la recherche Google.</p>";
                }
            } catch(Exception $e) {
                echo "<p>Une erreur est survenue. Veuillez réessayer plus tard.</p>";
                error_log("Erreur OpenAI : " . $e->getMessage());
            }
        }
        ?>
    </section>

</main>

    <footer>
    <a href="politique_cookies.html" class="pc" title="Politique de confidentialité">Politique de confidentialité</a>
    <a href="mentions.html" class="mentions" title="Mentions légales">Mentions légales</a>
    </footer>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.querySelector('form');
        var loadingIndicator = document.getElementById('loadingIndicator');

        form.addEventListener('submit', function() {
            // Affiche l'indicateur de chargement
            loadingIndicator.style.display = 'block';
        });

        window.addEventListener('load', function() {
            // Cache l'indicateur de chargement une fois que la page est complètement chargée
            loadingIndicator.style.display = 'none';
        });
    });
    </script>

</body>
</html>
