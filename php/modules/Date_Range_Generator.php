<?php

class Date_Range_Generator{

  private $_start_date;
  private $_end_date;

  public function __construct($start_date, $end_date){

    $this->_start_date = $start_date;
    $this->_end_date = $end_date;

  }

  public function dates(){

    $start_dmy = str_replace('/','-',$this->_start_date);
    $end_dmy = str_replace('/','-',$this->_end_date);
    $start = new DateTime($start_dmy);
    $end = new DateTime($end_dmy);
    $interval = new DateInterval('P1D'); // 1 day interval
    $period = new DatePeriod($start, $interval, $end);

    $dates = [];

    foreach ($period as $day) {
        // Do stuff with each $day...
        array_push($dates,$day->format('d-m-Y'));
    }
    array_push($dates,$this->_end_date);
    return $dates;

  }

}
