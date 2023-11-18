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

$msg = $bdd->prepare('SELECT * FROM messages WHERE id_destinataire = ? ORDER BY id DESC');
$msg->execute(array($_SESSION['id']));
$msg_nbr = $msg->rowCount();




                            

?>


<!DOCTYPE html>

<html>
        
    <head>
        
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" href="reception.css">
                
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

         
                            <h1>Mes messages :</h1>

                        

                            <?php
                            if($msg_nbr == 0)
                            {
                               echo "Vous n'avez aucun message..."; 
                            }
                            while($m = $msg->fetch())
                            {
                            $p_exp = $bdd->prepare('SELECT * FROM membres WHERE id = ?');
                            $p_exp->execute(array($m['id_expediteur']));
                            $p_exp = $p_exp->fetch();
                            $p_exp = $p_exp['pseudo'];
                            ?>

                            <?php if($m['lu'] == 1)
                            {
                            ?>
                            <span style="color:grey"><i>Lu</i>
                            <?php
                            }
                            ?>
                            <b><?= $p_exp ?></b> Vous a envoyé 

<?php


?>



                            <li><br><a href="échanger.php?id=<?= $m['id_expediteur'] ?>">un message</a></li>
                            <?php 

                            if($m['lu'] == 1)
                            {
                            ?>
                            </span>
                            <?php
                            }
                            ?>
                            <br>
                            ----------------------------------
                            <br>
                            <?php 
                        
                            }
                            ?>
</article>
                            </section>
<?php
}
?>

                        </body>
    </html>

