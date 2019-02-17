<?php

/*

  @Author Clyde Smets | clyde@apnic.net

  @Desc

 */

class File_Uploader{

    public $SIZE_LIMIT;

    private $_EXTENSIONS;
    private $_TYPE;
    private $_TARGET_DIR;
    private $_TRAIL;
    private $_LOCATION;
    private $_OUTPUT_PATH;
    private $_SIZE;
    private $_EXTENSION;

    /*
        $ext = ARRAY;
        $type = 'IMAGE' || 'FILE' (DEFAULT)
     */
    public function __construct($ext,$type = 'FILE',$location = null){
        if(gettype($ext) != 'array'){
            if (gettype($ext) == 'string'){
                $this->_EXTENSIONS = array($ext);
            }else{
                die('INVALID VAR TYPE: $ext variable is not string or' .
                    ' array type');
            }
        }else{
            $this->_EXTENSIONS = $ext;
        }

        $type = strtoupper($type);
        if ($this->_checkIfTypeIsValid($type) == true){
            $this->_TYPE = $type;
        }else{
            die('INVALID TYPE: $type is not valid or recognised.' .
                ' DEFAULT is \'FILE\'');
        }

        //Set file size limit in MB
        $this->SIZE_LIMIT = 50;

        //Set file destination
        $this->_TRAIL = "../../";
        $this->_LOCATION = $location;
        $this->_TARGET_DIR = "uploads/";
    }

    public function upload($key){
        if($this->_TYPE == 'IMAGE'){
            return $this->_upload_image($key);
        }elseif($this->_TYPE == 'FILE'){
            return $this->_upload_file($key);
        }
    }

    public function get_path(){
        return $this->_OUTPUT_PATH;
    }

    public function get_file_size() {
        return $this->_SIZE;
    }

    public function get_file_extension() {
        return $this->_EXTENSION;
    }

    private function _checkIfTypeIsValid($type){
        if($type == 'IMAGE' || $type == 'FILE'){
            return true;
        }else{
            return false;
        }
    }

    private function _upload_image($key){
        if (!isset($_FILES[$key])) {
            exit("No image given");
        }
        $image_dir = $this->_TRAIL . $this->_TARGET_DIR . "images/" .
            $this->_LOCATION;
        $filename = basename($_FILES[$key]["name"]);
        $image_file = $image_dir . $filename;
        $errors_found = 0;
        $image_file_type = pathinfo($image_file, PATHINFO_EXTENSION);
        $this->_EXTENSION = $image_file_type;
        $image_size = $_FILES[$key]['size'];
        $this->_SIZE = $image_size;

        //Check if it's an actual image
        $check = getimagesize($_FILES[$key]["tmp_name"]);
        if ($check !== false){
            //it's an image
        }else{
            die("ERROR! File is not an image");
            $errors_found++;
        }

        //Check if file already exists
        if (file_exists($image_file) == true){
            echo "WARNING! File already exists";
            $errors_found++;
        }

        //Check image file size
        $mb = 1048576; // = 1 MB
        if ($image_size > ($this->SIZE_LIMIT * $mb)){
            die("ERROR! Image size is too large");
            $errors_found++;
        }

        //Check if type is accepted
        if ($this->_check_valid_ext($image_file_type) == false){
            die("ERROR! File type is not accepted");
            $errors_found++;
        }


        if($errors_found > 0){
            die(" Sorry there are reported errors found. Please try again.");
            return false;
        }else{
            if (file_exists($image_dir) == false){
                mkdir($image_dir,0777,true);
            }
            if (move_uploaded_file($_FILES[$key]["tmp_name"],
                $image_file) == true){
                $this->_OUTPUT_PATH = $this->_TARGET_DIR . "images/" .
                    $this->_LOCATION . $filename;
                return true;
            }else{
                die("ERROR! There was an error with uploading the image' .
                    ' file. Please try again!");
                return false;
            }
        }

    }

    private function _upload_file($key){
        if (!isset($_FILES[$key])) {
            exit("No file given");
        }
        $file_dir = $this->_TRAIL . $this->_TARGET_DIR . "files/" .
            $this->_LOCATION;
        $filename = basename($_FILES[$key]["name"]);
        $file = $file_dir . $filename;
        $errors_found = 0;
        $file_type = pathinfo($file, PATHINFO_EXTENSION);
        $this->_EXTENSION = $file_type;
        $file_size = $_FILES[$key]['size'];
        $this->_SIZE = $file_size;

        //Check if file already exists
        if (file_exists($file) == true){
            die("ERROR! File already exists");
            $errors_found++;
        }

        //check file size
        $mb = 1048576; // = 1 MB
        if ($file_size > ($this->SIZE_LIMIT * $mb)){
            die("ERROR! File size is too large");
            $errors_found++;
        }

        //Check if type is accepted
        if ($this->_check_valid_ext($file_type) == false){
            die("ERROR! File type is not accepted");
            $errors_found++;
        }

        if($errors_found > 0){
            die(" Sorry there are reported errors found. Please try again.");
            return false;
        }else{
            if (file_exists($file_dir) == false){
                mkdir($file_dir,0777,true);
            }
            if (move_uploaded_file($_FILES[$key]["tmp_name"], $file) == true){
                $this->_OUTPUT_PATH = $this->_TARGET_DIR . "files/" .
                    $this->_LOCATION . $filename;
                return true;
            }else{
                die("ERROR! There was an error with uploading the file.' .
                    ' Please try again!");
                return false;
            }
        }
    }

    private function _check_valid_ext($ext){
        $extension_ok = false;
        foreach($this->_EXTENSIONS as $e){
            if ($ext == $e){
                $extension_ok = true;
                break;
            }else{
                $extension_ok = false;
            }
        }
        return $extension_ok;
    }

}

?>
