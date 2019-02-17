<?php

include ('_modules.php');

$value_1 = generate_random_string();
$value_2 = generate_random_string();

$values = array(
    $value_1,
    $value_2
);

$id = gen_id($values, true, true);

echo $id;
