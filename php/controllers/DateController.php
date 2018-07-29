<?php 

class DateController extends Controller {
	
	
	/**
	 * Calculate related information from two dates 
	 * 
	 * @return NULL[]
	 */
	function calculateDate() {
		$startDate = $_POST['startDate'];
		$endDate = $_POST['endDate'];
		
		// Do some validation
		if (empty($startDate)) {
			return;
		}
		
		if (empty($endDate)) {
			return;
		}
		
		$startDateObj = new DateTime($startDate);
		$endDateObj = new DateTime($endDate);
		
		$result = array();
		$interval = $startDateObj->diff($endDateObj);
		
		$result['days'] 		= $interval->days;
		$result['weeks'] 		= floor($interval->days / 7);
		
		// For some reason, the property weekdays in DateInterval can't get set. Hence have to do it myself.
		$result['weekdays'] 	= $this->__getWeekdays($startDateObj, $endDateObj); 
		
		return $result;
	}
	
	/**
	 * Get the weekdays from two dates.
	 * 
	 * @param unknown $startDateObj
	 * @param unknown $endDateObj
	 */
	function __getWeekdays($startDateObj, $endDateObj) {
		
		$oneDayinterval = DateInterval::createFromDateString('1 day');
		$period   = new DatePeriod($startDateObj, $oneDayinterval, $endDateObj);
		
		$count = 0;
		
		foreach ($period as $dt) {

			// Exclude the Saturday and Sunday. If later we want to consider holiday, we can add some more complex rules in.
			if ($dt->format("N") === '6' || $dt->format("N") === '7') {
				continue;
			} else {
				$count++;
			}
		}
		return $count;
	}
}

?>