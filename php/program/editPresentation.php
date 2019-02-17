<?php

include('../_modules.php');

$program_id = $_POST['program_id'];
$id = $_POST['id'];
$name = clean_entries($_POST['name']);

if ($program_id != null && $id != null && $name != null){

    $file = DATA_PATH . 'program.json';

    $presentation = array(
        'name' => $name
    );

    $find_program = array('id' => $program_id);
    $find_presentation = array('id' => $id);

    if(update_column(
        array(
            'file' => $file,
            'table' => 'programs',
            'record_id' => $find_program,
            'column' => 'presentation_files',
            'column_id' => $find_presentation,
            'record' => $presentation
        )
    ) == true){
        echo "success";
    }else{
        echo "Failed!";
    }

} else {
    if ($program_id == null){
        echo "Missing program id";
    }elseif($name == null){
        echo "Missing name";
    }elseif($id == null){
        echo "Missing id";
    }else{
        echo "something strange happened";
    }
}

