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
    if(isset($_POST['formajoutannonce']) AND !empty($_POST['Titre_annonce']))
    {
        $id_annonce = $_POST['id_annonce'];
        $Titre_annonce = htmlspecialchars($_POST['Titre_annonce']);
        $Categorie_annonce = $_POST['Categorie_annonce'];
        $Description_annonce = htmlspecialchars($_POST['Description_annonce']);
        $extensionUpload = ($_FILES['Photos_annonce']['name']);
        $Pays_annonce = htmlspecialchars($_POST['Pays_annonce']);
        $Region_annonce = htmlspecialchars($_POST['Region_annonce']);
        $Departement_annonce = htmlspecialchars($_POST['Departement_annonce']);
        $Commune_Ville_annonce = htmlspecialchars($_POST['Commune_Ville_annonce']);
        $Quartier_annonce = htmlspecialchars($_POST['Quartier_annonce']);
        $Rue_annonce = htmlspecialchars($_POST['Rue_annonce']);
        $id_membre = $_SESSION['id'];

        $MaxidObjet = 1;
            $MaxidObjetRequet = $bdd->query('SELECT MAX(id_annonce) max FROM annonce');
        $MaxidObjetRequet->execute(array($_SESSION['id']));
        error_log("Victor dit");
        while($donneesMax = $MaxidObjetRequet->fetch())
        {
        $MaxidObjet = $MaxidObjet + $donneesMax['max'];
        error_log($donneesMax['max']);
        error_log($MaxidObjet);
        }
        $extensionUpload = (strtolower(substr(strrchr($_FILES['Photos_annonce']['name'], '.'), 1)));
        $filename = $_SESSION['id'] . "." . $MaxidObjet . "." . $extensionUpload;
        error_log($filename);


        if(!empty($_POST['Titre_annonce']) AND !empty($_POST['Categorie_annonce']) AND !empty($_POST['Description_annonce']))
        {
            $insertObjet = $bdd->prepare('INSERT INTO annonce(Titre_annonce, Photos_annonce, Categorie_annonce, Description_annonce, Pays_annonce, Region_annonce, Departement_annonce, Commune_Ville_annonce, Quartier_annonce, Rue_annonce, id_membre) VALUES(:Titre_annonce, :Photos_annonce, :Categorie_annonce, :Description_annonce, :Pays_annonce, :Region_annonce, :Departement_annonce, :Commune_Ville_annonce, :Quartier_annonce, :Rue_annonce, :id_membre)');
            $insertObjet->execute(array(
                'Titre_annonce' => $Titre_annonce,
                'Photos_annonce' => $filename,
                'Categorie_annonce' => $Categorie_annonce,
                'Description_annonce' => $Description_annonce,
                'Pays_annonce' => $Pays_annonce,
                'Region_annonce' => $Region_annonce,
                'Departement_annonce' => $Departement_annonce,
                'Commune_Ville_annonce' => $Commune_Ville_annonce,
                'Quartier_annonce' => $Quartier_annonce,
                'Rue_annonce' => $Rue_annonce,
                'id_membre' => $_SESSION['id']
                ));
            header('Location: Mesannonces.php?id=' . $_SESSION['id']);
            
        }

        

        if(isset($_FILES['Photos_annonce']) AND !empty($_FILES['Photos_annonce']['name']))
            {
                $tailleMax = 2097152;
                $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');

                if($_FILES['Photos_annonce']['size'] <= $tailleMax)
                {
                    $extensionUpload = (strtolower(substr(strrchr($_FILES['Photos_annonce']['name'], '.'), 1)));

                    if(in_array($extensionUpload, $extensionsValides))
                    {
                        $chemin = "Annonce/P_Annonce" . $_SESSION['id'] . "." . $MaxidObjet . "." . $extensionUpload;
                        $resultat = move_uploaded_file($_FILES['Photos_annonce']['tmp_name'], $chemin);
                        if($resultat)
                        {

                        }
                    }
                    else
                    {
                        $msg = "La photo de votre annonce doit être au format jpg, jpeg, gif ou png";
                    }
                }
                else
                {
                    $msg = "La photo de votre annonce ne doit pas dépasser 2Mo";
                }
            }

        
    }
        



?>
<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" href="ajoutannonce.css">

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
                            <article class="contenu-article" align="center">





                            <h1 style="color: white;">Je publie une annonce :</h1>







                            <form method="POST" action="" enctype="multipart/form-data">
                                <table>
                                    <tr>
                                        <td align="right">
                                            <label>Nom de votre annonce :</label>
                                        </td>
                                        <td>
                                            <input type="text" name="Titre_annonce" placeholder="Offer..." value="">*
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <label>Photo :</label>
                                        </td>
                                        <td>
                                            <input type="file" name="Photos_annonce">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <label>Catégorie :</label>
                                        </td>
                                        <td>
                                            <select name="Categorie_annonce" id="Categorie_annonce">
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
                                            <textarea type="text" name="Description_annonce" placeholder="Description" row="5" cols="20">
                                            </textarea>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td align="right">
                                            <label>Pays :</label>
                                        </td>
                                        <td>
                                            <input type="text" name="Pays_annonce" placeholder="Country...">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <label>Région :</label>
                                        </td>
                                        <td>
                                            <input type="text" name="Region_annonce" placeholder="Region...">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <label>Ville :</label>
                                        </td>
                                        <td>
                                            <input type="text" name="Commune_Ville_annonce" placeholder="City...">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <label>Quartier :</label>
                                        </td>
                                        <td>
                                            <input type="text" name="Quartier_annonce" placeholder="Neighborhood...">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <label>Rue :</label>
                                        </td>
                                        <td>
                                            <input type="text" name="Rue_annonce" placeholder="Street...">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                        <br>
                                            <input type="submit" name="formajoutannonce" value="Ajouter !">
                                        </td>
                                    </tr>
                                    
                                </table>
                            </form>


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
