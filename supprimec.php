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

$reponse_membres = $bdd->prepare("DELETE membres, messages, objet_service, annonce FROM membres 
LEFT JOIN messages ON (membres.id = messages.id)
LEFT JOIN objet_service ON membres.id = objet_service.id_membre
LEFT JOIN annonce ON membres.id = annonce.id_membre WHERE membres.id =:num");

$reponse_membres->bindValue(':num', $_GET['numDonnees'], PDO::PARAM_INT);

$executeMembresIsOk = $reponse_membres->execute();

if($executeMembresIsOk)
{
	header("Location: index.php");
}
else
{
	$message = "Echec de la suppression de votre compte";
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
            <title> Equal.E </title>

                </head>

                    <body>

                        <div align="center">
                            
                           <p><?= $message ?></p>


                    </body>
</html>

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