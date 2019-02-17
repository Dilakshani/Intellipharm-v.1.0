<?php

include('../_modules.php');

$id = $_POST['id'];

if ($id != null){

    $file = DATA_PATH.'program.json';
    $Data_Handler = new Data_Handler($file);
    $Data_Handler->load_table('programs');

    $find = array('id' => $id);

    if(delete_record(array(
        'file' => $file,
        'table' => 'programs',
        'record_id' => $find
    )) == true){
    
        $date_file = DATA_PATH.'program_dates.json';
        if(delete_record(array(
            'file' => $date_file,
            'table' => 'dates',
            'record_id' => array('program' => $id)
        )) == true){
            echo "success";
        }
        
    } else {
        echo "Failed";
    }

}else{
    echo "Missing ID!";
}

?>
