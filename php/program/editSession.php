<?php

include('../_modules.php');

$program_id = $_POST['program_id'];
$session_id = $_POST['id'];
$date = $_POST['date'];
@$end_date = $_POST['end_date'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$location = clean_entries($_POST['location']);
$name = clean_entries($_POST['name']);
$description = $_POST['description'];
$video = clean_entries($_POST['video']);
$category = $_POST['category'];
$location_map = $_POST['map'];
$broadcast = $_POST['broadcast'];
$transcript = $_POST['transcript'];
@$agenda = $_POST['agenda'];
if($agenda == null){
    $agenda = array();
}
$use_agenda = $_POST['use_agenda'];
if ($use_agenda == "false"){
    $use_agenda = false;
}
if ($use_agenda == "true"){
    $use_agenda = true;
}


if ($session_id !== null && $program_id !== null && $date !== null &&
    $start_time != null && $end_time !== null && $name !== null){

    $file = DATA_PATH.'program.json';

    $program = array(
        'id' => $session_id,
        'date' => $date,
        'end_date' => $end_date,
        'name' => $name,
        'start_time' => $start_time,
        'end_time' => $end_time,
        'location' => $location,
        'map' => $location_map,
        'category' => $category,
        'broadcast' => $broadcast,
        'video' => $video,
        'transcript' => $transcript,
        'description' => $description,
        'use_agenda' => $use_agenda,
        'agenda' => $agenda
    );

    $program_record = array('id' => $program_id);
    $column_record = array('id' => $session_id);

    if(update_column(array(
        'file' => $file,
        'table' => 'programs',
        'column' => 'sessions',
        'record_id' => $program_record,
        'column_id' => $column_record,
        'record' => $program
    )) == true){

        sort_column_date_time(
            array(
                'file' => $file,
                'table' => 'programs',
                'column' => 'sessions',
                'time_key' => 'start_time',
                'date_key' => 'date',
                'id' => $program_id
            ));

        echo "success";
    }else{
        echo "Failed!";
    }

}else{
    if($date == null){
        echo "Missing date value";
    }elseif($start_time == null){
        echo "Missing start time";
    }elseif($end_time == null){
        echo "Missing end time";
    }elseif($name == null){
        echo "Missing name";
    }elseif($program_id == null){
        echo "Missing program id";
    }elseif($id == null){
        echo "Missing id";
    }
}

?>
