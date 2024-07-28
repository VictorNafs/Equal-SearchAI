<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dialogue avec l'IA pour Contribution</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #chatBox {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            height: 300px;
            overflow-y: scroll;
        }
        #userInput, #userImage {
            width: calc(100% - 90px);
            padding: 10px;
            margin-bottom: 10px;
        }
        button {
            width: 80px;
            padding: 10px;
        }
    </style>
</head>
<body>

<a href="logout.php">Déconnexion</a> | <a href="mes_propositions.php">Mes ajouts</a>    <a href="profil.php">Mon Profil</a>

<div id="chatContainer">
    <div id="chatBox"></div>
    <form id="userForm" enctype="multipart/form-data">
        <input type="text" id="userInput" name="message" placeholder="Décrivez votre proposition ici...">
        <input type="file" id="userImage" name="image" style="display: none;">
        <button type="submit">Envoyer</button>
    </form>
</div>

<script>
$(document).ready(function() {
    $("#userForm").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "ajout.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $("#chatBox").append("<div>" + response + "</div>");
                if (response.includes("Confirmez-vous ces informations ?")) {
                    $("#userImage").show();
                } else {
                    $("#userImage").hide();
                }
                $("#userInput").val("");
                $("#userImage").val("");
                $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);
            },
            error: function(xhr, status, error) {
                console.log("Erreur AJAX : " + status + " - " + error);
                console.log(xhr.responseText);
                $("#chatBox").append("<div>Erreur lors de l'envoi de la demande.</div>");
            }
        });
    });
});
</script>

</body>
</html>
