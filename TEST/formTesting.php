<?php
if(isset($_FILES['files'])){
	$format = $_POST['format'] === 'yes' ? true : false;
	require_once'../CLASS/Upload.class.php';
	$files = new Upload;
	$arrayFile = $files->cleanFileArray($_FILES['files']);
	$files->setFormat($format);
	$uploaded_files = $files->set_upload($arrayFile) ? $files->getPaths() : array();
} else {
	header('location: index.php?error=no_file_selected');
}
?>

<html>
<head>
    <title>Upload de fichiers test</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"/>
</head>
<body>
<div class="container">
	<h1>Fichiers uploadés</h1>
	<hr/>
	<?php
		foreach ($uploaded_files as $key => $value) {
			if(!empty($uploaded_files)){
				if(is_array($value)){
					foreach ($value as $k => $v) { ?>
						<p><a href="<?php echo $v ?>"><?php echo $v ?></a></p>
					<?php } 
				} else { ?>
					<p><a href="<?php echo $value ?>"><?php echo $value ?></a></p>
				<?php }
			}
		}
	?>
	<hr/>
	<a href="index.php" class="btn btn-danger">Retour à la page upload</a>
	
</div>


</body>
</html>


