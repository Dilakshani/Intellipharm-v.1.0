<?php

include('../_modules.php');

$program_id = $_POST['program_id'];

if ($program_id != null){
    $file_types = array("jpg", "png", "jpeg", "gif");
    $File_Uploader = new File_Uploader($file_types,'IMAGE',$program_id.'/');
    if($File_Uploader->upload('file') == true){

        $name = $_POST['name'];
        $file = $File_Uploader->get_path();

        if ($name == null){
            $name = basename($file);
        }

        $id = gen_id(array('id' => $name),true,false);

        $presentation = array(
            'id' => $id,
            'name' => $name,
            'file' => $file
        );

        $record = array('id' => $program_id);

        if (insert_column_record(
            array(
                'file' => DATA_PATH . 'program.json',
                'table' => 'programs',
                'column' => 'maps',
                'record_id' => $record,
                'record' => $presentation
            )) == true){
            echo "Success!";
        }else{
            echo "Error occured whilst entering in data!";
        }

    }else{
        echo "Failed!";
    }
}else{
    echo "Missing program id<br />Upload failed!";
}

?>
