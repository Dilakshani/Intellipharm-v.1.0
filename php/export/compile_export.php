<?php

$export_result = [];

//Load up needed databases to fetch record data according to ids found in the
//program. For example getting the speaker name from the speaker id retrieved.
$timezone_db_file = DATA_PATH.'timezones.json';
$timezone_table_data = dump_table(
    array(
        'file' => $timezone_db_file,
        'table' => 'timezones'
    )
);

$speaker_db_file = DATA_PATH.'speakers.json';
$speakers_dump = dump_table(
    array(
        'file' => $speaker_db_file,
        'table' => 'speakers'
    )
);

$program_name = '';
$program_path = '';

foreach($programs_table_data as $program){
    if($program['id'] == $program_id){

        $program_name = $program['name'];
        $program_path = $program['path'];

        $export_result['name'] = $program['name'];

        $tz_id = '';
        $tz_name = '';
        $tz_offset = '';
        foreach($timezone_table_data as $timezone){
            if ($timezone['id'] == $program['timezone']){
                $tz_id = $timezone['tzid'];
                $tz_name = $timezone['name'];
                $tz_offset = $timezone['tzoffset'];
                $export_result['timezone'] = array(
                    "name" => $timezone['name'],
                    "code" => $timezone['tzcode'],
                    "offset" => $timezone['tzoffset']
                );
            }
        }

        //Setup ICS exporting for Calendars
        $ICS_exporter = new ICS_Export($tz_id, $tz_name, $tz_offset);

        //Generate dates
        $Program_Date_Range = new Date_Range_Generator($program['start_date'],
            $program['end_date']);

        $day_list = [];
        $program_dates = $Program_Date_Range->dates();
        $day = 1;
        foreach($program_dates as $program_date){
            $day_list[$program_date] = $day;
            $day++;
        }

        $sessions = [];
        $speakers = [];
        $previous_day = null;
        $program_sessions = [];
        $cache = [];
        foreach($program['sessions'] as $session){

            list($session_id, $cache)  =
                increment_duplicate(kebab_case($session['name']), $cache);

            //Generate calendar code for ICS files
            if ($session['category'] != 'break'){

                if (!isset($session['end_date'])){
                    $end_date = $session['date'];
                } else {
                    $end_date = $session['end_date'];
                }
                $ICS_exporter->generate(
                    $session_id,
                    $session['start_time'],
                    $session['end_time'],
                    $session['date'],
                    $end_date,
                    $session['name'],
                    $session['location']
                );
            }

            //Get agenda details
            $agenda_record = null;
            foreach($session['agenda'] as $agenda){

                if ($agenda['type'] != 'label'){

                    $presentation_record = array();

                    $path = $program['path'];
                    $photo = '';

                    //Get default image
                    $photo_table = dump_table(
                        array(
                            'file' => DATA_PATH . 'speaker_photos.json',
                            'table' => 'default'
                        )
                    );
                    $defaultPhoto = $photo_table[0]['img'];

                    //Grab speaker bio and photo
                    $bio = '';
                    $photo = '';
                    foreach ($speakers_dump as $sp_record){
                        if ($sp_record['id'] == $agenda['id']){
                            $bio = $sp_record['bio'];
                            $photo = $sp_record['photo'];
                        }
                    }

                    // Use default speaker photo if none is given
                    if ($photo == 'none'){
                        $photo = $defaultPhoto;
                    }

                    //Check if image is URL
                    preg_match('/(http:\/\/|https:\/\/).+/',$photo,$image_url_match);
                    if (count($image_url_match) > 0){
                        $photo_path = $photo;
                    } else {
                        $clean_photo_path = str_replace("uploads/","",$photo);
                        $photo_path = $path.$clean_photo_path;
                    }

                    $speaker_id = kebab_case($agenda['name']);

                    // Push the speaker to the global speakers array if they are not
                    // yet added.
                    if (!isset($speakers[$speaker_id])){
                        $speakers[$speaker_id] = array(
                            "name" => $agenda['name'],
                            "photo" => $photo_path,
                            "bio" => $bio,
                            "topic" => @$agenda['topic'],
                            "abstract" => @$agenda['abstract'],
                            "slideshare" => @$agenda['slideshare'],
                            "keynote" => $agenda['keynote'],
                            "affiliations" => [],
                            "sessions" => []
                        );
                    }

                    // Because speakers have multiple affiliations per conference, check if
                    // the current affiliation has been added. If not, chuck it up. We are
                    // assuming that a speaker has only one role at an organisation.
                    if(!isset(
                        $speakers[$speaker_id]['affiliations'][$agenda['organisation']])
                    )
                    {
                        $speakers[$speaker_id]['affiliations'][$agenda['organisation']]
                            = $agenda['position'];
                    }

                    // If the speaker hasn't already been marked as speaking at the current
                    // session, do it now.
                    if(!isset($speakers[$speaker_id]['sessions'][$session_id])){
                        $speakers[$speaker_id]['sessions'][$session_id] = array(
                            "day" => $day_list[$session['date']],
                            "name" => $session['name']
                        );
                    }

                    if (@$agenda['presentation_files'] != null){
                        foreach($agenda['presentation_files'] as $agenda_presentations){
                            foreach($program['presentation_files'] as $presentations){
                                if($presentations['id'] == $agenda_presentations['id']){

                                    //Check if file is URL
                                    preg_match('/(http:\/\/|https:\/\/).+/',$presentations['file'],
                                        $presentation_url_match);

                                    if (count($presentation_url_match) > 0){
                                        $presentation_path = $presentations['file'];
                                    } else {
                                        $clean_presentation_path = str_replace("uploads/","",
                                            $presentations['file']);
                                        $presentation_path = $path.$clean_presentation_path;
                                    }

                                    array_push($presentation_record,array(
                                        "name" => $presentations['name'],
                                        "file" => $presentation_path,
                                        "size" => $presentations['size'],
                                        "type" => $presentations['type']
                                    ));
                                }
                            }
                        }
                    }

                    // Add presentation files to the speakers records so we can show their
                    // presentation files on their profile.
                    if (!isset(
                        $speakers[$speaker_id]['sessions'][$session_id]['presentation_files']
                    )) {
                    $speakers[$speaker_id]['sessions'][$session_id]['presentation_files']
                        = $presentation_record;
                    }

                    $agenda_record[] = array(
                        "id" => $speaker_id,
                        "type" => $agenda['type'],
                        "photo" => $photo_path,
                        "name" => $agenda['name'],
                        "organisation" => $agenda['organisation'],
                        "position" => $agenda['position'],
                        "time" => @$agenda['time'],
                        "topic" => $agenda['topic'],
                        "abstract" => $agenda['abstract'],
                        "slideshare" => @$agenda['slideshare'],
                        "presentation_files" => $presentation_record
                    );
                } else {

                    $agenda_record[] = array(
                        "id" => kebab_case($agenda['name']),
                        "name" => $agenda['name'],
                        "type" => $agenda['type'],
                        "time" => $agenda['time'],
                    );
                }

            }

            //Get Transcript info record
            $transcript_record = array();
            if (isset($program['transcripts'])) {
                foreach ($program['transcripts'] as $transcript) {
                    if ($transcript['id'] == $session['transcript']) {
                        //Check if file is URL
                        preg_match('/(http:\/\/|https:\/\/).+/',$transcript['file'],
                            $transcript_url_match);
                        $transcript_path = '';
                        if (count($transcript_url_match) > 0){
                            $transcript_path = $transcript['file'];
                        } else {
                            $clean_transcript_path = str_replace("uploads/","",
                                $transcript['file']);
                            @$transcript_path = $path.$clean_transcript_path;
                        }

                        $transcript_record = array(
                            'name' => $transcript['name'],
                            'file' => $transcript_path,
                            'type' => $transcript['type'],
                            'size' => $transcript['size']
                        );
                    }
                }
            }

            //Build session array
            $session_record = array(
                "id" => $session_id,
                "date" => $session['date'],
                "end_date" => @$session['end_date'],
                "name" => $session['name'],
                "start_time" => $session['start_time'],
                "end_time" => $session['end_time'],
                "location" => $session['location'],
                "category" => $session['category'],
                "map" => $session['map'],
                "broadcast" => $session['broadcast'],
                "video" => $session['video'],
                "transcript" => $transcript_record,
                "description" => $session['description'],
                "agenda" => $agenda_record
            );


            $dates = array($session['date']);

            if (isset($session['end_date'])){
                $Date_Range = new Date_Range_Generator($session['date'],
                    $session['end_date']);

                $dates = $Date_Range->dates();
            }

            $date_count = count($dates);

            if ($date_count > 1){

                for($di = 0; $di < $date_count; $di++){

                    if (@$program_sessions[$dates[$di]] == null){

                        $program_sessions[$dates[$di]] = array(
                            "day" => $day_list[$dates[$di]],
                            "date" => $dates[$di],
                            "sessions" => array($session_record)
                        );
                    } else {
                        array_push($program_sessions[$dates[$di]]['sessions'],
                            $session_record);
                    }

                }

            } else {

                if (@$program_sessions[$dates[0]] == null){
                    $program_sessions[$dates[0]] = array(
                        "day" => $day_list[$dates[0]],
                        "date" => $dates[0],
                        "sessions" => array($session_record)
                    );
                } else {
                    array_push($program_sessions[$dates[0]]['sessions'], $session_record);
                }

            }

        }

        $export_result['days'] = $program_sessions;
        $export_result['speakers'] = $speakers;
        $export_result['ics'] = $program['path'] .
            kebab_case($program['name']) . '.ics';

        //Create ICS calendar file.
        $ICS_exporter->write(DATA_PATH . kebab_case($program['name']) . '.ics');

    }
}

//check speakers to see if any are unsed and store them into the object.
$organisations_db_file = DATA_PATH.'organisations.json';
$orgs = dump_table(
    array(
        'file' => $organisations_db_file,
        'table' => 'organisations'
    )
);

$missing_speakers = [];
foreach($programs_table_data as $program){
    $path = $program['path'];
    if($program['id'] == $program_id){
        foreach($speakers_dump as $speaker){
            $found = false;
            $missing_speaker = null;
            $speaker_id = kebab_case($speaker['name']);
            foreach ($program['speakers'] as $program_speaker){
                if ($program_speaker['id'] == $speaker['id']){
                    foreach ($export_result['speakers'] as $e_speaker){
                        if ($e_speaker['name'] == $speaker['name']){
                            $found = true;
                        }
                    }
                    if ($found == false){
                        $org_name = '';
                        foreach ($orgs as $org){
                            if ($speaker['organisation'] == $org['id']){
                                $org_name = $org['short_name'];
                            }
                        }

                        // Check if image is URL
                        $photo_path = null;
                        preg_match('/(http:\/\/|https:\/\/).+/',$speaker['photo'],$image_url_match);
                        if (count($image_url_match) > 0){
                            $photo_path = $speaker['photo'];
                        } else {
                            $clean_photo_path = str_replace("uploads/","",$speaker['photo']);
                            $photo_path = $path.$clean_photo_path;
                        }

                        $missing_speaker = array(
                            "name" => $speaker['name'],
                            "photo" => $photo_path,
                            "bio" => $speaker['bio'],
                            "topic" => @$program_speaker['topic'],
                            "abstract" => @$program_speaker['abstract'],
                            "keynote" => $program_speaker['keynote'],
                            "affiliations" => array(
                                $org_name => $speaker['position']
                            ),
                            "sessions" => []
                        );
                        break;
                    }
                }
            }
            if ($missing_speaker != null){
                $missing_speakers[$speaker_id] = $missing_speaker;
            }
        }
    }
}
$export_result['speakers'] += $missing_speakers;

// Rearrange alphabetically
$sort_speakers = $export_result['speakers'];
usort(
    $sort_speakers,
    function($a, $b) {
        return strnatcasecmp($a['name'],$b['name']);
    }
);

// usort will remove the key so we have to add it back in :(
// Will need to looking into a better solution.
$speakers_with_key = [];

for($si = 0; $si < count($sort_speakers); $si++) {
    $key = kebab_case($sort_speakers[$si]['name']);
    $speakers_with_key[$key] = $sort_speakers[$si];
}

$export_result['speakers'] = $speakers_with_key;

file_put_contents(DATA_PATH . kebab_case($program_name) . '.json',
    json_encode($export_result, JSON_PRETTY_PRINT));
