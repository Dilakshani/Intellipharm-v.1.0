<?php

include('../_modules.php');

$id = $_POST['id'];
$tzname = clean_entries($_POST['tzname']);
$tzid = clean_entries($_POST['tzid']);
$tzoffset = clean_entries($_POST['tzoffset']);
$tzcode = clean_entries($_POST['tzcode']);

if ($id != null && $tzname != null && $tzid != null && $tzoffset != null && $tzcode != null){

    $file = DATA_PATH.'timezones.json';

    $timezone = array(
        'id' => $id,
        'name' => $tzname,
        'tzid' => $tzid,
        'tzoffset' => $tzoffset,
        'tzcode' => $tzcode
    );

    $find = array('id' => $id);

    if(update_record(
        array(
            'file' => $file,
            'table' => 'timezones',
            'record_id' => $find,
            'record' => $timezone
        )
    ) == true){
        echo "success";
    }else{
        echo "Failed";
    }

}else{
    echo "Missing program name!";
}

?>
