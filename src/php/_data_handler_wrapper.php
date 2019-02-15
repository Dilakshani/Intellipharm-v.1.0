<?php

function has_required_values($obj, $use_column = false) {
    $file = isset($obj['file']) ? $obj['file'] : die('no file given');
    $table = isset($obj['table']) ? $obj['table'] : die('no table given');
    if ($use_column == true) {
        $column = isset($obj['column']) ? $obj['column'] : die('no column given');
    }
    return true;
}

function insert_table_record($obj) {
    $record = isset($obj['record']) ? $obj['record'] : die('no record given');
    if (gettype($record) != 'array') {
        die('Record is not an array');
    }
    if (has_required_values($obj) == true) {
        $dh = new Data_Handler($obj['file']);
        $dh->load_table($obj['table']);
        if ($dh->insert($record) == true) {
            return true;
        } else {
            return false;
        }
    }
}

function insert_column_record($obj){
    $record_id = isset($obj['record_id']) ? $obj['record_id'] :
        die('no record id given');
    $record = isset($obj['record']) ? $obj['record'] : die('no record given');

    if (has_required_values($obj, true) == true) {
        $dh = new Data_Handler($obj['file']);
        $dh->load_table($obj['table']);
        $dh->load_column($obj['column']);

        if ($dh->insert_to_column($record_id, $record) == true){
            return true;
        } else {
            return false;
        }
    }
}

function update_record($obj) {
    $record_id = isset($obj['record_id']) ? $obj['record_id'] :
        die('no id given');
    if (gettype($record_id) != 'array') {
        die('ID must be supplied as an array to find by key => value');
    }
    $record = isset($obj['record']) ? $obj['record'] : die('No record given');
    if (has_required_values($obj) == true) {
        $dh = new Data_Handler($obj['file']);
        $dh->load_table($obj['table']);

        if ($dh->update($record_id, $record) == true) {
            return true;
        } else {
            return false;
        }
    }
}

function update_column($obj) {
    $record_id = isset($obj['record_id']) ? $obj['record_id'] :
        die('no record to find is given');
    if (gettype($record_id) != 'array') {
        die('record id is not an array');
    }
    $column_id = isset($obj['column_id']) ? $obj['column_id'] :
        die('no column id to find is given');
    if (gettype($column_id) != 'array') {
        die('column id is not an array');
    }
    $record = isset($obj['record']) ? $obj['record'] :
        die('No record given');
    if (gettype($record) != 'array') {
        die('Record given is not an array');
    }

    if (has_required_values($obj, true) == true) {
        $dh = new Data_Handler($obj['file']);
        $dh->load_table($obj['table']);
        $dh->load_column($obj['column']);

        if ($dh->update_column($record_id, $column_id, $record)
        == true) {
            return true;
        } else {
            return false;
        }
    }
}

function sort_column_date_time($obj) {
    $time = isset($obj['time_key']) ? $obj['time_key'] :
        die('no time key given');
    $date = isset($obj['date_key']) ? $obj['date_key'] :
        die('no date key given');
    $id = isset($obj['id']) ? $obj['id'] : die('no id given');

    if (has_required_values($obj, true) == true) {
        $dh = new Data_Handler($obj['file']);
        $dh->load_table($obj['table']);
        $dh->load_column($obj['column']);

        if ($dh->sort_column_by_date_time(
            array('id' => $id),
            $obj['column'],
            $date,
            $time) == true) {

            return true;
        } else {
            return false;
        }
    }
}

function sort_records_alphabetically($obj) {
    if (has_required_values($obj, true) == true) {
        $dh = new Data_Handler($obj['file']);
        $dh->load_table($obj['table']);

        if ($dh->sort_records_alphabetically($obj['column'])) {
            return true;
        }
        return false;
    }
}

function sort_column_records_alphabetically($obj) {
    $record_id = isset($obj['record_id']) ? $obj['record_id'] :
        die('no id given');
    if (gettype($record_id) != 'array') {
        die('ID must be supplied as an array to find by key => value');
    }

    if (has_required_values($obj, true) == true) {
        $dh = new Data_Handler($obj['file']);
        $dh->load_table($obj['table']);

        if ($dh->sort_column_records_alphabetically(
            $record_id,
            $obj['column'],
            $obj['key']
        )) {
            return true;
        }
        return false;
    }
}

function delete_table($obj) {
    if (has_required_values($obj) == true) {
        $dh = new Data_Handler($obj['file']);
        if ($dh->delete_table($obj['table']) == true) {
            return true;
        } else {
            return false;
        }
    }
}

function delete_record($obj) {
    $record_id = isset($obj['record_id']) ? $obj['record_id'] :
        die('No record to find given');

    if (has_required_values($obj) == true) {
        $dh = new Data_Handler($obj['file']);
        $dh->load_table($obj['table']);

        if ($dh->delete_record($record_id) == true) {
            return true;
        } else {
            return false;
        }
    }
}

function delete_column($obj) {
    $record_id = isset($obj['record_id']) ? $obj['record_id'] :
        die('no record id given');
    if (gettype($record_id) != 'array') {
        die('record is not array');
    }
    $column_id = (gettype($obj['column_id']) == 'array') ?
        $obj['column_id'] : die('column id is not array');

    if (has_required_values($obj, true) == true) {
        $dh = new Data_Handler($obj['file']);
        $dh->load_table($obj['table']);
        $dh->load_column($obj['column']);

        if (isset($column_id) == true) {
            if ($dh->delete_column($record_id, $column_id) == true) {
                return true;
            } else {
                return false;
            }
        } else {
            if ($dh->delete_column($record_id) == true) {
                return true;
            } else {
                return false;
            }
        }
    }
}

function find_record($obj) {
    $record_id = isset($obj['record_id']) ? $obj['record_id'] :
        die('No record id is given');
    if (gettype($record_id) != 'array') {
        die('Array is not an array');
    }
    if (has_required_values($obj) == true) {
        $dh = new Data_Handler($obj['file']);
        $dh->load_table($obj['table']);
        if ($dh->find($record_id) == true) {
            return true;
        } else {
            return false;
        }
    }
}

function dump_table($obj) {
    if (has_required_values($obj) == true) {
        $dh = new Data_Handler($obj['file']);
        $dh->load_table($obj['table']);
        return $dh->fetch_table();
    }
}
