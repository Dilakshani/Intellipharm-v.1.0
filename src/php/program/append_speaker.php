<?php

include('../_modules.php');

$id = clean_entries($_POST['speaker']);

$keynote = ($_POST['keynote'] != null) ? clean_entries($_POST['keynote']) : 'no';
$isKeynote = false;
if ($keynote == 'yes'){
  $isKeynote = true;
}

$program_id = $_POST['program_id'];

if ($id != null){

  $file = DATA_PATH.'program.json';

  $find = array(
    "id" => $program_id
  );

  $speaker = array(
    'id' => $id,
    'keynote' => $isKeynote
  );

  $obj = array(
    'file' => $file,
    'table' => 'programs',
    'column' => 'speakers',
    'record_id' => $find,
    'record' => $speaker
  );

  if(insert_column_record($obj) == true){
    echo "success";
  }else{
    echo "Failed!";
  }

}else{

  echo "Missing Speaker ID!";

}

 ?>
