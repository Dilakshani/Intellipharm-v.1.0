<?php

include('../_modules.php');

$id = $_POST['id'];
$name = clean_entries($_POST['name']);
$timezone = clean_entries($_POST['timezone']);
$start_date = $_POST['start-date'];
$end_date = $_POST['end-date'];
$path = strip_tags($_POST['path']);

if ($id != null && $name != null && $timezone != null && $start_date != null 
    && $end_date != null) {

    $file = DATA_PATH.'program.json';

    $program = array(
        'id' => $id,
        'name' => $name,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'timezone' => $timezone,
        'path' => $path
    );

    $find = array('id' => $id);

    if(update_record(array(
        'file' => $file,
        'table' => 'programs',
        'record_id' => $find,
        'record' => $program
    )) == true){
        $Date_Ranger = new Date_Range_Generator($start_date, $end_date);
        $dates = $Date_Ranger->dates();
        $program_dates = array(
            'program' => $id,
            'dates' => $dates
        );
        $find_dates = array('program' => $id);
        if (update_record(array(
            'file' => DATA_PATH . 'program_dates.json',
            'table' => 'dates',
            'record_id' => $find_dates,
            'record' => $program_dates
        )) == true){
            echo "success";
        } else {
            echo "Program successfully update, but Dates failed to update.";    
        }
    }else{
        echo "Failed";
    }

}else{
    if ($name == null){
        echo "Missing program name!";
    }elseif($timezone == null){
        echo "Missing timezone!";
    }elseif($start_date == null){
        echo "Missing start date";
    }elseif($end_date == null){
        echo "Missing end date";
    }elseif($id == null){
        echo "Missing id";
    }else{
        echo "Something strange happened!...weird...";
    }
}

?>
