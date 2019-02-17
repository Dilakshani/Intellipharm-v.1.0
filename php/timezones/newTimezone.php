<?php

include('../_modules.php');

$tzname = clean_entries($_POST['tzname']);
$tzid = clean_entries($_POST['tzid']);
$tzoffset = clean_entries($_POST['tzoffset']);
$tzcode = clean_entries($_POST['tzcode']);

if ($tzname != null && $tzid != null && $tzoffset != null && $tzcode != null){

    $file = DATA_PATH.'timezones.json';

    $id_generation = array(
        'name' => $tzname,
        'tzid' => $tzid,
        'tzoffset' => $tzoffset,
        'tzcode' => $tzcode
    );

    $id = gen_id($id_generation);

    $find_duplicate = array('id' => $id);

    if (find_record(
        array(
            'file' => $file,
            'table' => 'timezones',
            'record_id' => $find_duplicate
        )
    ) == FALSE) {

    $timezone = array(
        'id' => $id,
        'name' => $tzname,
        'tzid' => $tzid,
        'tzoffset' => $tzoffset,
        'tzcode' => $tzcode
    );

    if(insert_table_record(
        array(
            'file' => $file,
            'table' => 'timezones',
            'record' => $timezone
        )
    ) == true){
        echo "success";
    }else{
        echo "Failed!";
    }

    }else{
        echo "Organisation already Exists.";
    }

}else{
    echo "Missing program name!";
}

?>
