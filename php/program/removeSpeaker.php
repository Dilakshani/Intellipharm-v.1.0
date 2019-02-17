<?php

include('../_modules.php');

$id = $_POST['id'];
$program_id = $_POST['program_id'];

if ($id != null){

    $file = DATA_PATH.'program.json';
    $record_id = array('id' => $program_id);
    $column_id = array('id' => $id);

    if(delete_column(array(
        'file' => $file,
        'table' => 'programs',
        'column' => 'speakers',
        'record_id' => $record_id,
        'column_id' => $column_id
    )) == true){
    echo "success";
    }else{
        echo "Failed";
    }
}else{
    echo "Missing ID!";
}

?>
