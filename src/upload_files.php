<?php

/*	upload_files v.1
*  **********************************************
*	(PHP > 5)		
*	upload_files - Upload différents types de fichiers
* ***********************************************
*	Description
*	
*	bool/array upload_files( array/string $fichiers [, sring $repertoire = false [, bool $formats = NULL 
*	]])
*	Tente d'upload des fichiers :
*	img => png , gif , jpg 
*	zip => zip
*	pdf => pdf
*	***********************************************
*	Liste des paramètres
*	
*	$fichiers
*	Le ou les fichier(s) à upload sous forme de array.
*
*	$repertoire
*	le repertoire par défault est "fichiers/", possibilité d'en un définir 
*	à la volé un repertoire sous forme de string
*
*	$formats (s'applique aux fichiers de type image)
*	le format par défaut est NULL, il applique un format par défaut. 
*	Si la valeurs est true, la fonction crée 3 formats de diférentes tailles
*	rangés dans 3 repertoire différents :
* 	$repertoire/  + 'small/'
* 	$repertoire/  + 'medium/'
* 	$repertoire / + 'large/'
*	***********************************************
*	Valeurs de retour
*
*	Cette fonction renvoie un tableau en cas de succès ou FALSE en cas d'erreur.
*	***********************************************
*	Constantes
*
*	Possibilité de définir des constantes (parametres prioritaire)
*	exemples :
*	define("IMG_REPERTOIRE", "images/");
*	define("PDF_REPERTOIRE", "pdf/");
*	define("ZIP_REPERTOIRE", "zip/");
*	define("IMG_FORMAT", true); // ou false
*	define("IMG_FORMAT_WIDH_SMALL", 400); // default = 500
*	define("IMG_FORMAT_HEIGTH_SMALL", 200); // default = 375
*
*/


// fonction privé upload_pdf gere les pdf
function upload_pdf($fichier, $ext, $repertoire)
{
    // test/création du repertoire
    if (!$repertoire) {
        // repertoire par défault
        $repertoire = 'fichiers/';
        if (defined('PDF_REPERTOIRE')) {
            // repertoire initalisé avec la constante
            $repertoire = PDF_REPERTOIRE;
        }
    }
    if (!file_exists($repertoire)) {
        mkdir($repertoire, 0777);
    }
    // création de l'url
    $url = $repertoire . md5(uniqid(mt_rand())) . "." . $ext;
    // permission
    chmod($repertoire, 0777);
    // déplacement
    if (move_uploaded_file($fichier['tmp_name'], $url)) {
        return $url = [$url];
    }
    return false;
}

// fonction privé upload_zip gere les zip
function upload_zip($fichier, $ext, $repertoire)
{
    // test/création du repertoire
    if (!$repertoire) {
        // repertoire par défault
        $repertoire = 'fichiers/';
        if (defined('ZIP_REPERTOIRE')) {
            // repertoire initalisé avec la constante
            $repertoire = ZIP_REPERTOIRE;
        }
    }
    if (!file_exists($repertoire)) {
        mkdir($repertoire, 0777, true);
    }
    // création de l'url
    $url = $repertoire . md5(uniqid(mt_rand())) . "." . $ext;
    // permission
    chmod($repertoire, 0777);
    // déplacement
    if (move_uploaded_file($fichier['tmp_name'], $url)) {
        return $url = [$url];
    }
    return false;
}

// fonction privé defineFormat gere l'option de format d'images
function optionFormat($urls, $ext)
{
    //valeur par défault
    $new_width = defined('IMG_FORMAT_WIDH_SMALL') ? IMG_FORMAT_WIDH_SMALL : 500;
    $new_height = defined('IMG_FORMAT_HEIGTH_SMALL') ? IMG_FORMAT_HEIGTH_SMALL : 375;
    foreach ($urls as $key => $url) {
        // appel de la fonction de redimentionnement
        switch ($ext) {
            case "jpg":
                $image = imagecreatefromjpeg($url); //jpeg file
                $locatImage = "imageJPEG";
                break;
            case "gif":
                $image = imagecreatefromgif($url); //gif file
                $locatImage = "imagegif";
                break;
            case "png":
                $image = imagecreatefrompng($url); //png file
                $locatImage = "imagepng";
                break;
            default:
                $image = false;
                break;
        }
        // redimenssion
        if ($image) {
            $width = imagesx($image);
            $height = imagesy($image);
            if ($width > $height) {
                //format horizontal
                $new_width = $new_width * $key;
                $new_height = ($new_width * $height) / $width;
                //$new_width = $new_width * 2;
            } else {
                //format vertical
                $new_height = $new_height * $key;
                $new_width = ($new_height * $width) / $height;
                //$new_height = $new_height * 2;
            }
            $thumb = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            $locatImage($thumb, $url);
            chmod($url, 0777);
            imagedestroy($image);
        }
    }
    return true;
}

// fonction privé upload_img gere les images
function upload_img($fichier, $ext, $repertoire, $formats)
{
    // Connaitre les dimensions de l'image
    $sizes = getimagesize($fichier['tmp_name']);
    // Choix du repertoire
    if (!$repertoire) {
        // repertoire par défault
        $repertoire = 'fichiers/';
        if (defined('IMG_REPERTOIRE')) {
            // repertoire initalisé avec la constante
            $repertoire = IMG_REPERTOIRE;
        }
    }
    // Test si le repertoire existe
    if (!file_exists($repertoire)) {
        // création du repertoire
        mkdir($repertoire, 0777, true);
    }
    // test parametre format true 3 format false par default
    // vérification d'une constante
    if (defined('IMG_FORMAT') && $formats === NULL) {
        // format initalisé avec la constante
        IMG_FORMAT ? $formats = 3 : $formats = 1;
    } elseif ($formats) {
        // Priorité parametre de la fonction
        $formats = 3;
    } else {
        // rien défini ou false = défault
        $formats = 1;
    }
    // ( nouveaux )repertoires pour l'option format (small, medium, large);
    if ($formats == 3) {
        $repertoireSizes = ['small/', 'medium/', 'large/'];
    }
    // création de l'url du déplacement
    $url = $repertoire . md5(uniqid(mt_rand())) . "." . $ext;
    /**
     *  FORMAR AVEC OPTION
     */
    // sous repertoire pour l'option formats
    if (isset($repertoireSizes) && $formats >= 3) {
        $y = 0;
        $len = count($repertoireSizes);
        foreach ($repertoireSizes as $key => $repertoireSize) {
            // url par key
            $single_url = $repertoire . $repertoireSizes[$key] . md5(uniqid(mt_rand())) . "." . $ext;
            if ($y === 0) {
                //premiere itération
                $url .= "//" . $single_url . "//";
            } elseif ($y == $len - 1) {
                //derniere itération du tableau
                $url .= $single_url;
            } else {
                $url .= $single_url . "//";
            }
            $y++;
            // Test si le repertoire existe
            $rep = $repertoire . $repertoireSizes[$key];
            if (!file_exists($rep)) {
                // création du repertoire
                mkdir($rep, 0777);
            }
            // déplacement de l'image temporaire dans son repertoire
            copy($fichier['tmp_name'], $single_url);
        }
        // transforme en array
        $url = explode("//", $url);
        // suppression de la premiere ligne (du format par default)
        unset($url[0]);
        // destruction de l'image temporaire
        unlink($fichier['tmp_name']);
        // redimension des images
        $retour = optionFormat($url, $ext, $formats);
        if (!$retour) {
            return false;
        }
        return $url;
        /////////////////////// END OPTION FORMATS
    }
    /**
     *  FORMAR PAR DEFAULT
     */
    // déplacement
    if (move_uploaded_file($fichier['tmp_name'], $url)) {
        // appel de la fonction de redimentionnement
        switch ($ext) {
            case "jpg":
                $image = imagecreatefromjpeg($url); //jpeg file
                $locatImage = "imageJPEG";
                break;
            case "gif":
                $image = imagecreatefromgif($url); //gif file
                $locatImage = "imagegif";
                break;
            case "png":
                $image = imagecreatefrompng($url); //png file
                $locatImage = "imagepng";
                break;
            default:
                $image = false;
                break;
        }
        // redimenssion
        if ($image) {
            $width = imagesx($image);
            $height = imagesy($image);
            //valeur par défault
            $new_width = 500;
            $new_height = 375;
            if ($width > $height) {
                //format horizontal
                $new_height = ($new_width * $height) / $width;
            } else {
                //format vertical
                $new_width = ($new_height * $width) / $height;
            }
            $thumb = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            $locatImage($thumb, $url);
            chmod($url, 0777);
            imagedestroy($image);
            $url = [$url];
            return $url;
        }
    }
}

// fonction public upload_files fonction appelé gere plusieurs formats
function upload_files($fichiers, $repertoire = false, $formats = NULL)
{
    //extensions acceptées
    $exts = array(
        "img" => array('jpg', 'gif', 'png'),
        "zip" => array('zip'),
        "pdf" => array('pdf'),
    );
    
    // initialisation du rapport
    $rapport = [];
    //traitement par fichier
    foreach ($fichiers as $key => $fichier) {
        // vérification de l'existence du fichier
        if (file_exists($fichier['tmp_name']) && !$fichier['name'] == '') {
            // $ext_upload récupere l'extension du fichier
            $ext_upload = strtolower(substr(strrchr($fichier['name'], '.'), 1));
            // vérification si extension accepté
            $test = false;
            foreach ($exts as $ext) { if (in_array($ext_upload, $ext)) { $test = true; };}
            if (!$test) { return false; };
           //traitement fichiers par rapport à l'extension 
            foreach ($exts as $key => $ext) {
                if (in_array($ext_upload, $ext)) {
                    //recherche de la catégorie de l'extension
                    $executeFunction = "upload_" . $key;
                    //appel de la bonne function
                    $retour = $executeFunction($fichier, $ext_upload, $repertoire, $formats);
                    if ($retour) {
                        $rapport = array_merge($retour, $rapport);
                    } else {
                        return false;
                    }
                    /////////////////// END
                } 
            }
        }
    }
    return $rapport;
}