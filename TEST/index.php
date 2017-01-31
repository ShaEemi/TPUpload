<html>
<head>
    <title>Upload de fichiers test</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"/>
</head>
<body>
<div class="container">
	<h1>Upload de fichier</h1>
	<hr/>
	<?php if(isset($_GET['error']) && $_GET['error'] === 'no_file_selected'){ ?>
		<p class="text-danger">Veuillez choisir un fichier à uploader</p>
	<?php } ?>
	<div id="edit_photos_bloc" class="">

		<button data-backdrop="static" data-toggle="modal" data-target=".upload_photos_modal" class="img_add_photon btn btn-primary btn-lg">Commencer</button>
	</div>
</div>






<script type="text/javascript" src="assets/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/main.js"></script>

<!--
	*
	*  Modal pour "Uploader des photos de bateau"
	*
-->
<div class="modal fade upload_photos_modal" tabindex="-1" role="dialog">
	<form class="upload_form" method="post" action="formTesting.php" enctype="multipart/form-data">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close panel-success" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Upload des fichiers</h4>
	      </div>
	      <div class="modal-body">
	      	<div class="bloc_file_uploader">
	      		<div>
	      			<p>Générer les diférents formats pour les images?</p>
	      			<label class="radio-inline"><input type="radio" name="format" id="inlineRadio1" value="yes" checked> Oui</label>
					<label class="radio-inline"><input type="radio" name="format" id="inlineRadio2" value="no"> Non</label>
	      		</div>
	      		<br/>

				<button class="btn btn-success active btn_add_files" type="button">+ Ajouter un fichier</button>
			</div>		
	      </div>
	      <div class="modal-footer">
	        <button type="submit" name="upload_photos" class="btn btn-primary">Upload</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
  </form>
</div><!-- /.modal -->


</body>
</html>