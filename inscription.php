
<?php 

							try
							{
								$bdd = new PDO('mysql:host=localhost;dbname=e.e;charset=utf8', 'root', '');
							}

							catch(Exception $e)
							{
								die('Erreur : '.$e->getMessage());
							}

if(isset($_POST['forminscription']))
{
if(isset($_POST['checked']))
{
$pseudo = htmlspecialchars($_POST['pseudo']);
$mail = htmlspecialchars($_POST['mail']);
$mail2 = htmlspecialchars($_POST['mail2']);
$mdp = sha1($_POST['mdp']);
$mdp2 = sha1($_POST['mdp2']);

	if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2']))
	{
	$pseudolength = strlen($pseudo);
		
		if($pseudolength <= 255)
		{
			if($mail == $mail2)
			{
				if(filter_var($mail, FILTER_VALIDATE_EMAIL))
				{
				$reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
				$reqmail->execute(array($mail));
				$mailexist = $reqmail->rowCount();
					if($mailexist == 0)
					{
						if($mdp == $mdp2)
						{
						$insertmbr = $bdd->prepare("INSERT INTO membres(pseudo, mail, motdepasse) VALUES(?, ?, ?)");
						$insertmbr->execute(array($pseudo, $mail, $mdp));
						$erreur = "Votre compte à bien été créé ! <a href=\"connexion.php\">Me connecter</a>";
						}
						else
						{
							$erreur = "mauvais mot de passe !";
						}
					}
					else
					{
						$erreur = "Cet email existe déjà !";
					}
				}
				else
				{
					$erreur = "Votre adresse email n'est pas valide !";
				}
			}
			else
			{
				$erreur = "Mauvaise adresse email !";
			}
		}
		else
		{
			$erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
		}

		$maillength = strlen($mail);
		if($maillength <= 255)
		{
			
		}
		else
		{
			$erreur = "Votre adresse email ne doit pas dépasser 255 caractères !";
		}


	}
	else
	{
		$erreur = "Tout les champs doivent être complétés !";
	}
}
}
?>

<!DOCTYPE html>

<html>

	<head>

		<meta charset="utf-8">

		<meta name="viewport" content="width=device-width, initial-scale=1">

			<link rel="stylesheet" href="inscription.css">

				<title> Equal-E </title>

	</head>

					<body>

				<div id="header">
					

						<header>

					<div class="contenu">
						
						<img src="logo/Equal-mascot-mini.png">

						<p> Version bêta </p>
						<a href="index.php" style="
                float: right;
                color: white;"> Accueil </a>

					</div>
					
						</header>

						
				</div>

				<div align="center">

				<div id="section">

						<section>

					<div class="contenu-section">
						
							
					
					</div>

						</section>

				</div>


				<div id="article">

						<article>

					<div class="contenu-article">

						<h1 style="
						color: green;"> INSCRIPTION </h1>

							<form method="POST" action="">
								<table>
									<tr>
										<td align="right">
											<label for="pseudo">Pseudo :</label>
										</td>
										<td>
											<input type="text" placeholder="Your username..." id="pseudo" name="pseudo" value="<?php if(isset($pseudo)) { echo $pseudo; } ?>">
										</td>
									</tr>
									<tr>
										<td align="right">
											<label for="mail">Email :</label>
										</td>
										<td>
											<input type="email" placeholder="Your email..." id="mail" name="mail" value="<?php if(isset($mail)) { echo $mail; } ?>">
										</td>
									</tr>
									<tr>
										<td align="right">
											<label for="mail2"> Confirmez votre email :</label>
										</td>
										<td>
											<input type="email" placeholder="Confirm your email..." id="mail2" name="mail2" value="<?php if(isset($mail2)) { echo $mail2; } ?>">
										</td>
									</tr>
									<tr>
										<td align="right">
											<label for="mdp">Mot de passe :</label>
										</td>
										<td>
											<input type="password" placeholder="Your password..." id="mdp" name="mdp">
										</td>
									</tr>
									<tr>
										<td align="right">
											<label for="mdp2">Confirmez votre mot de passe :</label>
										</td>
										<td>
											<input type="password" placeholder="Confirm your password..." id="mdp2" name="mdp2">
										</td>
									</tr>
									<tr>


									</tr>
									<tr>
										<td></td>
										<td>
											
											<input type="submit" name="forminscription" value="S'inscrire !">
											<input type="checkbox" name="checked"> J'accepte la <a href="pdc.php"> politique de confidentialité</a>

										</td>
									</tr>
								</table>
							</form>
							<?php
							if(isset($erreur))
							{
								echo '<font color="red">' . $erreur . "</font>" ;
							}
							?>
					</div>

						</article>

				</div>

				</div>


					</body>
</html>