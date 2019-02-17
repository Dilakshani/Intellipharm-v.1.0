<?php

include('../_modules.php');

$program_id = $_POST['program_id'];
@$url = $_POST['url'];
@$name = $_POST['name'];



function check_if_name_exists($program_id, $name) {

    $programs = dump_table(
        array(
            'file' => DATA_PATH . 'program.json',
            'table' => 'programs'
        )
    );

    foreach ($programs as $program) {
        if ($program['id'] == $program_id) {
            foreach ($program['presentation_files'] as $presentation) {
                if ($presentation['name'] == $name) {
                    die ('Presentation name already exists');
                }
            }
        }
    }
}

if ($program_id != null){

    //If a name has been entered check if it already exists.
    if ($name != null) {
        check_if_name_exists($program_id, $name);
    }

    if (!isset($url)){
        $file_types = array("pdf", "ppt", "pptx");
        $File_Uploader = new File_Uploader($file_types,'FILE',$program_id.'/');
        if($File_Uploader->upload('file') == true){
            $file = $File_Uploader->get_path();
            $file_size = file_size_convert($File_Uploader->get_file_size());
            $type = $File_Uploader->get_file_extension();
        }else{
            echo "Failed!";
        }
    } else {
        $file = $url;
        $file_size = curl_get_file_size($url);
        $type = pathinfo($file, PATHINFO_EXTENSION);
    }

    if ($name == null){
        $name = basename($file);
    }

    $id = gen_id(array('id' => $name),true,true);

    $presentation = array(
        'id' => $id,
        'name' => $name,
        'file' => $file,
        'type' => $type,
        'size' => $file_size
    );

    $record = array('id' => $program_id);

    if (insert_column_record(
        array(
            'file' => DATA_PATH . 'program.json',
            'table' => 'programs',
            'column' => 'presentation_files',
            'record_id' => $record,
            'record' => $presentation
        )
    ) == true){
        sort_column_records_alphabetically(
            array(
                'file' => DATA_PATH . 'program.json',
                'table' => 'programs',
                'record_id' => $record,
                'column' => 'presentation_files',
                'key' => 'name'
            )
        );
        echo "Success!";
    }else{
        echo "Error occured whilst entering in data!";
    }

}else{
    echo "Missing program id<br />Upload failed!";
}
