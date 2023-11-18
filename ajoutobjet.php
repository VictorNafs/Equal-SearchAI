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


if(isset($_SESSION['id']))
{
    if(isset($_POST['formajoutobjet']) AND !empty($_POST['Titre']))
    {
        $id_objet = $_POST['id_objet'];
        $Titre = htmlspecialchars($_POST['Titre']);
        $Categorie = $_POST['Categorie'];
        $Description = htmlspecialchars($_POST['Description']);
        $extensionUpload = ($_FILES['Photos']['name']);
        $Pays = htmlspecialchars($_POST['Pays']);
        $Region = htmlspecialchars($_POST['Region']);
        $Departement = htmlspecialchars($_POST['Departement']);
        $Commune_Ville = htmlspecialchars($_POST['Commune_Ville']);
        $Quartier = htmlspecialchars($_POST['Quartier']);
        $Rue = htmlspecialchars($_POST['Rue']);
        $id_membre = $_SESSION['id'];

        $MaxidObjet = 1;
            $MaxidObjetRequet = $bdd->query('SELECT MAX(id_objet) max FROM objet_service');
        $MaxidObjetRequet->execute(array($_SESSION['id']));
        error_log("Victor dit");
        while($donneesMax = $MaxidObjetRequet->fetch())
        {
        $MaxidObjet = $MaxidObjet + $donneesMax['max'];
        error_log($donneesMax['max']);
        error_log($MaxidObjet);
        }
        $extensionUpload = (strtolower(substr(strrchr($_FILES['Photos']['name'], '.'), 1)));
        $filename = $_SESSION['id'] . "." . $MaxidObjet . "." . $extensionUpload;
        error_log($filename);


        if(!empty($_POST['Titre']) AND !empty($_POST['Categorie']) AND !empty($_POST['Description']))
        {
            $insertObjet = $bdd->prepare('INSERT INTO objet_service(Titre, Photos, Categorie, Description, Pays, Region, Departement, Commune_Ville, Quartier, Rue, id_membre) VALUES(:Titre, :Photos, :Categorie, :Description, :Pays, :Region, :Departement, :Commune_Ville, :Quartier, :Rue, :id_membre)');
            $insertObjet->execute(array(
                'Titre' => $Titre,
                'Photos' => $filename,
                'Categorie' => $Categorie,
                'Description' => $Description,
                'Pays' => $Pays,
                'Region' => $Region,
                'Departement' => $Departement,
                'Commune_Ville' => $Commune_Ville,
                'Quartier' => $Quartier,
                'Rue' => $Rue,
                'id_membre' => $_SESSION['id']
                ));
            header('Location: Mesoffres.php?id=' . $_SESSION['id']);
            
        }
        
        if(isset($_FILES['Photos']) AND !empty($_FILES['Photos']['name']))
            {
                $tailleMax = 2097152;
                $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');

                if($_FILES['Photos']['size'] <= $tailleMax)
                {
                    $extensionUpload = (strtolower(substr(strrchr($_FILES['Photos']['name'], '.'), 1)));

                    if(in_array($extensionUpload, $extensionsValides))
                    {
                        $chemin = "Objet_Service/P_Objet_Service" . $_SESSION['id'] . "." . $MaxidObjet . "." . $extensionUpload;
                        $resultat = move_uploaded_file($_FILES['Photos']['tmp_name'], $chemin);
                        if($resultat)
                        {

                        }
                    }

        
                    else
                    {
                        $msg = "La photo de votre objet/service doit être au format jpg, jpeg, gif ou png";
                    }
                }
                else
                {
                    $msg = "La photo de votre objet/service ne doit pas dépasser 2Mo";
                }
            }

        
    }
    
        



?>
<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" href="ajoutobjet.css">

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
                            <a href="match.php?id=<?php echo $_SESSION['id']; ?>">Mes match</a>

<?php

}

?>
</nav>
                    </div>
                            </div>
                        </div>


                    </article>


                            <article class="contenu-article" align="center">


                        




                        <h1 style="color: white;">Ajouter un objet ou un service :</h1>







                            <form method="POST" action="" enctype="multipart/form-data">
                                <table>
                                    <tr>
                                        <td align="right">
                                            <label>Nom de votre offre :</label>
                                        </td>
                                        <td>
                                            <input type="text" name="Titre" placeholder="Offer..." value=""> *
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <label>Photo :</label>
                                        </td>
                                        <td>
                                            <input type="file" name="Photos">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <label>Catégorie :</label>
                                        </td>
                                        <td>
                                            <select name="Categorie" id="Categorie">
                                            <option value="Object">Objet
                                            </option>
                                            <option value="Service">Service
                                            </option>
                                            </select>
                                        </td>
                                    </tr>
                                        <td align="right">
                                            <label>Description :</label>
                                        </td>
                                        <td>
                                            <textarea type="text" name="Description" placeholder="Description" row="5" cols="20">
                                            </textarea>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td align="right">
                                            <label>Pays :</label>
                                        </td>
                                        <td>
                                            <input type="text" name="Pays" placeholder="Country...">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <label>Région :</label>
                                        </td>
                                        <td>
                                            <input type="text" name="Region" placeholder="Region...">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <label>Ville :</label>
                                        </td>
                                        <td>
                                            <input type="text" name="Commune_Ville" placeholder="City...">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <label>Quartier :</label>
                                        </td>
                                        <td>
                                            <input type="text" name="Quartier" placeholder="Neighborhood...">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <label>Rue :</label>
                                        </td>
                                        <td>
                                            <input type="text" name="Rue" placeholder="Street...">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                        <br>
                                            <input type="submit" name="formajoutobjet" value="Ajouter !">
                                        </td>
                                    </tr>
                                    
                                </table>
                            </form>

                        </div>

                            </article>

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
