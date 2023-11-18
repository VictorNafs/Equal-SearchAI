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

$o_s = $bdd->prepare('SELECT * FROM membres INNER JOIN objet_service ON membres.id = objet_service.id_membre ORDER BY id_objet DESC');
$o_s->execute(array($_SESSION['id']));
$o_s_nbr = $o_s->rowCount();



$articles = $bdd->query('SELECT Titre, CONCAT(Categorie, Description, Pays, Region, Departement, Commune_Ville, Quartier, Rue) concatenation FROM objet_service ORDER BY id_objet DESC');



?>


<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

<script src="path/to/script.js"></script>

            <link rel="stylesheet" href="rechercheos.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


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
                            <a href="deconnexion.php?id=<?php echo $_SESSION['id']; ?>" style="float: right;">Se déconnecter</a>
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


           

<?php 

if($articles->rowCount() > 0)
{ 

    
   while($a = $articles->fetch() AND $id_o_s = $o_s->fetch()) 
    {
    $o_s_exp = $bdd->prepare('SELECT * FROM membres INNER JOIN objet_service ON membres.id = objet_service.id_membre WHERE id_objet = ?');
    $o_s_exp->execute(array($id_o_s['id_objet']));
    $o_s_exp = $o_s_exp->fetch();
    $o_s_exp = $o_s_exp['id_membre'];
        
?>



    <br>
        <a href="sonarticle.php?id=<?= $id_o_s['id_objet'] ?>&amp;<?= $o_s_exp ?>"><br><?= $a['Titre'] ?>
        </a>


    <br> 

        <?= $a['concatenation'] ?>

<?php

} 
} 



}
else 
{
?>

</article>

                            </section>
Aucun résultats ...

<?php

}

?>


                            


                </body>

</html>



