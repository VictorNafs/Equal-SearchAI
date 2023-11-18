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
$an = $bdd->prepare('SELECT * FROM annonce WHERE id_annonce = ?');
$an->execute(array($_GET['id']));

                            

?>


<!DOCTYPE html>

<html>
    
    <head>
            
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">
                
            <link rel="stylesheet" href="sonannonce.css">

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

                            <article class="contenu-article" align="center">


                        

                            <?php if($donnees = $an->fetch())
                            {

                            ?>

                            <h1> 
                                
                            <?php 

                                echo $donnees['Titre_annonce'];

                            ?>

                            </h1>

                            <?php
        
                            $an_exp = $bdd->prepare('SELECT membres.id FROM membres INNER JOIN annonce ON membres.id = annonce.id_membre WHERE annonce.id_annonce = ?');
                            $an_exp->execute(array($_GET['id']));
                            $id_membre = $an_exp->fetch();
                            
                           ?>

                            <a href="échanger.php?id=<?= $id_membre['id'] ?>"><h3>Echanger</h3></a>

                            <?php

                                echo '<br><br><br>';

                                    if(!empty($donnees['Photos_annonce']))
                                    {
                                        if($an)
                                        {
                                        $SelectPhotos = $bdd->prepare('SELECT Photos_annonce FROM annonce WHERE id_annonce = ?');
                                        $SelectPhotos->execute(array($_SESSION['id']));
                                        }
                                        echo '<img src="Annonce/P_Annonce';
                                        echo $donnees['Photos_annonce'];
                                        echo ' "width="150">';
                                    }

                                    echo '<br>';

                            echo $donnees['Categorie_annonce']; 

                                echo '<br>';

                            echo $donnees['Description_annonce']; 

                                echo '<br>';

                            echo $donnees['Pays_annonce'];

                                echo '<br>';

                            echo $donnees['Region_annonce']; 

                                echo '<br>';

                            echo $donnees['Departement_annonce'];

                                echo '<br>';

                            echo $donnees['Commune_Ville_annonce'];

                                echo '<br>';

                            echo $donnees['Quartier_annonce'];

                                echo '<br>';

                            echo $donnees['Rue_annonce'];

                                echo '<br>';

                            ?>
   
                            <a href="supprimera.php?numDonnees=<?= $donnees['id_annonce'] ?>"> Supprimer </a>

                            <?php
                        
                            }

                            $an->closeCursor();

                            ?>


                            </article>

                        </section>

<?php

}

?>

                </body>

</html>

