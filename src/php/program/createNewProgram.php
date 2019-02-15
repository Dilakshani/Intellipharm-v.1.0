<?php

include('../_modules.php');

$program_name = clean_entries($_POST['name']);
$timezone = clean_entries($_POST['timezone']);
$start_date = $_POST['start-date'];
$end_date = $_POST['end-date'];
@$path = strip_tags($_POST['path']);
$remote = $_POST['remote'];
@$host = $_POST['host'];
@$port = $_POST['port'];
@$server_path = $_POST['server_path'];
@$bamboo_code = $_POST['bamboo-code'];

if ($program_name != null && $timezone != null && $start_date != null
    && $end_date != null){

    $file = DATA_PATH.'program.json';

    $generate_id= array(
        'name' => $program_name,
        'timezone' => $timezone
    );

    $id = gen_id($generate_id);

    $program = array(
        'id' => $id,
        'name' => $program_name,
        'timezone' => $timezone,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'path' => $path,
        'remote' => $remote,
        'host' => $host,
        'port' => $port,
        'server_path' => $server_path,
        'bamboo' => $bamboo_code,
        'speakers' => array(),
        'sessions' => array(),
        'presentation_files' => array(),
        'maps' => array()
    );

    $program_record = array(
        'file' => $file,
        'table' => 'programs',
        'record' => $program
    );

    if(insert_table_record($program_record) == true){

        $Date_Ranger = new Date_Range_Generator($start_date, $end_date);
        $dates = $Date_Ranger->dates();
        $program_dates = array(
            'program' => $id,
            'dates' => $dates
        );

        insert_table_record(array(
            'file' => DATA_PATH . 'program_dates.json',
            'table' => 'dates',
            'record' => $program_dates
        ));

        echo "success";
    }else{
        echo "Failed!";
    }

}else{
    if ($program_name == null){
        echo "Missing program name!";
    }elseif($timezone == null){
        echo "Missing timezone!";
    }elseif($start_date == null){
        echo "Missing start date";
    }elseif($end_date == null){
        echo "Missing end date";
    }else{
        echo "Something strange happened!...weird...";
    }

}

?>
