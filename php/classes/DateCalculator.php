<?php 

class DateCalculator {
	
	private $startDate;
	private $endDate;
	
	// Let's say Sydney got Friday, Saturday as weekend
	private $timezoneWeekendMap = array(
		'Australia/Sydney' => [5, 6]
	);
	
    function __construct($startDate, $endDate) {
    	$this->startDate = $startDate;
    	$this->endDate = $endDate;
    }
    
    function getDays() {
    	$interval = $this->startDate->diff($this->endDate);
    	$days = $interval->days;
    	return $days;
    }
    
    function getFullWeeks() {
    	return floor($this->getDays() / 7);
    }
    
    function getWeekdays() {
    	// All right, let's use the Math way to calculate the workdays.
    	// First, let's get the full weeks. This will be used later.
    	$fullWeeks = $this->getFullWeeks();
    	
    	// Then let's get the remaining days between this two dates.
    	$remainingDays = $this->getDays() % 7;
    	
    	$dayOfWeekOfStartDate = (int)$this->startDate->format("N");
    	$dayOfWeekOfEndDate = (int)$this->endDate->format("N");
    	
    	$primaryTimezone = $this->startDate->getTimezone()->getName();

    	// Here we need to calculate how many weekneds in the remaining days.
    	if ($dayOfWeekOfStartDate <= $dayOfWeekOfEndDate) {
    		
    		// We are not going to include the end date.
    		for ($i = $dayOfWeekOfStartDate; $i < $dayOfWeekOfEndDate; $i ++) {
    			if ($this->_isDayWeekend($i, $primaryTimezone) && $remainingDays > 0) {
    				$remainingDays --;
    			}
    		}
    	} else {
    		for ($i = $dayOfWeekOfStartDate; $i <= 7; $i ++) {
    			if ($this->_isDayWeekend($i, $primaryTimezone) && $remainingDays > 0) {
    				$remainingDays --;
    			}
    		}
    		
    		for ($i = 1; $i < $dayOfWeekOfEndDate; $i ++) {
    			if ($this->_isDayWeekend($i, $primaryTimezone)&& $remainingDays > 0) {
    				$remainingDays --;
    			}
    		}
    	}
    	$daysWithoutCalculatingHolidays = $fullWeeks * 5 + $remainingDays;
    	return $daysWithoutCalculatingHolidays;
    }
    
    function _isDayWeekend($day, $timezone = 'UTC') {
    	if (isset($this->timezoneWeekendMap[$timezone])) {
    		return in_array($day, $this->timezoneWeekendMap[$timezone]);
    	}
    	return ($day === 6 || $day === 7);
    }
    
    function getWeekdaysUsingLoop() {
    	$oneDayinterval = DateInterval::createFromDateString('1 day');
    	$period   = new DatePeriod($this->startDate, $oneDayinterval, $this->endDate);
    	
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