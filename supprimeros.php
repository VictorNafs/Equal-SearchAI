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

if(isset($_SESSION['id']))
{

$reponse = $bdd->prepare("DELETE FROM objet_service WHERE id_objet =:num LIMIT 1");

$reponse->bindValue(':num', $_GET['numDonnees'], PDO::PARAM_INT);

$executeIsOk = $reponse->execute();

if($executeIsOk)
{
	$message = "Cet objet ou service à bien été supprimé !";
}
else
{
	$message = "Echec de la suppression de cet objet/service";
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" href="supprim.css">

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
                <div class="menu-header">
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
                        </div>


                    </article>


                    <p style="margin-top: 50%;"><?= $message ?></p>



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