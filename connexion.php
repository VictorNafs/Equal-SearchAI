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

if(isset($_POST['formconnexion']))
{
$mailconnect = htmlspecialchars($_POST['mailconnect']);
$mdpconnect = sha1($_POST['mdpconnect']);
    
    if(!empty($mailconnect) AND !empty($mdpconnect))
    {
    $requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ? AND motdepasse = ?");
    $requser->execute(array($mailconnect, $mdpconnect));
    $userexist = $requser->rowCount();
        if($userexist == 1)
        {
        $userinfo = $requser->fetch();
        $_SESSION['id'] = $userinfo['id'];
        $_SESSION['pseudo'] = $userinfo['pseudo'];
        $_SESSION['mail'] = $userinfo['mail'];
        header("Location: annoncesobjets.php?id=" . $_SESSION['id']); 
        }
        else
        {
            $erreur = "Wrong email or wrong password !";
        }
    }
    else
    {
        $erreur = "All fields must be completed !";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="connexion.css">
    <title>Equal.E</title>
</head>

<body>
    <div id="header">
        <header>
            <div class="contenu">
                <img src="logo/Equal-mascot-mini.png">
                <p>Version bêta</p>
                <a href="index.php" style="
                float: right;
                color: white;"> Accueil </a>
            </div>
        </header>
    </div>
    <div id="section">
        <section>
            
        </section>
    </div>
    <div id="article">
        <article>
            <div class="contenu-article">
                <div align="center">
                    <br><br><br>
                    <h1 style="color: green;">CONNEXION</h1>
                    <form method="POST" action="">
                        <input type="email" name="mailconnect" placeholder="Email...">
                        <input type="password" name="mdpconnect" placeholder="Password...">
                        <input type="submit" name="formconnexion" value="Log in">
                    </form>
                    <a href="MdpOublie.php">Mot de passe oublié ?</a>
                    <?php
                    if(isset($erreur))
                    {
                        echo '<font color="red">' . $erreur . "</font>";
                    }
                    ?>
                </div>
            </div>
        </article>
    </div>
</body>

</html>
