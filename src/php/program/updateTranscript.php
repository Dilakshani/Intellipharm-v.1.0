<?php

include('../_modules.php');

$program_id = $_POST['program_id'];
@$url = $_POST['url'];
$transcript_id = $_POST['transcript'];

if ($program_id != null && $transcript_id != null){

    //Get dump of programs
    $programs = dump_table(array(
        'file' => DATA_PATH . 'program.json',
        'table' => 'programs'
    ));

    $transcript = '';

    foreach ($programs as $program) {
        if ($program['id'] == $program_id) {
            $transcripts = $program['transcripts'];
        }
    }

    foreach ($transcripts as $record) {
        if ($record['id'] == $transcript_id) {
            $transcript = $record;
        }
    }

    if (!isset($url)){
        //Delete existing file
        $fo = fopen(ROOT . $transcript['file'], 'w+');
        fclose($fo);
        unlink(ROOT . $transcript['file']);

        //Upload new file
        $file_types = array("txt");
        $File_Uploader = new File_Uploader($file_types,'FILE',$program_id.'/');
        if($File_Uploader->upload('file') == true){
            $file = $File_Uploader->get_path();
            $file_size = file_size_convert($File_Uploader->get_file_size());
            $type = $File_Uploader->get_file_extension();
        }else{
            echo "Failed!";
        }
    } else {
        $file = $url;
        $file_size = curl_get_file_size($url);
        $type = pathinfo($file, PATHINFO_EXTENSION);
    }

    $updated_transcript = array(
        'file' => $file,
        'type' => $type,
        'size' => $file_size
    );

    $record = array('id' => $program_id);
    $column = array('id' => $transcript_id);

    if (update_column(
        array(
            'file' => DATA_PATH . 'program.json',
            'table' => 'programs',
            'column' => 'transcripts',
            'record_id' => $record,
            'column_id' => $column,
            'record' => $updated_transcript
        )
    ) == true){
        echo "Success!";
    }else{
        echo "Error occured whilst entering in data!";
    }

}else{
    echo "Missing program id<br />Upload failed!";
}
