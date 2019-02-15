<?php

include('../_modules.php');

$file = $_POST['id'];
$program_id = $_POST['program_id'];

if ($file != null && $program_id != null){

    $database_file = DATA_PATH.'program.json';

    $find_file = array('file' => $file);
    $record = array('id' => $program_id);

    if(delete_column(array(
        'file' => $database_file,
        'table' => 'programs',
        'column' => 'maps',
        'record_id' => $record,
        'column_id' => $find_file
    )) == true){

        $open_file = fopen(ROOT.$file, 'w');
        fclose($open_file);
        unlink(ROOT.$file);
        echo "success";

    }else{
        echo "Failed";
    }

} else {
    if ($file == null){
        echo "Missing file name!";
    }
    if ($program_id == null){
        echo "Missing program id!";
    }
}
