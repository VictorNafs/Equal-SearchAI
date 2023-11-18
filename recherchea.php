<?php 
session_start();

                            try
                            {
                                $bdd = new PDO('mysql:host=localhost;dbname=e.e;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                            }

                            catch(Exception $e)
                            {
                                die('Erreur : '.$e->getMessage());
                            }

if(isset($_SESSION['id']) AND !empty($_SESSION['id']))
{

$an = $bdd->prepare('SELECT * FROM membres INNER JOIN annonce ON membres.id = annonce.id_membre ORDER BY id_annonce DESC');
$an->execute(array($_SESSION['id']));
$an_nbr = $an->rowCount();


	
$articles = $bdd->query('SELECT Titre_annonce, CONCAT(Categorie_annonce, Description_annonce, Pays_annonce, Region_annonce, Departement_annonce, Commune_Ville_annonce, Quartier_annonce, Rue_annonce) concatenation FROM annonce ORDER BY id_annonce DESC');

    

?>


<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

<script src="path/to/script.js"></script>

            <link rel="stylesheet" href="recherchea.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


                <title> Equal.E </title>

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



<!-- <div align="center"> -->

<!-- <form method="GET"> -->

<!--	<input type="search" name="q" placeholder="Recherche...">
	<input type="submit" value="Valider">
-->
<!-- </form> -->

<!-- </div> -->


<article class="contenu-article">



<?php 

if($articles->rowCount() > 0)
{ 

    while($a = $articles->fetch() AND $id_an = $an->fetch()) 
    {
    $an_exp = $bdd->prepare('SELECT * FROM membres INNER JOIN annonce ON membres.id = annonce.id_membre WHERE id_annonce = ?');
    $an_exp->execute(array($id_an['id_annonce']));
    $an_exp = $an_exp->fetch();
    $an_exp = $an_exp['id_membre'];
                            
?>



<br> 

        <a href="sonannonce.php?id=<?= $id_an['id_annonce'] ?>&amp;<?= $an_exp ?>"><?= $a['Titre_annonce'] ?>
        </a> 

    <br> 

        <?= $a['concatenation']?>

	<?php 

}
} 


} 
else 
{ 
?>

</article>
                            </section>

Aucun résultats...

<?php 

} 

?>
							


                </body>

</html>