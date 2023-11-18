<?php 
// Inclusion de la bibliothèque PHPMailer
 require_once('PHPMailer-master/src/PHPMailer.php');
  require_once('PHPMailer-master/src/SMTP.php');
  require_once('PHPMailer-master/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;

  
// Configuration de l'objet PHPMailer
$email = new PHPMailer();
$email->Host = 'mail.gandi.net'; // Serveur SMTP
$email->SMTPAuth = true; // Authentification SMTP activée
$email->Username = 'Victor@Equal-exchange.com'; // Adresse e-mail de l'expéditeur
$email->Password = 'Schpountz2233@'; // Mot de passe de l'expéditeur
$email->SMTPSecure = 'tls'; // Utilisation du chiffrement TLS
$email->Port = 587; // Port SMTP

// Connexion à la base de données
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=e.e;charset=utf8', 'root', '');
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

if(isset($_POST['email'])) {
    $user_email = $_POST['email'];
    $user_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$user_email) {
        // L'email n'est pas valide, afficher un message d'erreur ou rediriger vers une autre page
    } else {
        //Générer un code de réinitialisation unique
        $reset_code = bin2hex(random_bytes(16));

        // Mettre à jour la base de données avec le code de réinitialisation
        $req = $bdd->prepare("UPDATE membres SET reset_code = ? WHERE mail = ?");
        $req->execute(array($reset_code, $user_email));

        // Adresse e-mail de l'utilisateur
        $to = $user_email;

        // Sujet de l'e-mail
        $subject = "Réinitialisation de mot de passe";

        // Message de l'e-mail avec le lien de réinitialisation
        $message = "Bonjour,\n\nVous avez demandé une réinitialisation de votre mot de passe sur notre site. Cliquez sur le lien ci-dessous pour choisir un nouveau mot de passe :\n\n";
        $message .= "https://Equal-exchange.com/reinitialisation.php?email=".urlencode($user_email)."&reset_code=".urlencode($reset_code)."\n\n";
        $message .= "Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer cet e-mail.\n\nCordialement,\nL'équipe de Equal-exchange.com";

        // Corps de l'e-mail
        $body = $message;

        // Destinataire de l'e-mail
        $email->addAddress($to);

        // Sujet de l'e-mail
        $email->Subject = $subject;

        // Corps de l'e-mail
        $email->Body = $body;

        // Envoi de l'e-mail
        if(!$email->send()) {
            echo 'Erreur lors de l\'envoi du message : ' . $email->ErrorInfo;
        } else {
            echo 'Message envoyé !';
        }
    }
}

?>

<!DOCTYPE html>

<html>

	<head>

		<meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

			<link rel="stylesheet" href="MdpOublie.css">

				<title> Equal-E </title>
	</head>
	

					<body>


<header>
                    
                    
                        
                        <img src="logo/Equal-mascot-mini.png">

                        <p> Version bêta </p>

                    
                        

                            <a href="index.php" style="
                float: right;
                color: green;"> Accueil </a>



                            </header>

                            <section>



<form action="MdpOublie.php" method="post">
    <label for="email">Adresse email :</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Envoyer</button>
</form>












                                <p>Chères Utilisateurs</p>



<p>Nous sommes au regret de vous informer que notre service de réinitialisation des mots de passe oubliés est pour le moment en cours de création.</p>



<p>Mais il est tellement simple de se créer un nouveau compte que nous vous recommandons cette solution.</p>



<p>Vous pouvez aussi nous envoyer un email à cette adresse, equalexchanges@gmail.com, afin que nous procédions à la suppression de votre compte dans les meilleurs délais.</p>



<p>Nous vous prions de nous excuser pour la gêne occasionnée.</p>



    <!-- <h1>Mot de passe oublié</h1>


    <form action="MdpOublie.php" method="post">
      <label for="mail">Entrez votre adresse email :</label>
      <input type="email" id="mail" name="mail" required>
      <input type="submit" value="Envoyer">
    </form> -->
  

</section>


					</body>
</html>