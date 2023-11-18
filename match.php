<?php 
session_start();

                                    try {
                                    $bdd = new PDO('mysql:host=localhost;dbname=e.e;charset=utf8', 'root', '');
                                    } catch(Exception $e) {
                                        die('Erreur : '.$e->getMessage());
                                    }


if(isset($_SESSION['id']))
{
// Récupération des informations de l'utilisateur connecté
$id_membre = $_SESSION['id'];

$stmt_membre = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
$stmt_membre->execute([$id_membre]);
$membre = $stmt_membre->fetch(PDO::FETCH_ASSOC);

$stmt_annonce = $bdd->prepare("SELECT * FROM annonce WHERE id_membre = ?");
$stmt_annonce->execute([$id_membre]);
$annonces = $stmt_annonce->fetchAll(PDO::FETCH_ASSOC);

$stmt_objet_service = $bdd->prepare("SELECT * FROM objet_service WHERE id_membre = ?");
$stmt_objet_service->execute([$id_membre]);
$objet_services = $stmt_objet_service->fetchAll(PDO::FETCH_ASSOC);

// Recherche des correspondances avec d'autres utilisateurs
$stmt_correspondances = $bdd->prepare("
    SELECT membres.pseudo, membres.id, COUNT(*) AS score
    FROM membres
    INNER JOIN annonce ON membres.id = annonce.id_membre
    INNER JOIN objet_service ON membres.id = objet_service.id_membre
    WHERE (
        annonce.Titre_annonce IN (
            SELECT Titre FROM objet_service WHERE id_membre = ?
        )
        OR objet_service.Titre IN (
            SELECT Titre_annonce FROM annonce WHERE id_membre = ?
        )
    )
    AND (
        annonce.Pays_annonce = objet_service.Pays
        AND annonce.Region_annonce = objet_service.Region
        AND annonce.Departement_annonce = objet_service.Departement
        AND annonce.Commune_Ville_annonce = objet_service.Commune_Ville
        AND annonce.Quartier_annonce = objet_service.Quartier
        AND annonce.Rue_annonce = objet_service.Rue
    )
    AND membres.id != ?
    GROUP BY membres.id
    ORDER BY score DESC
");
$stmt_correspondances->execute([$id_membre, $id_membre, $id_membre]);
$correspondances = $stmt_correspondances->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" href="match.css">

                <title> Equal-E </title>
</head>
<body>

<header>
                    
                    
                        
                            <img src="logo/Equal-mascot-mini.png">

                            <p> Version bêta </p>

                    
                        
<?php

                            if(isset($_SESSION['id']))
                            {
?>
                            <a href="deconnexion.php?id=<?php echo $_SESSION['id']; ?>">Se déconnecter</a>
<?php

                            }

?>


                            </header>
                            
                            <section>

                            <article>

                    <div class="contenu-section">
                    <div class="menu-container">
                <nav class="menu-content">
<?php

                            if(isset($_SESSION['id']))
                            {
?>
                            <a href="editionprofil.php?id=<?php echo $_SESSION['id']; ?>">Modifier mon profil</a>
                            &ensp;
                            <a href="rechercheos.php?id=<?php echo $_SESSION['id']; ?>">Offres disponibles</a>
                            &ensp;
                            <a href="ajoutobjet.php?id=<?php echo $_SESSION['id']; ?>">
                            Ajouter un objet ou un service</a>
                            &ensp;
                            <a href="Mesoffres.php?id=<?php echo $_SESSION['id']; ?>">
                            Ce que je propose</a>
                            &ensp;
                            <a href="recherchea.php?id=<?php echo $_SESSION['id']; ?>">Ce que les gens cherchent</a>
                            &ensp;
                            <a href="ajoutannonce.php?id=<?php echo $_SESSION['id']; ?>">
                            Je publie une annonce</a>
                            &ensp;
                            <a href="Mesannonces.php?id=<?php echo $_SESSION['id']; ?>">Mes annonces</a>
                            &ensp;
                            <a href="reception.php?id=<?php echo $_SESSION['id']; ?>">Mes messages</a>

<?php

}

?>
</nav>
                    </div>
                            </div>


                    </article>

<article class="contenu-article">

    <h1> Les profils qui vous correspondent : </h1>
    
    <?php if (empty($correspondances)): ?>
        <p>Aucune correspondance trouvée.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Pseudo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($correspondances as $correspondance): ?>
                    <tr>
                        <td><?php echo $correspondance['pseudo']; ?></td>
                        
                        <td><a href="échanger.php?id=<?= $correspondance['id'] ?>">Proposer un échange</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</article>

                        </section>

<?php
}
else
{
    header("Location: connexion.php");
}
?>

</body>
</html>
