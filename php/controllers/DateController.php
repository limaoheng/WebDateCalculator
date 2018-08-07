<?php 

class DateController {
	
	
	/**
	 * Calculate related information from two dates 
	 * 
	 * @return 
	 * {
	 * 	'days':  $days;
	 *  'weeks': $weeks;
	 *  'weekdays': $weekdays;
	 * }
	 */
	function calculateDate() {
		$startDate 			= $_POST['startDate'];
		$endDate 			= $_POST['endDate'];
		$startTimeZone 		= $_POST['startTimeZone'] !== undefined && !empty($_POST['startTimeZone']) ? $_POST['startTimeZone'] : 'UTC';
		$endTimeZone 		= $_POST['endTimeZone'] !== undefined && !empty($_POST['endTimeZone']) ? $_POST['endTimeZone'] : 'UTC';
		
		// Do some validation
		if (!$this->__validateDateInput($startDate) || !$this->__validateDateInput($endDate) ) {
			throw new Exception("Please input correct date. Input start date: $startDate, and input end date: $endDate");
		}
		
		if ($startDate > $endDate) {
			throw new Exception("Please make sure the start date is earlier than the end date. Input start date: $startDate, and input end date: $endDate");
		}
		
		if (!$this->__validateTimeZoneInput($startTimeZone) || !$this->__validateTimeZoneInput($endTimeZone) ) {
			throw new Exception("Please input correct time zone: start timezone: $startTimeZone and end timezone: $endTimeZone");
		}
		
		$startDateObj = new DateTime($startDate, new DateTimeZone($startTimeZone));
		$endDateObj = new DateTime($endDate, new DateTimeZone($endTimeZone));
		
		$dateCal = new DateCalculator($startDateObj, $endDateObj);
		$days = $dateCal->getDays();
		$weeks = $dateCal->getFullWeeks();
		$weekdays = $dateCal->getWeekdays();
		
		$result = array();		
		
		$result['days'] 		= $days;
		$result['weeks'] 		= $weeks;
		$result['weekdays'] 	= $weekdays;
		
		return $result;
	}
	
	function __validateDateInput($date) {
		if (empty($date)) {
			return false;
		}
		return !!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date);
	}
	
	function __validateTimeZoneInput($timezone) {
		
		return in_array($timezone, $this->getSupportedTimeZones()['supportedTimeZones']);
	}
	
	/**
	 * Get a list of PHP supported timezone names.
	 * @return string[]
	 */
	function getSupportedTimeZones() {
		$result = array();
		$result['supportedTimeZones'] = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
		return $result;
	}
}

?>