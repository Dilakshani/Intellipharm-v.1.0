<?php

/*
	@author Clyde Smets - clyde@apnic.net
*/

class ICS_Export {

	private $_export = '';
	private $_tz = '';
	private $_tz_offset = '';
	private $_tz_name = '';
	private $_prodId = "-//APNIC//CMS//EN";

	public function __construct($timezone, $timezone_name, $timezone_offset){
		$this->_tz = $timezone;
		$this->_tz_offset = str_replace(':','',$timezone_offset);
		$this->_tz_name = $timezone_name;
	}

	public function generate(	$uid,
								$start_time,
								$end_time,
								$start_date,
								$end_date,
								$summary,
								$location ){

		$sDate = date_create($start_date);
		$clean_start_date = date_format($sDate, "Ymd");
		$eDate = date_create($end_date);
		$clean_end_date = date_format($eDate, "Ymd");
		$clean_start_time = str_replace(':', '', $start_time);
		$clean_end_time = str_replace(':', '', $end_time);
		$dst = $clean_start_date . 'T' . $clean_start_time . '00';
		$det = $clean_end_date . 'T' . $clean_end_time . '00';

		$ics = "BEGIN:VEVENT\r\nTRANSP:TRANSPARENT\r\n";
		$ics .= "UID:" . $uid . "\r\n";
		$ics .= "DTSTAMP:" . $this->_create_DTSTAMP() . "\r\n";
		$ics .= "DTSTART;TZID=" . $this->_tz . ":" . $dst . "\r\n";
		$ics .= "DTEND;TZID=" . $this->_tz . ":" . $det . "\r\n";
		$ics .= "SUMMARY:" . str_replace(',','\,',$summary) . "\r\n";
		$ics .= "LOCATION:" . str_replace(',','\,',$location) . "\r\n";
		$ics .= "END:VEVENT\r\n\r\n";

		$this->_export .= $ics;
	}

	public function write($file){
		$head = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:" . $this->_prodId . "\r\n";
		$head .= "METHOD:PUBLISH\r\nCALSCALE:GREGORIAN\r\n\r\n";
		$head .= "BEGIN:VTIMEZONE\r\n";
		$head .= "TZID:" . $this->_tz . "\r\n";
		$head .= "X-LIC-LOCATION:" . $this->_tz . "\r\n";
		$head .= "BEGIN:STANDARD\r\n";
		$head .= "TZOFFSETFROM:" . $this->_tz_offset . "\r\n";
		$head .= "TZOFFSETTO:" . $this->_tz_offset . "\r\n";
		$head .= "TZNAME:" . $this->_tz_name . "\r\n";
		$head .= "DTSTART:19700101T000000\r\nEND:STANDARD\r\nEND:VTIMEZONE\r\n";
		$tail = "END:VCALENDAR";
		$write = $head . $this->_export . $tail;
		file_put_contents($file, $write);
	}

	private function _create_DTSTAMP(){
		$date = date('Ymd his');
		$dtstamp = str_replace(' ', 'T', $date);
		return $dtstamp;
	}

}
