<?php

include('../_modules.php');

$id = $_POST['id'];

if ($id != null){

    $file = DATA_PATH.'organisations.json';
    $find = array('id' => $id);
    if(delete_record(
        array(
            'file' => $file,
            'table' => 'organisations',
            'record_id' => $find
        )
    ) == true){
        echo "success";
    }else{
        echo "Failed";
    }
}else{
    echo "Missing ID!";
}

?>
