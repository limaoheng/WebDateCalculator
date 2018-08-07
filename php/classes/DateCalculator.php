<?php 

class DateCalculator {
	
	private $startDate;
	private $endDate;
	
	// For demonstration purpose, let's say Sydney got Friday, Saturday as weekend
	private $timezoneWeekendMap = array(
		'Australia/Sydney' => [5, 6]
	);
	
	// Again, for demonstration purpose, I hardcoded the public holiday for Adelaide in.
	// In real life, this should be something getting from some web service or library or in configuration.
	private $publicHolidays = array(
		'Australia/Adelaide' => array(
			'01-01',
			'02-26',
			'03-12',
			'03-30',
			'03-31',
			'04-02',
			'04-25',
			'06-11',
			'10-01',
			'12-24',
			'12-25',
			'12-26',
			'12-31'
		)
	);
	
    function __construct($startDate, $endDate) {
    	if (isset($startDate) && isset($endDate) && $startDate <= $endDate) {
    		$this->startDate = $startDate;
    		$this->endDate = $endDate;
    	} else {
    		throw new Exception("Start Date and End Date given is not correct.");
    	}
    }
    
    /**
     * Get days between the dates given. Remember, the end date is not count.
     * 
     * @return number
     */
    function getDays() {
    	$interval = $this->startDate->diff($this->endDate);
    	$days = $interval->days;
    	return $days;
    }
    
    /**
     * Get the full week between the dates.
     * 
     * @return number
     */
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
    		
    		// We are NOT going to include the end date.
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
    	
    	// Now let's get the holidays
    	$holidayCounts = $this->_getPublicHolidaysBetweenDates($this->startDate, $this->endDate);
    	
    	$finalResult = $daysWithoutCalculatingHolidays - $holidayCounts;
    	return $finalResult;
    }
    
    /**
     * To find out whether the day given is a weekend or not. Depending on the timezone, the weekend
     * might not be Saturday, Sunday.
     * 
     * @param number $day
     * @param string $timezone
     * @return boolean
     */
    function _isDayWeekend($day, $timezone = 'UTC') {
    	if (isset($this->timezoneWeekendMap[$timezone])) {
    		return in_array($day, $this->timezoneWeekendMap[$timezone]);
    	}
    	return ($day === 6 || $day === 7);
    }
    
    /**
     * This function is going to find the public holiday from the configuration between the dates.
     * 
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return number | the total number of the holiday
     */
    function _getPublicHolidaysBetweenDates($startDate, $endDate) {
    	
    	// We need one timezone as the primary timezone to calculate the holidays
    	$primaryTimezone = $startDate->getTimezone()->getName();
		$count = 0;
		
    	if (isset($this->publicHolidays[$primaryTimezone])) {
    		$publicHolidays = $this->publicHolidays[$primaryTimezone];
    		$startYear = (int) $startDate->format('Y');
    		$endYear = (int) $endDate->format('Y');

    		// This will need to be looped over, since we may not have same holidays every year.
    		// But in current demonstration version, every year is the same.
    		for ($i = $startYear; $i <= $endYear; $i ++) {
    			foreach ($publicHolidays as $ph) {

    				// Here, since we are not working with real data yet, let's assume every year, the holiday
    				// date is the same. In real world, this would be calling some API with year, timezone as 
    				// parameters.
    				$ph = $i . '-' . $ph;
    				$publicHolidayObj = new DateTime($ph, new DateTimeZone($primaryTimezone));
    				$dayOfWeek = (int) $publicHolidayObj->format("N");
    				
    				// If the holiday is between the dates given, and it's not a weekend, we count it.
    				if ($startDate <= $publicHolidayObj && $publicHolidayObj < $endDate && !$this->_isDayWeekend($dayOfWeek, $primaryTimezone)) {
    					$count ++;
    				}
    			}
    		}
    	}
    	return $count;
    }
    
    /**
     * Deprecated implementation of calculating weekdays using loop.
     * 
     * @return number
     */
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