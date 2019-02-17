<?php

include('../_modules.php');

$id = $_POST['id'];

if ($id != null){

    $file = DATA_PATH.'timezones.json';
    $find = array('id' => $id);

    if(delete_record(
        array(
            'file' => $file,
            'table' => 'timezones',
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
