<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>formulaire - Upload</title>
	<!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- Compiled and minified CSS -->
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
</head>
<body>
<div class="container">
<div class="card">
<div class="container">
	<!-- MATERIALIZE_FORM -->
	<form name="name_form" method="post" action="../src/debug_upload.php" enctype="multipart/form-data">
		<h4>Choisissez un ou des fichiers à upload</h4>
		<!-- FILE -->
		<div class="file-field input-field">
			<div class="btn">
				<span>File</span>
				<input name="fichier" type="file" multiple>
			</div>
			<div class="file-path-wrapper">
				<input class="file-path validate" type="text" placeholder="Upload one or more files">
			</div>
		</div>

<!-- FILE -->
		<div class="file-field input-field">
			<div class="btn">
				<span>File</span>
				<input name="fichier_1" type="file" multiple>
			</div>
			<div class="file-path-wrapper">
				<input class="file-path validate" type="text" placeholder="Upload one or more files">
			</div>
		</div>

		<!-- SUBMIT -->
		<button class="btn waves-effect waves-light" type="submit" >Submit
		    <i class="material-icons right">send</i>
		</button>

	</form>

</div>
</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
</body>
</html>