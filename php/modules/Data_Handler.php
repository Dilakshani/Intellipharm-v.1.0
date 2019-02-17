<?php

/*

  @Author Clyde Smets | clyde@apnic.net

  @Desc Insert data into a file. If a file doesn't exist, create it. Add new
  entries. Look up entries and replace it with new data.

  data
  array (
    "identifier" => "value"
  )

 */

class Data_Handler{

    private $_FILE;
    private $_DATA;
    private $_TABLE;
    private $_COLUMN;
    private $_COLUMN_DATA;

    public function __construct($file){
        $this->_FILE = $file;
        $this->_build_file($file);
    }

    public function fetch_data(){
        $file_contents = file_get_contents($this->_FILE);
        return json_decode($file_contents, TRUE);
    }

    public function load_table($table){
        $fetch = $this->fetch_data();
        $this->_DATA = @$fetch[$table];
        $this->_TABLE = $table;
    }

    public function fetch_table(){
        return $this->_DATA;
    }

    public function load_column($column){
        $fetch = $this->fetch_data();
        $this->_COLUMN_DATA = isset($fetch[$this->_TABLE][$column]) ? $fetch[$this->_TABLE][$column] : '';
        $this->_COLUMN = $column;
    }

    public function fetch_column(){
        return $this->_COLUMN_DATA;
    }

    public function insert($record){
        try{
            $database = $this->fetch_data();
            if(@$database[$this->_TABLE] == NULL){
                if ($database == '' || $database == NULL){
                    $database = array($this->_TABLE => array($record));
                }else{
                    $database[$this->_TABLE] = array();
                    array_push($database[$this->_TABLE], $record);
                }
            }else{
                array_push($database[$this->_TABLE], $record);
            }
            file_put_contents($this->_FILE, json_encode($database, JSON_PRETTY_PRINT));
            return TRUE;
        } catch(Exception $e){
            return FALSE;
        }

    }

    public function insert_to_column($find, $insert){
        try{
            $column = array_keys($find)[0];
            $value = $find[$column];
            $records = $this->_DATA;
            $match_key = array_search($value,array_column($records,'id'));
            $record = $records[$match_key];

            if ($record[$this->_COLUMN] == null){
                $record[$this->_COLUMN] = array($insert);
            }else{
                array_push($record[$this->_COLUMN], $insert);
            }

            $this->replace($find, $record);
            return TRUE;
        } catch(Exception $e){
            return FALSE;
        }
    }

    public function update_column($record, $column, $value){
        try {
            $database = $this->fetch_data();
            $column_key = array_keys($record)[0];
            $value_key = $record[$column_key];
            $record_key = array_search($value_key,array_column($this->_DATA,$column_key));
            $found_record = $database[$this->_TABLE][$record_key];

            $column_array_key = array_keys($column)[0];
            $column_array_value = $column[$column_array_key];
            $column_key = array_search($column_array_value,array_column($found_record[$this->_COLUMN],$column_array_key));
            $found_column_record = $found_record[$this->_COLUMN][$column_key];

            for($ci = 0; $ci < count($value); $ci++){
                for($ri = 0; $ri < count($found_column_record); $ri++){
                    $contents_key = array_keys($value)[$ci];
                    $column_record_key = array_keys($found_column_record)[$ri];
                    if($contents_key == $column_record_key){
                        $found_column_record[$column_record_key] = $value[$contents_key];
                        break;
                    }
                }
            }
            $database[$this->_TABLE][$record_key][$this->_COLUMN][$column_key] = $found_column_record;
            file_put_contents($this->_FILE, json_encode($database, JSON_PRETTY_PRINT));
            return TRUE;
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return FALSE;
        }
    }

    // $find = array("id" => "value")
    public function update($find, $contents){
        try {
            $database = $this->fetch_data();
            $column = array_keys($find)[0];
            $value = $find[$column];
            $match_key = array_search($value,array_column($this->_DATA,$column));
            $record = $database[$this->_TABLE][$match_key];

            for($ci = 0; $ci < count($contents); $ci++){
                for($ri = 0; $ri < count($record); $ri++){
                    $contents_key = array_keys($contents)[$ci];
                    $record_key = array_keys($record)[$ri];
                    if($contents_key == $record_key){
                        $record[$record_key] = $contents[$contents_key];
                        break;
                    }
                }
            }

            $database[$this->_TABLE][$match_key] = $record;
            file_put_contents($this->_FILE, json_encode($database, JSON_PRETTY_PRINT));
            return TRUE;
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return FALSE;
        }
    }

    public function replace($find, $replace){
        try {
            $database = $this->fetch_data();
            $column = array_keys($find)[0];
            $value = $find[$column];
            $key = array_search($value,array_column($this->_DATA,$column));
            $database[$this->_TABLE][$key] = $replace;
            file_put_contents($this->_FILE, json_encode($database, JSON_PRETTY_PRINT));
            return TRUE;
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return FALSE;
        }
    }

    public function find($find){
        $table_data = $this->_DATA;
        $key = $this->_get_key($find);
        if ($key != null){
            $result = $table_data[$key];
            if ($result == NULL){
                return FALSE;
            }else{
                return $result;
            }
        }else{
            return FALSE;
        }
    }

    public function delete_table($table_name) {
        try {
            $database = $this->fetch_data();
            $tables = array_keys($database);
            $key = false;
            for($i = 0; $i < count($tables); $i++){
                if ($tables[$i] == $table_name){
                    $key = $i;
                    break;
                }
            }
            array_splice($database,$key,1);
            file_put_contents($this->_FILE, json_encode($database, JSON_PRETTY_PRINT));
            return TRUE;
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            return FALSE;
        }
    }

    // $find = array("id" => "value")
    public function delete_record($record_id){
        try {
            $database = $this->fetch_data();
            $key = $this->_get_key($record_id);
            array_splice($database[$this->_TABLE],$key,1);
            file_put_contents($this->_FILE, json_encode($database, JSON_PRETTY_PRINT));
            return TRUE;
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return FALSE;
        }
    }

    public function delete_column($record, $column = null){
        try {

            $database = $this->fetch_data();

            $found_record_key = $this->_get_key($record);

            if ($column == null){
                unset( $database[$this->_TABLE][$found_record_key][$this->_COLUMN] );
            }else{
                $column_record = $database[$this->_TABLE][$found_record_key][$this->_COLUMN];
                $column_key = array_keys($column)[0];
                $column_value = $column[$column_key];
                $column_record_key = array_search($column_value,array_column($column_record,$column_key));
                array_splice( $database[$this->_TABLE][$found_record_key][$this->_COLUMN],$column_record_key, 1);
            }

            file_put_contents($this->_FILE, json_encode($database, JSON_PRETTY_PRINT));
            return TRUE;

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return FALSE;
        }
    }

    public function sort_table_by_date($record){

    }

    public function sort_column_by_date_time($record, $column, $date_key, $time_key){
        $database = $this->fetch_data();

        $this->load_table($this->_TABLE);
        $this->load_column($this->_COLUMN);

        $column_key = array_keys($record)[0];
        $value_key = $record[$column_key];
        $record_key = array_search($value_key,array_column($this->_DATA,$column_key));
        $found_record = $database[$this->_TABLE][$record_key];
        $column_record = $database[$this->_TABLE][$record_key][$column];

        foreach($column_record as $k => $val){
            $time[$k] = $val[$time_key];
            $fix_date = str_replace('/','-',$val[$date_key]);
            $date[$k] = strtotime($fix_date);
        }
        array_multisort($date, SORT_ASC, $time, SORT_ASC, $column_record);

        $database[$this->_TABLE][$record_key][$column] = $column_record;

        file_put_contents($this->_FILE, json_encode($database, JSON_PRETTY_PRINT));
    }

    public function sort_column_by_value($record, $column, $key){
        $database = $this->fetch_data();

        $this->load_table($this->_TABLE);
        $this->load_column($this->_COLUMN);

        $column_key = array_keys($record)[0];
        $value_key = $record[$column_key];
        $record_key = array_search($value_key,array_column($this->_DATA,$column_key));
        $found_record = $database[$this->_TABLE][$record_key];
        $column_record = $database[$this->_TABLE][$record_key][$column];

        foreach($column_record as $k => $val){
            $time[$k] = $val[$key];
        }
        array_multisort($time, SORT_ASC, $column_record);

        $database[$this->_TABLE][$record_key][$column] = $column_record;
        file_put_contents($this->_FILE, json_encode($database, JSON_PRETTY_PRINT));
    }

    public function sort_records_alphabetically($key) {
        $database = $this->fetch_data();
        $table = $database[$this->_TABLE];
        usort(
            $table,
            function($a, $b) use ($key) {
                return strnatcasecmp($a[$key],$b[$key]);
            }
        );

        $database[$this->_TABLE] = $table;
        file_put_contents($this->_FILE, json_encode($database, JSON_PRETTY_PRINT));

        return true;
    }

    public function sort_column_records_alphabetically($find, $sort_column, $key) {
        $database = $this->fetch_data();
        $table = $database[$this->_TABLE];
        $column = array_keys($find)[0];
        $value = $find[$column];

        for($i = 0; $i < count($table); $i++) {
            if ($table[$i][$column] == $value) {

                $column_data = $table[$i][$sort_column];
                usort(
                    $column_data,
                    function($a, $b) use ($key) {
                        return strnatcasecmp($a[$key],$b[$key]);
                    }
                );

                $table[$i][$sort_column] = $column_data;

                $database[$this->_TABLE] = $table;
                file_put_contents($this->_FILE, json_encode($database, JSON_PRETTY_PRINT));

                break;
            }
        }

        return true;
    }

    private function _get_key($find){
        $column = array_keys($find)[0];
        $value = $find[$column];
        $key = array_search($value,array_column($this->_DATA,$column));
        return $key;
    }

    private function _build_file($file){
        if (!file_exists($file)){
            $path = $this->_get_path($file);
            if($path != NULL){
                mkdir($path,0777,TRUE);
            }
            $new_file = fopen($file, 'w');
            fclose($new_file);
        }
    }

    private function _get_path($file){
        $path = '';
        if(is_dir($file) == FALSE){
            $directories = explode("/",$file);
            $filename = $directories[count($directories)];
            if(strpos($filename,'.') == TRUE){
                for($i = 0; $i < (count($directories) - 1); $i++){
                    $path .= $directories[$i]."/";
                }
                return $path;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

}

?>
