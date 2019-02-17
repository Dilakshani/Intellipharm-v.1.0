<?php

include('../_modules.php');

$program_id = $_POST['program_id'];
$id = $_POST['id'];
$name = clean_entries($_POST['name']);

if ($program_id != null && $id != null && $name != null){

    $file = DATA_PATH . 'program.json';

    $transcript = array(
        'name' => $name
    );

    $find_program = array('id' => $program_id);
    $find_trans = array('id' => $id);

    if(update_column(
        array(
            'file' => $file,
            'table' => 'programs',
            'record_id' => $find_program,
            'column' => 'transcripts',
            'column_id' => $find_trans,
            'record' => $transcript
        )
    ) == true){
        echo "success";
    }else{
        echo "Failed!";
    }

}else{
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


