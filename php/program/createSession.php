<?php

include('../_modules.php');

$program_id = $_POST['program_id'];
$date = $_POST['date'];
@$end_date = $_POST['end_date'];
$start_time = $_POST['start-time'];
$end_time = $_POST['end-time'];
@$location = clean_entries($_POST['location']);
$name = clean_entries($_POST['name']);
$category = $_POST['category'];

if ($program_id !== null && $date !== null && $start_time != null &&
    $end_time !== null && $name !== null){

    $file = DATA_PATH.'program.json';

    $generate_id= array(
        'date' => $date,
        'name' => $name,
        'start_time' => $start_time,
        'end_time' => $end_time,
        'location' => $end_time
    );

    $id = gen_id($generate_id);

    $program = array(
        'id' => $id,
        'date' => $date,
        'end_date' => $end_date,
        'name' => $name,
        'start_time' => $start_time,
        'end_time' => $end_time,
        'location' => $location,
        'map' => null,
        'category' => $category,
        'broadcast' => null,
        'video' => null,
        'transcript' => null,
        'description' => null,
        'use_agenda' => false,
        'agenda' => array()
    );

    $parse_record = array(
        'file' => $file,
        'table' => 'programs',
        'column' => 'sessions',
        'record_id' => array('id' => $program_id),
        'record' => $program
    );

    if(insert_column_record($parse_record)  == true){
        $options = array(
            'id' => $program_id,
            'file' => $file,
            'table' => 'programs',
            'column' => 'sessions',
            'date_key' => 'date',
            'time_key' => 'start_time'
        );
        sort_column_date_time($options);

        echo "success";
    }else{
        echo "Failed!";
    }

}else{
    if($date !== null){
        echo "Missing date value";
    }elseif($start_time !== null){
        echo "Missing start time";
    }elseif($end_time !== null){
        echo "Missing end time";
    }elseif($name !== null){
        echo "Missing name";
    }
}

?>
