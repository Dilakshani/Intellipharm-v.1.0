<?php

include('../_modules.php');

@$short_name = clean_entries($_POST['short_name']);
$name = clean_entries($_POST['name']);
@$url = $_POST['url'];

if ($short_name == ''){
    $short_name = $name;
}

if ($short_name != null && $name != null){

    $file = DATA_PATH.'organisations.json';

    $id_generation = array(
        'short_name' => $short_name,
        'name' => $name,
        'url' => $url
    );

    $id = gen_id($id_generation);

    $find_duplicate = array('id' => $id);
    if (find_record(
        array(
            'file' => $file,
            'table' => 'organisations',
            'record_id' => $find_duplicate
        )
    ) == FALSE){
        $organisation = array(
            'id' => $id,
            'short_name' => $short_name,
            'name' => $name,
            'url' => $url
        );

        if(insert_table_record(
            array(
                'file' => $file,
                'table' => 'organisations',
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
        echo "Organisation already Exists.";
    }

}else{
    if ($short_name == null){
        echo "Missing short name";
    }elseif($name == null){
        echo "Missing name";
    }else{
        echo "something strange happened";
    }
}

?>
