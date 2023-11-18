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
?>

<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" href="annoncesobjets.css">

                <title> Equal.E </title>

    </head>

                    <body>

                <div id="header">
                    

                        <header>

                    <div class="contenu">
                        
                        <img src="logo/Equal-mascot-mini.png">

                        <p> Version bêta </p>

                    </div>
                    
                        </header>

                        
                </div>

                
                   

                    

                <section>

<article>
                    <div id="section1">
                        <a href="rechercheos.php?id=<?php echo $_SESSION['id']; ?>">Offres disponibles</a>
                    </div>
                    
                    <div id="section2">
                        <a href="recherchea.php?id=<?php echo $_SESSION['id']; ?>">Ce que les gens cherchent ?</a>
                    </div>

</article>



<article>
 
 <br><br><br>   

<p style="text-align: center; color: green;">Vous êtes prié de supprimer les articles et annonces qui vous choquent merci.</p>


</article>
                       </section>


                
                   
                        <footer>
                    
                   
                        </footer>
               



                    </body>

</html>

<?php
}
?>
