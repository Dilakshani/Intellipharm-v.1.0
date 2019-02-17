<?php

include('../_modules.php');

$id = $_POST['id'];
$short_name = clean_entries($_POST['short_name']);
$name = clean_entries($_POST['name']);
$url = $_POST['url'];

if ($id != null && $short_name != null && $name != null){

    $file = DATA_PATH.'organisations.json';

    $organisation = array(
        'id' => $id,
        'short_name' => $short_name,
        'name' => $name,
        'url' => $url
    );

    $find = array('id' => $id);

    if(update_record(
        array(
            'file' => $file,
            'table' => 'organisations',
            'record_id' => $find,
            'record' => $organisation
        )
    ) == true){
        sort_records_alphabetically(
            array(
                'file' => $file,
                'table' => 'organisations',
                'column' => 'name'
            )
        );
        echo "success";
    }else{
        echo "Failed!";
    }

}else{
    if ($short_name == null){
        echo "Missing short name";
    }elseif($name == null){
        echo "Missing name";
    }elseif($id == null){
        echo "Missing id";
    }else{
        echo "something strange happened";
    }
}

?>
