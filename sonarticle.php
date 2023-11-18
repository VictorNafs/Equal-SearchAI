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
$o_s = $bdd->prepare('SELECT * FROM objet_service WHERE id_objet = ?');
$o_s->execute(array($_GET['id']));

                            

?>


<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

                <link rel="stylesheet" href="sonarticle.css">

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

                            <article class="contenu-article">
                        
                            

                            <?php if($donnees = $o_s->fetch())
                            {
                                
                            ?>

                            <h1> 

                            <?php 

                            echo $donnees['Titre']; 

                            ?>

                            </h1>

                            <?php
        
                            $o_s_exp = $bdd->prepare('SELECT membres.id FROM membres INNER JOIN objet_service ON membres.id = objet_service.id_membre WHERE objet_service.id_objet = ?');
                            $o_s_exp->execute(array($_GET['id']));
                            $id_membre = $o_s_exp->fetch();
                            
   
     
                           ?>

                            <a href="échanger.php?id=<?= $id_membre['id'] ?>"><h3>Echanger</h3></a>

                            <?php

                                echo '<br><br><br>';

                                    if(!empty($donnees['Photos']))
                                    {
                                        if($o_s)
                                        {
                                        $SelectPhotos = $bdd->prepare('SELECT Photos FROM objet_service WHERE id_objet = ?');
                                        $SelectPhotos->execute(array($_SESSION['id']));
                                        }
                                        echo '<img src="Objet_Service/P_Objet_Service';
                                        echo $donnees['Photos'];
                                        echo ' "width="150">';
                                    }


                                echo '<br>';

                            echo $donnees['Categorie']; 
                                    
                                echo '<br>';

                            echo $donnees['Description']; 

                                echo '<br>';

                            echo $donnees['Pays'];

                                echo '<br>';

                            echo $donnees['Region']; 

                                echo '<br>';

                            echo $donnees['Departement'];

                                echo '<br>';

                            echo $donnees['Commune_Ville'];

                                echo '<br>';

                            echo $donnees['Quartier'];

                                echo '<br>';

                            echo $donnees['Rue'];

                                echo '<br>';

?>

                            <a href="supprimeros.php?numDonnees=<?= $donnees['id_objet'] ?>"> Supprimer </a>

<?php
                        
                            }

                            $o_s->closeCursor();
                            
?>

                        </article>

                        </section>
<?php

}

?>
                </body>

    </html>

