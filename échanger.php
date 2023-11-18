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






    if(isset($_GET['id']) AND !empty($_GET['id']))
    {
    $get_id = $_GET['id'];
    $recupMembre = $bdd->prepare('SELECT id_membre FROM objet_service');
    $recupMembre->execute(array($get_id));
	
        if($recupMembre->rowCount() > 0)
	    {
		  if(isset($_POST['envoyer']))
		  {
		  $message = htmlspecialchars($_POST['message']);
		  $insererMessage = $bdd->prepare('INSERT INTO messages(message, id_destinataire, id_expediteur) VALUES (?, ?, ?)');
		  $insererMessage->execute(array($message, $get_id, $_SESSION['id']));
          header('Location: échanger.php?id=' . $get_id);
		  }
	    }
	    else
	    {
		echo "No exchanger found";
	    }
	
    }
    else
    {
	echo "No username found";
    }

?>


<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" href="échanger.css">

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

<article class="contenu-article" style="margin-bottom: 0px;">




<?php 

$o_s_dest = $bdd->prepare('SELECT * FROM objet_service INNER JOIN membres ON objet_service.id_membre = membres.id WHERE objet_service.id_membre = ?');
$o_s_dest->execute(array($_GET['id'],));

$o_s_exp = $bdd->prepare('SELECT * FROM objet_service INNER JOIN membres ON objet_service.id_membre = membres.id WHERE objet_service.id_membre = ?');
$o_s_exp->execute(array($_SESSION['id']));    


?>
                        
<h1> Cette personne propose : </h1>
                                                
<?php


        while($donnees_dest = $o_s_dest->fetch())
        {
        $recup_o_s_dest = $bdd->prepare('SELECT * FROM membres INNER JOIN objet_service ON membres.id = objet_service.id_membre WHERE id_objet = ?');
        $recup_o_s_dest->execute(array($donnees_dest['id_objet']));
        $recup_o_s_dest = $recup_o_s_dest->fetch();
        $recup_o_s_dest = $recup_o_s_dest['id_membre'];

?>
        <a href="sonarticle.php?id=<?= $donnees_dest['id_objet'] ?>&amp;<?= $recup_o_s_dest ?>"> <?= $donnees_dest['Titre'] . " - " ?> </a>
        
<?php

        }

?>

<h1> Je propose : </h1>

<?php

        while($donnees_exp = $o_s_exp->fetch())
        {
        $recup_o_s_exp = $bdd->prepare('SELECT * FROM membres INNER JOIN objet_service ON membres.id = objet_service.id_membre WHERE id_objet = ?');
        $recup_o_s_exp->execute(array($donnees_exp['id_objet']));
        $recup_o_s_exp = $recup_o_s_exp->fetch();
        $recup_o_s_exp = $recup_o_s_exp['id_membre'];
        
?>
        <a href="sonarticle.php?id=<?= $donnees_exp['id_objet'] ?>&amp;<?= $recup_o_s_exp ?>"> <?= $donnees_exp['Titre'] . " - " ?> </a>
        
<?php

        }

?>


</article>

<article class="contenu-article" style="margin-top: 5px;">



<?php 

$an_dest = $bdd->prepare('SELECT * FROM annonce INNER JOIN membres ON annonce.id_membre = membres.id WHERE annonce.id_membre = ?');
$an_dest->execute(array($_GET['id'],));

$an_exp = $bdd->prepare('SELECT * FROM annonce INNER JOIN membres ON annonce.id_membre = membres.id WHERE annonce.id_membre = ?');
$an_exp->execute(array($_SESSION['id']));    

?>

<h1> Cette personne recherche : </h1>
                                                
<?php

        while($donnees_dest = $an_dest->fetch())
        {
        $recup_an_dest = $bdd->prepare('SELECT * FROM membres INNER JOIN annonce ON membres.id = annonce.id_membre WHERE id_annonce = ?');
        $recup_an_dest->execute(array($donnees_dest['id_annonce']));
        $recup_an_dest = $recup_an_dest->fetch();
        $recup_an_dest = $recup_an_dest['id_membre'];

?>

        <a href="sonannonce.php?id=<?= $donnees_dest['id_annonce'] ?>&amp;<?= $recup_an_dest ?>"> <?= $donnees_dest['Titre_annonce'] . " - " ?> </a>
        
<?php

        }

?>

<h1> Je recherche : </h1>

<?php

        while($donnees_exp = $an_exp->fetch())
        {
        $recup_an_exp = $bdd->prepare('SELECT * FROM membres INNER JOIN annonce ON membres.id = annonce.id_membre WHERE id_annonce = ?');
        $recup_an_exp->execute(array($donnees_exp['id_annonce']));
        $recup_an_exp = $recup_an_exp->fetch();
        $recup_an_exp = $recup_an_exp['id_membre'];
        
?>
        <a href="sonannonce.php?id=<?= $donnees_exp['id_annonce'] ?>&amp;<?= $recup_an_exp ?>"> <?= $donnees_exp['Titre_annonce'] . " - " ?> </a>
        
<?php

        }

?>

                        </article>


                            <article class="contenu-article" style="margin-top: 30px;">

 <form method="POST" action="">
                            
        <textarea name="message">

        </textarea>
                            
<br><br>
            
        <input type="submit" name="envoyer" value="Envoyer">
                        
    </form>


                    		
<?php 

$recupMessages = $bdd->prepare('SELECT * FROM messages WHERE id_expediteur = ? AND id_destinataire = ? OR id_expediteur = ? AND id_destinataire =? ORDER BY id DESC');
$recupMessages->execute(array($_SESSION['id'], $get_id, $get_id, $_SESSION['id']));
                    		
    while($message = $recupMessages->fetch())
    {
        if($message['id_destinataire'] == $_SESSION['id'])
        {

?>	
                    			
<p style="
color: red;
font-size: 20px;"> - <?= $message['message']; ?></p>
                    		
<?php
        
        }
        
        elseif($message['id_destinataire'] == $get_id) 
        {

?>	
                    			
<p style="
color: green;
font-size: 20px;"> - <?= $message['message']; ?></p>
                    		
<?php
        
        }
    }

?>

</article>



                    </body>

</html>

<?php
}
?>