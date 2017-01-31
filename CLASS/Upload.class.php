<?php

class Upload {
    /**
     * @var string path for uploads files
     */
    private $path;
    /**
     * @var bool format define the format for images. Default : False
     */
    private $format;
    /**
     * @var int size image width
     */
    private $max_width = 1280;
    /**
     * @var int size image height
     */
    private $max_height = 960;
    /**
     * @var array result of upload
     */
    private $uploaded_paths = array();

    /**
     * Upload constructor.
     * @
     */
    public function __construct($path = 'uploaded_files/', $format = false)
    {
        // set path
        $this->setPath($path);

        // set format
        $this->setFormat($format);
    }

    /**
     * @param int $max_width
     */
    public function setMaxWidth($max_width)
    {
        if (! is_int($max_width)) throw new InvalidArgumentException("Erreur : max_width NaN");
        $this->max_width = $max_width;
    }

    /**
     * @param int $max_height
     */
    public function setMaxHeight($max_height)
    {
        if (! is_int($max_height)) throw new InvalidArgumentException("Erreur : max_height NaN");
        $this->max_height = $max_height;
    }

    
    public function getPaths()
    {
        return $this->uploaded_paths;
    }

    private function is_image($file_type){
        return in_array($file_type, array('image/png', 'image/jpeg', 'image/gif'));
    }

    public function cleanFileArray($files){

        $arrayFiles = array();
        $fileNb = count($files['name']);

        for ($i = 0; $i < $fileNb; $i++) {
            if (!empty($files['name'][$i])) {
                $arrayFiles[] = array(
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i],
                );
            }
        }

        return $arrayFiles;
    }

    /**
     * @param $files
     * @return array | Exception error
     */
    public function set_upload($files) {

        if (empty($files) && is_array($files))
            throw new InvalidArgumentException("erreur : files param is not valid");

        foreach ($files as $key => $file) {
            if (file_exists($file['tmp_name']) && $file['name'] != "") {
                $is_image = $this->is_image($file['type']);

                switch ($is_image) {
                    case true:
                        array_push($this->uploaded_paths, $this->upload_img($file));
                        break;

                    case false:
                        array_push($this->uploaded_paths, $this->upload_file($file));
                        break;

                    default:
                        throw new InvalidArgumentException("erreur : format of file is not valid");

                }
            }
        }
        return $this->uploaded_paths;
    }

    /**
     * Verrif if path is correct and is possible create it
     * @param $path
     * @return bool
     */
    public static function verrif_path($path) {
        if (file_exists($path)){
            return chmod($path, 0777);
        } else {
            mkdir($path, 0777, true);
            return chmod($path, 0777);
        }
    }

    /**
     * @param $path string return bool|erreur
     */
    public function setPath($path) {
        // set path
        if (!empty($path) && is_string($path))
            $this->path = $path;
        else
            throw new InvalidArgumentException("erreur : path is not a string");
    }

    /**
     * @param $format bool return bool|erreur
     */
    public function setFormat($format) {
        if (is_bool($format))
            $this->format = $format;
        else
            throw new InvalidArgumentException("erreur : format is not a boolean");
    }

    /**
     * do the upload
     * @param $file
     * @return bool|string
    */
    private function upload_file($file) {

        if (!self::verrif_path($this->path)) throw new InvalidArgumentException("erreur : destination path isn't valid");
        // get extention
        $ext = strtolower(substr(strrchr($file['name'], '.'), 1));

        // création de l'url
        $fileName = md5(uniqid(mt_rand())) . "." . $ext;
        //die($fileName);
        $url = $this->path . $fileName;
        // permission
        chmod($this->path, 0777);
        // déplacement
        if (move_uploaded_file($file['tmp_name'], $url)) {
            return $this->path . $fileName;
        }
        return false;

    }

    /**
     * do the upload
     * @param $file
     * @return string
     */
    private function upload_img($file) {
        if (!self::verrif_path($this->path)) throw new InvalidArgumentException("erreur : destination path is not valid");
        $ext = strtolower(substr(strrchr($file['name'], '.'), 1));

        // création de l'url du déplacement
        $fileName = md5(uniqid(mt_rand())) . "." . $ext;
        if ($this->format) {
            $url = [
                [
                    "url" => $this->path . "small/" .$fileName,
                    "scale" => 0.25,
                    "path"  => $this->path . "small/"
                ],
                [
                    "url" => $this->path . "medium/" .$fileName,
                    "scale" => 0.65,
                    "path"  => $this->path . "medium/"
                ],
                [
                    "url" => $this->path . "large/" .$fileName,
                    "scale" => 1,
                    "path"  => $this->path . "large/"
                ],

            ];
        } else {
            $url[] = [
                "url" => $this->path . $fileName,
                "scale" => 1,
                "path"  => $this->path
            ];
        }

        // copy to destination && redimenssion
        for ($i = 0; $i < count($url); $i ++) {
            // check || create path &&  sub-directory for format option
            if (! self::verrif_path($url[$i]["path"])) throw new InvalidArgumentException("permissions denied for path :" . $url[$i]["path"]);

            // copy file to destination
            if (!copy($file['tmp_name'], $url[$i]["url"])) throw new InvalidArgumentException("cannot not copy file:" . $url[$i]["url"]);

            // format img
            if (! $this->imgFormat($url[$i]['url'], $url[$i]['scale'] )) throw new InvalidArgumentException("cannot format img : " . $url[$i]['url']);
        }

        //destroy tmp_name file
        unlink($file['tmp_name']);

        $return = $this->format ? array(
            $this->path . 'small/' . $fileName,
            $this->path . 'medium/' .  $fileName,
            $this->path . 'large/' .  $fileName,
        ) : $this->path . $fileName ;
        return $return;
    }

    public function imgFormat($url, $scale = 1) {
        // appel de la fonction de redimentionnement
        $ext = pathinfo($url, PATHINFO_EXTENSION);
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
                throw new InvalidArgumentException("Format img invalid");
                break;
        }
        // redimenssion
        if ($image) {
            $width = imagesx($image);
            $height = imagesy($image);

            $new_width = $this->max_width * $scale;
            $new_height = $this->max_height * $scale;

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
            return $url;
        }
        throw new InvalidArgumentException("cannot format img");
    }


    public function __destruct()
    {
        //var_dump($this);
    }
}