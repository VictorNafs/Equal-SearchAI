<?php
// Démarrage ou reprise de la session
session_start();

// Destruction de toutes les informations de session
session_destroy();

// Redirection vers la page d'accueil
header("Location: index.php");
exit;
