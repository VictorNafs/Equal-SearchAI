<?php 
set_time_limit(60); // Limite de temps d'exécution du script

require 'vendor/autoload.php';
require_once 'openai_api.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$googleApiKey = $_ENV['GOOGLE_API_KEY'];
$googleSearchEngineId = $_ENV['GOOGLE_SEARCH_ENGINE_ID'];

function rechercheGoogle($query, $start = 1) {
    $apiKey = $_ENV['GOOGLE_API_KEY'];
    $searchEngineId = $_ENV['GOOGLE_SEARCH_ENGINE_ID'];
    $url = "https://www.googleapis.com/customsearch/v1?key=" . $apiKey . "&cx=" . $searchEngineId . "&q=" . urlencode($query) . "&start=" . $start . "&num=10";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Limite de temps pour la requête
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

function rechercheGoogleImages($query, $start = 1) {
    $apiKey = $_ENV['GOOGLE_API_KEY'];
    $searchEngineId = $_ENV['GOOGLE_SEARCH_ENGINE_ID'];
    $url = "https://www.googleapis.com/customsearch/v1?key=" . $apiKey . "&cx=" . $searchEngineId . "&q=" . urlencode($query) . "&start=" . $start . "&num=10&searchType=image";
$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Limite de temps pour la requête
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

function getFaviconUrl($siteUrl) {
    return "https://www.google.com/s2/favicons?domain=" . urlencode($siteUrl);
}

$keyConcepts = '';
$resultatsImages = [];
$afficherTexteIntro = true;
$afficherImageIntro = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recherche'])) {
    $afficherTexteIntro = false;
    $afficherImageIntro = false;
    $recherche = $_POST['recherche'];
    $typeRecherche = $_POST['typeRecherche'] ?? 'simple';

    if ($typeRecherche == 'ia') {
        // Traitement de la recherche avec l'IA
        $messages = [
            ["role" => "system", "content" => "Listez directement jusqu'à 4 mots-clés pertinents issus de la requête de l'utilisateur, sans explications ni commentaires. Afin d'orienter efficacement une recherche Google, incluez jusqu'à 2 mots-clés principaux directement extraits de la requête pour garantir que la réponse reste centrée sur le sujet demandé. Complétez avec 2 mots-clés supplémentaires pertinents et en lien direct avec la requête initiale, enrichissant ainsi celle-ci de votre esprit de déduction."],
            ["role" => "user", "content" => $recherche]
        ];

        try {
            $response = $openAI->queryGPT($messages);
            if (!$response || !isset($response['choices'][0]['message']['content'])) {
                throw new Exception('Aucune réponse de l\'IA');
            }
            $keyConcepts = $response['choices'][0]['message']['content'];
        } catch(Exception $e) {
            error_log("Erreur OpenAI : " . $e->getMessage());
            echo "<p>Erreur lors de la récupération des résultats de l'IA. Veuillez réessayer plus tard.</p>";
        }
    } else {
        // Traitement de la recherche simple
        $keyConcepts = $recherche; // Utiliser directement la requête utilisateur
    }

    try {
        $resultatsGoogle = rechercheGoogle($keyConcepts, 1);
        $resultatsImages = rechercheGoogleImages($keyConcepts, 1);
        if (empty($resultatsGoogle['items']) && empty($resultatsImages['items'])) {
            $afficherTexteIntro = true;
            $afficherImageIntro = true;
        }
    } catch(Exception $e) {
        error_log("Erreur lors de la recherche Google : " . $e->getMessage());
        echo "<p>Erreur lors de la récupération des résultats de recherche Google. Veuillez réessayer plus tard.</p>";
    }
}


function displayTextIntro() {
    global $afficherTexteIntro;
    if ($afficherTexteIntro) {
        echo "<div id='introText'>
                <h2>Explorez une nouvelle dimension de la recherche avec Equal-Exchange et cherchez AUTREMENT</h2> 
                <p>Notre moteur de recherche révolutionnaire analyse vos requêtes avec l'intelligence d'OpenAI pour des résultats Google affinés.</p> 
                <p>Cherchez, posez vos questions, et découvrez des réponses pertinentes là où les recherches traditionnelles atteignent leurs limites.</p> 
                <p>Rejoignez-nous dans cette aventure novatrice pour une recherche sur Internet plus intuitive et efficace.</p>
            </div>";
    }
}
    function displayImageIntro() {
        global $afficherImageIntro;
    if ($afficherImageIntro) {
        echo "<div id='introImage'>";
        echo "<img src='logo_large.png' alt='Logo Equal-Exchange'>";
        echo "</div>";
    }
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="index.css">
    <link rel="shortcut icon" href="logo_EQEX.ico" type="image/x-icon" />
    <meta property="og:image" content="https://equal-exchange.com/logo_large.png"/>
    <meta name="theme-color" content="#FFFFFF">

    <meta name="description" content="Equal-Exchange : moteur de recherche innovant alliant OpenAI et Google pour une expérience de recherche intuitive et efficace.">

    <meta name="keywords" content="recherche assistée par IA, optimisation de recherche par IA, moteur de recherche hybride, technologie de recherche avancée, recherche intelligente sur internet, moteur de recherche révolutionnaire, intégration OpenAI Google, recherche web nouvelle génération, système de recherche intuitif, pertinence accrue en recherche web, analyse IA des requêtes, expérience de recherche améliorée, filtrage IA de recherches Google, innovation en recherche web">
    <meta name="author" content="Victor DUPREZ - Equal-Exchange">

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6308308780527097"
     crossorigin="anonymous"></script>

    <meta name="twitter:card" content="summary_large_image">    
    <meta name="twitter:site" content="@iqualexchange">
    <meta name="twitter:title" content="Equal-Exchange - La recherche innovante">
    <meta name="twitter:description" content="Découvrez une nouvelle manière de rechercher avec l'intelligence artificielle d'OpenAI et Google.">
    <meta name="twitter:image" content="https://equal-exchange.com/logo_large.png">

    <link rel="canonical" href="https://www.equal-exchange.com/index.php">

    <link rel="manifest" href="manifest.json">

    <title>Equal-Exchange : Moteur de Recherche Innovant avec IA</title>

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

                        <a href="register.php"><h3>instription</h3></a>

                        <a href="login.html"><h3>connexion</h3></a>


</header>

 <main>
    <div class="tab">
        <button onclick="openTab('textResults')">Résultats Texte</button>
        <button onclick="openTab('imageResults')">Résultats Image</button>
    </div>

<section>
    <form action="index.php" method="post">
        <input type="text" name="recherche" placeholder="Rechercher...">
        <div>
    <input type="radio" id="rechercheSimple" name="typeRecherche" value="simple">
    <label class="radio-label" for="rechercheSimple">Recherche Simple</label>

    <input type="radio" id="rechercheIA" name="typeRecherche" value="ia" checked>
    <label class="radio-label" for="rechercheIA">Recherche avec IA</label>
</div>
        <button type="submit">Rechercher</button>
    </form>
</section>

    <section id="textResults" class="tabContent">
    <?php
    if ($afficherTexteIntro) {
    // Affiche le texte d'introduction si aucune recherche n'a été effectuée
    displayTextIntro();
} else {
    if (!empty($resultatsGoogle['items'])) {
        if (!empty($keyConcepts)) {
                echo "<div class='reponse-openai'>";
                echo "<p>Concepts clés identifiés par l'IA : " . htmlspecialchars($keyConcepts) . "</p>";
                echo "</div>";
            }
        echo "<h2>Résultats de la recherche Google :</h2>";
        foreach ($resultatsGoogle['items'] as $item) {
    $faviconUrl = getFaviconUrl($item['link']); // Récupération de l'URL du favicon
    echo "<a href='" . htmlspecialchars($item['link']) . "' target='_blank' class='result-link'>";
    echo "<div class='resultat-texte'>";
    echo "<img src='" . htmlspecialchars($faviconUrl) . "' alt='Favicon' class='favicon'>"; // Affichage du favicon
    echo "<h3>" . htmlspecialchars($item['title']) . "</h3>";
    echo "<p>" . htmlspecialchars($item['snippet']) . "</p>";
    echo "</div>";
    echo "</a>";
}

    } else {
        echo "<p style='color: white;'>Aucun résultat trouvé ou erreur lors de la recherche Google.</p>";
    }
}
    ?>
</section>

<section id="imageResults" class="tabContent" style="display:none;">
    <?php
    if ($afficherImageIntro) {
        // Affiche le texte d'introduction si aucune recherche n'a été effectuée
        displayImageIntro();
    }
    if (!empty($resultatsImages['items'])) {
        echo !empty($keyConcepts) ? "<div class='reponse-openai'><p>Concepts clés identifiés par l'IA : " . htmlspecialchars($keyConcepts) . "</p></div>" : '';
        echo "<h2>Résultats de la recherche Google :</h2>";
        echo "<div class='grid-container-images'>"; // Début du conteneur de grille
        foreach ($resultatsImages['items'] as $image) {
    echo "<div class='resultat-image'>";
    // Ajoutez un gestionnaire d'erreur onerror à la balise img
    echo "<img src='" . htmlspecialchars($image['link']) . "' alt='" . htmlspecialchars($image['snippet']) . "' onerror='this.onerror=null;this.src=\"logo_large.png\";'>";
    echo "</div>";
}

        echo "</div>"; // Fin du conteneur de grille
    } else {
        echo "<p style='color: white;'>Aucun résultat d'image trouvé ou erreur lors de la recherche Google.</p>";
    }
    ?>
</section>



</main>

    <footer>
    <a href="politique_cookies.html" class="pc" title="Politique de confidentialité">Politique de confidentialité</a>
    <a href="mentions.html" class="mentions" title="Mentions légales">Mentions légales</a>
    </footer>

    <script defer>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.querySelector('form');
        var loadingIndicator = document.getElementById('loadingIndicator');

        form.addEventListener('submit', function() {
            loadingIndicator.style.display = 'block';
        });

        window.addEventListener('load', function() {
            loadingIndicator.style.display = 'none';
        });

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('service-worker.js')
            .then(function(registration) {
                console.log('Service Worker Registered', registration);
            })
            .catch(function(error) {
                console.log('Service Worker Registration Failed', error);
            });
        }
    });

    function openTab(tabName) {
        var i, tabcontent;
        tabcontent = document.getElementsByClassName("tabContent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        document.getElementById(tabName).style.display = "block";
    }
</script>





</body>
</html>
