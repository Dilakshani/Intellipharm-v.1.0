<?php

require '../_modules.php';

$program_id = $_POST['id'];
@$path = $_POST['path'];
$remote = ($_POST['remote'] == 'false') ? false : $_POST['remote'];
@$host = $_POST['host'];
@$port = $_POST['port'];
@$server_path = $_POST['server_path'];

// Bamboo trigger pointpoint url
@$bamboo_code = $_POST['bamboo-code'];

$file = DATA_PATH . 'program.json';
$find = array ('id' => $program_id);

if ($remote == 'remote'){
    if ($host != null && $port != null && $server_path != null
        && $program_id != null){

        $update = array(
            'path' => $path,
            'remote' => $remote,
            'host' => $host,
            'port' => $port,
            'server_path' => $server_path
        );

        if (update_record(
            array(
                'file' => $file,
                'table' => 'programs',
                'record_id' => $find,
                'record' => $update
            )) == true){
            echo 'success';
        } else {
            echo 'Failed when trying to update record';
        }
    } else {
        echo "Failed parsing one of the following:\r\n-Host\r\n-Port\r\n-Server Path";
    }
} else if ($remote == 'bamboo') {

    if ($bamboo_code != null) {

        $update = array(
            'path' => $path,
            'remote' => $remote,
            'host' => $host,
            'port' => $port,
            'server_path' => $server_path,
            'bamboo' => $bamboo_code
        );

        if (update_record(
            array(
                'file' => $file,
                'table' => 'programs',
                'record_id' => $find,
                'record' => $update
            )) == true){
            echo 'success';
        } else {
            echo 'Failed when trying to update record';
        }
    } else {
        echo "Bamboo endpoint is not given";
    }

} else {
    //Update path only if path value is entered else do nothing
    if (isset($path) && $program_id != null){
        $update = array(
            'remote' => $remote,
            'path' => $path
        );

        if (update_record(
            array(
                'file' => $file,
                'table' => 'programs',
                'record_id' => $find,
                'record' => $update
            )) == true){
            echo 'success';
        } else {
            echo 'Failed on updating path';
        }
    } else {
        //Let's do nothing but sit and fart :-)
    }
}
