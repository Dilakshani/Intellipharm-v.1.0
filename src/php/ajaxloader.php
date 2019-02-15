<?php
include "config.php";

$data = json_decode(file_get_contents("php://input"));

$request = $data->request;

// Fetch All records
if($request == 1){
  $userData = mysqli_query($con,"select * from members order by id desc");

  $response = array();
  while($row = mysqli_fetch_assoc($userData)){
    $response[] = $row;
  }

  echo json_encode($response);
  exit;
}

// Add record
if($request == 2){
  $firstname = $data->firstname;
  $surname = $data->surname;
  $email = $data->email;

  $userData = mysqli_query($con,"SELECT * FROM users WHERE firstname='".$firstname."'");
  if(mysqli_num_rows($userData) == 0){
    mysqli_query($con,"INSERT INTO members(firstname,surname,email) VALUES('".$firstname."','".$surname."','".$email."')");
    echo "Insert successfully";
  }else{
    echo "Member first name already exists.";
  }

  exit;
}

// Update record
if($request == 3){
  $id = $data->id;
  $surname = $data->surname;
  $email = $data->email;

  mysqli_query($con,"UPDATE members SET name='".$surname."',email='".$email."' WHERE id=".$id);

  echo "Update successfully";
  exit;
}

// Delete record
if($request == 4){
  $id = $data->id;

  mysqli_query($con,"DELETE FROM members WHERE id=".$id);

  echo "Delete successfully";
  exit;
}

?>
