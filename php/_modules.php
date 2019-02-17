<?php

require 'modules/Data_Handler.php';
require 'modules/File_Uploader.php';
require 'modules/Date_Range_Generator.php';
require 'modules/ICS_Export.php';

define("ROOT", "../../");
define("DATA_PATH", ROOT."data/");

function clean_entries($string){
    $string = strip_tags($string);
    $string = stripslashes($string);
    return $string;
}

function gen_id($array, $use_every_other = false, $random_number = true){
    $id = '';
    foreach($array as $item){
        $item = mb_convert_encoding($item, 'ASCII');;
        $banned_characters = array('.','+','?','"',"'",'\\','/','&','#','$',',',':',';','@','=', ' ');
        foreach($banned_characters as $char){
            $item = str_replace($char,'',$item);
        }
        if ($use_every_other == true){
            for($i = strlen($item) - 1; $i >= 0; $i -= 2){
                $id .= $item[$i];
            }
        }else{
            $id .= substr($item, 0, 2);
        }
    }
    if ($random_number == true){
        $random_num = rand(100,999);
        $id = $id.$random_num;
    }
    return $id;
}

function generate_random_string($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

function file_size_convert($bytes){

    $bytes = floatval($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "TB",
            "VALUE" => pow(1024, 4)
        ),
        1 => array(
            "UNIT" => "GB",
            "VALUE" => pow(1024, 3)
        ),
        2 => array(
            "UNIT" => "MB",
            "VALUE" => pow(1024, 2)
        ),
        3 => array(
            "UNIT" => "KB",
            "VALUE" => 1024
        ),
        4 => array(
            "UNIT" => "B",
            "VALUE" => 1
        ),
    );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}

function kebab_case($string){
    $string = trim(strtolower($string));
    $kebab = str_replace(' ', '-', $string);
    $remove_characters = preg_replace('/[^a-zA-Z0-9-]/', '', $kebab);
    return $remove_characters;
}

function increment_duplicate($string, $increment_cache){

	if (count($increment_cache) > 0){
		for ($i = 0; $i < count($increment_cache); $i++){
			if ($increment_cache[$i]['id'] == $string) {
				$increment_cache[$i]['count'] = $increment_cache[$i]['count'] + 1;
				return array($string . $increment_cache[$i]['count'], $increment_cache);
			}
		}
	}

	$cache = [];
	$cache['id'] = $string;
	$cache['count'] = 1;
	array_push($increment_cache, $cache);

	return array($string, $increment_cache);
}

function curl_get_file_size( $url ) {
    // Assume failure.
    $result = -1;

    $curl = curl_init( $url );

    // Issue a HEAD request and follow any redirects.
    curl_setopt( $curl, CURLOPT_NOBODY, true );
    curl_setopt( $curl, CURLOPT_HEADER, true );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );

    $data = curl_exec( $curl );
    curl_close( $curl );

    if( $data ) {
        $content_length = "unknown";
        $status = "unknown";

        if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
            $status = (int)$matches[1];
        }

        if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
            $content_length = (int)$matches[1];
        }

        // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
        if( $status == 200 || ($status > 300 && $status <= 308) ) {
            $result = $content_length;
        }
    }
    $clen = $result;
    $size = $clen;
    switch ($clen) {
    case $clen < 1024:
        $size = $clen .' B'; break;
    case $clen < 1048576:
        $size = round($clen / 1024, 2) .' KiB'; break;
    case $clen < 1073741824:
        $size = round($clen / 1048576, 2) . ' MiB'; break;
    case $clen < 1099511627776:
        $size = round($clen / 1073741824, 2) . ' GiB'; break;
    }

    return $size;
}

//Include a library of functions that wrap the data hander to keep things in
//once place if changes to the data handler class happens
include_once('_data_handler_wrapper.php');
?>
