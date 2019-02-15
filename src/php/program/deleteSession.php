<?php

include('../_modules.php');

$id = $_POST['id'];
$program_id = $_POST['program_id'];

if ($id != null){

  $file = DATA_PATH.'program.json';
  $Data_Handler = new Data_Handler($file);
  $Data_Handler->load_table('programs');
  $Data_Handler->load_column('sessions');

  $record = array('id' => $program_id);
  $column = array('id' => $id);
  
  if(delete_column(array(
    'file' => $file,
    'table' => 'programs',
    'column' => 'sessions',
    'record_id' => $record,
    'column_id' => $column
  )) == true){
    echo "success";
  }else{
    echo "Failed";
  }

}else{
  echo "Missing ID!";
}

 ?>
