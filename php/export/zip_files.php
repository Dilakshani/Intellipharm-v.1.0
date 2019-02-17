<?php

include_once("gather_files.php");

function zip_files($filename, $files) {

    $zip = new ZipArchive();

    if ($zip->open(ROOT . 'exports/' . $filename, ZIPARCHIVE::CREATE) !== TRUE) {
        exit("cannot open <$filename>\n");
    }

    //Let's zip files
    //Starting with presentation files
    foreach ($files as $file) {
        $zip->addFile(ROOT . 'uploads/' . $file);
    }
    $zip->close();
}

$zip_files = $program_id . '-files.zip';
$zip_images = $program_id . '-images.zip';

zip_files($zip_files, $files);
zip_files($zip_images, $images);
