<?php
//Upload all used files to the assigned server via already declared class
//$sftp.

//Open created program file.
$program_file_contents = file_get_contents(DATA_PATH .
kebab_case($program_name) . '.json');
$program_contents = json_decode($program_file_contents, true);
//Store found files to transfer
$files = array();

//Store found images to transfer
$images = array();

//Pattern to get http
$http_pat = '/(http:\/\/|https:\/\/).*/';

//Scan through speakers and find files to later upload to remote
foreach ($program_contents['speakers'] as $speaker) {
    foreach ($speaker['sessions'] as $session) {
        foreach ($session['presentation_files'] as $presentation){
            $presentation_file = $presentation['file'];
            if ($program_path != null) {
                $presentation_file =
                    str_replace($program_path, '', $presentation_file);
            }
            if (preg_match($http_pat, $presentation_file) == false) {
                if ($files == null){
                    $files = array($presentation_file);
                }
                foreach ($files as $file) {
                    if ($file != $presentation_file){
                        array_push($files, $presentation_file);
                        break;
                    }
                }
            }
        }
    }

    //Check if photo is not a URL because we cannot move a URL asset to a server.
    $speaker_photo = $speaker['photo'];
    if ($program_path != null) {
        $speaker_photo = str_replace($program_path, '', $speaker_photo);
    }
    if (preg_match($http_pat, $speaker['photo']) == false) {
        array_push($images, $speaker_photo);
    }
}

//Scan through session to get transcript to upload
foreach($program_contents['days'] as $day) {
    foreach($day['sessions'] as $session) {
        if ($session['transcript'] != null) {
            $transcript_file = $session['transcript']['file'];
            if ($program_path != null) {
                $transcript_file =
                    str_replace($program_path, '', $transcript_file);
            }
            if (preg_match($http_pat, $transcript_file) == false) {
                if ($files == null) {
                    $files = array($transcript_file);
                }
                foreach ($files as $file) {
                    if ($file != $transcript_file) {
                        array_push($files, $transcript_file);
                        break;
                    }
                }
            }
        }
    }
}
