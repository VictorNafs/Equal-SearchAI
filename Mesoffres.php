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
$id = $_SESSION['id'];
if(array($_SESSION['id']))
{	
$reponse = $bdd->query("SELECT * FROM objet_service INNER JOIN membres ON objet_service.id_membre = membres.id
			WHERE objet_service.id_membre = '$id'");
$reponse->execute(array($_SESSION['id']));
		
	
	
?>


<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        	<link rel="stylesheet" href="Mesoffres.css">

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

							<h1>Ce que je propose :</h1>


							<?php 

							while($donnees = $reponse->fetch())
							{
								echo $donnees['Titre'];

									echo '<br><br><br>';

								if(!empty($donnees['Photos']))
	                            {
	                            	if($reponse)
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


<a href="supprimeros.php?numDonnees=<?= $donnees['id_objet'] ?>"> Delete </a>


<?php
						
							}

							$reponse->closeCursor();
							


                            
?>                            
              
</article>

                        </section>
                            
<?php

}

?>				
				</body>

</html>

