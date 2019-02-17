<?php

include_once("gather_files.php");

//Create files folder
$sftp->mkdir($program['server_path'] . 'files');
$sftp->chmod(0775, $program['server_path'] . 'files');

//Create images folder
$sftp->mkdir($program['server_path'] . 'images');
$sftp->chmod(0775, $program['server_path'] . 'images');

//Delete existing program folder in files
$sftp->rmdir($program['server_path'] . 'files/' . $program_id, true);

//Delete existing program folder in images
$sftp->rmdir($program['server_path'] . 'images/' . $program_id, true);

//Create program folder in uploads directory
$sftp->mkdir($program['server_path'] . 'files/' . $program_id);
$sftp->chmod(0775, $program['server_path'] . 'files/' . $program_id);

//Create program folder in images directory
$sftp->mkdir($program['server_path'] . 'images/');
$sftp->chmod(0775, $program['server_path'] . 'images/');

//Let's upload files to the remote server!
//Starting with presentation files
foreach ($files as $file) {
    $sftp->put($program['server_path'] . $file, ROOT . 'uploads/' . $file,
    \phpseclib\Net\SFTP::SOURCE_LOCAL_FILE);
}

//Now let's do the images aka speaker photos
foreach ($images as $image) {
    $sftp->put($program['server_path'] . $image, ROOT . 'uploads/' . $image,
    \phpseclib\Net\SFTP::SOURCE_LOCAL_FILE);
}
