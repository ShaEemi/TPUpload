<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Upload_files</title>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
</head>
<body>

<div class="card">
    <div class="container">
        <h1>Upload image</h1>

        <h2>Afficher les carractéristiques du fichiers uploadé</h2>

        <p><?php
            //tableau des fichiers ou du fichiers
            $fichiers = $_FILES;

            require 'upload_files.php';
            $retour = upload_files($fichiers);
            var_dump($retour);
            ?></p>

        <a class="waves-effect waves-light btn" href="../test/formulaire.php">Retour</a>

        <p style="padding-bottom:10px"></p>


    </div>

</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
</body>
</html>