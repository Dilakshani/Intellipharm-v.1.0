<?php

include('../_modules.php');

$program_id = $_POST['program_id'];
@$url = $_POST['url'];
$presentation_id = $_POST['presentation'];

if ($program_id != null && $presentation_id != null){

    //Get dump of programs
    $programs = dump_table(array(
        'file' => DATA_PATH . 'program.json',
        'table' => 'programs'
    ));

    $presentation = '';

    foreach ($programs as $program) {
        if ($program['id'] == $program_id) {
            $presentations = $program['presentation_files'];
            foreach ($presentations as $record) {
                if ($record['id'] == $presentation_id) {
                    $presentation = $record;
                }
            }
        }
    }

    if (!isset($url)){
        //Delete existing file
        $fo = fopen(ROOT . $presentation['file'], 'w+');
        fclose($fo);
        unlink(ROOT . $presentation['file']);

        //Upload new file
        $file_types = array("pdf","ppt","pptx");
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

    $updated_presentation = array(
        'file' => $file,
        'type' => $type,
        'size' => $file_size
    );

    $record = array('id' => $program_id);
    $column = array('id' => $presentation_id);

    if (update_column(
        array(
            'file' => DATA_PATH . 'program.json',
            'table' => 'programs',
            'column' => 'presentation_files',
            'record_id' => $record,
            'column_id' => $column,
            'record' => $updated_presentation
        )
    ) == true){
        echo "Success!";
    }else{
        echo "Error occured whilst entering in data!";
    }

}else{
    echo "Missing program id<br />Upload failed!";
}
