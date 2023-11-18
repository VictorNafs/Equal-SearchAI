<?php 
session_start();

                            try
                            {
                                $bdd = new PDO('mysql:host=localhost;dbname=e.e;charset=utf8', 'root', '');
                            }

                            catch(Exception $e)
                            {
                                die('Erreur : '.$e->getMessage());
                            }

if(isset($_GET['id']) AND $_GET['id'] > 0 OR isset($_SESSION['id']))
{
    $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();

    if(isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo'])
    {
        $newpseudo = htmlspecialchars($_POST['newpseudo']);
        $insertpseudo = $bdd->prepare("UPDATE membres SET pseudo = ? WHERE id = ?");
        $insertpseudo->execute(array($newpseudo, $_SESSION['id']));
        header('Location: editionprofil.php?id=' . $_SESSION['id']);
    }

    if(isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2']))
    {
        $mdp1 = sha1($_POST['newmdp1']);
        $mdp2 = sha1($_POST['newmdp2']);

        if($mdp1 == $mdp2)
        {
            $insertmdp = $bdd->prepare("UPDATE membres SET motdepasse = ? WHERE id = ?");
            $insertmdp->execute(array($mdp2, $_SESSION['id']));
            header('Location: editionprofil.php?id=' . $_SESSION['id']);
        }
        else
        {
            $msg = "Vos deux mots de passe ne correspondent pas !";
        }
    }

    



?>

<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" href="editionprofil.css">

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
                            <a href="deconnexion.php?id=<?php echo $_SESSION['id']; ?>"style ="
                            font-size: 20px">Se déconnecter</a>
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
                    
                <div class="contenu-article" align="center">
                    
                    <div id="section2">


                    
                            <h1 style="color: white;">Modifier mon profil :</h1>



                    </div>


                            <article>


                        
                            <form method="POST" action="" enctype="multipart/form-data">
                                <table>
                                    <tr>
                                        <td align="right">
                                            <label>Pseudo :</label>
                                        </td>
                                        <td>
                                            <input type="text" name="newpseudo" placeholder="Pseudo" value="<?php echo $user['pseudo']; ?>">
                                        </td>
                                    </tr>
                                    
                                    <tr>

                                        <td align="right">
                                            <label>Mot de passe :</label>
                                        </td>
                                        <td>
                                            <input type="password" name="newmdp1" placeholder="Your password...">
                                        </td>
                                        </tr>
                                        <td align="right">
                                            <label>Confirmez votre mot de passe :</label>
                                        </td>
                                        <td>
                                            <input type="password" name="newmdp2" placeholder="Confirm your password...">
                                        </td>
                                    </tr>

                                    </tr>
                                    
                                    <tr>
                                        <td></td>
                                        <td>
                                        <br>
                                            <input type="submit" value="Modifier mon profil !">
                                        </td>
                                    </tr>
                                    
                                </table>
                            </form>
                            <?php 

                            if(isset($msg)) 
                            { 
                                echo $msg; 
                            } 

                            ?>



                            </article>



                        </div>
                        
                        <a href="supprimec.php?numDonnees=<?= $_SESSION['id'] ?>" style="
                        color: white;
                        font-size: 20px">Supprimer mon compte </a>

                        
                            


<?php
}
else
{
    header("Location: connexion.php");
}
?>



                    </body>
</html>
