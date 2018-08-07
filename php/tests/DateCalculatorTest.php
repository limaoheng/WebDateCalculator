<?php 

use PHPUnit\Framework\TestCase;

class DateCalculatorTest extends TestCase {
	
	public function testGetDaysNoTimeZone() {
		$startDate = new DateTime('2018-08-01');
		$endDate = new DateTime('2018-08-02');
		$dateCalculator = new DateCalculator($startDate, $endDate);
		$days = $dateCalculator->getDays();
		
		$this->assertEquals(1, $days);
	}
	
	public function testGetDaysStartDateGreaterThanEndDate() {
		$this->expectException(Exception::class);
		$endDate = new DateTime('2018-08-01');
		$startDate= new DateTime('2018-08-02');
		new DateCalculator($startDate, $endDate);
	}
	
	public function testGetDaysWithDifferentTimeZone() {
		
		// These should be on the same day.
		$startDate = new DateTime('2018-08-01', new DateTimeZone("America/Denver"));
		$endDate = new DateTime('2018-08-02', new DateTimeZone("Australia/Adelaide"));

		$dateCalculator = new DateCalculator($startDate, $endDate);
		$days = $dateCalculator->getDays();
		
		$this->assertEquals(0, $days);
	}
	
	public function testGetFullWeeksLessThanOne() {
		$startDate = new DateTime('2018-08-01');
		$endDate = new DateTime('2018-08-02');
		$dateCalculator = new DateCalculator($startDate, $endDate);
		$weeks = $dateCalculator->getFullWeeks();
		
		$this->assertEquals(0, $weeks);
	}
	
	public function testGetFullWeeksLessThanTwo() {
		$startDate = new DateTime('2018-08-01');
		$endDate = new DateTime('2018-08-09');
		$dateCalculator = new DateCalculator($startDate, $endDate);
		$weeks = $dateCalculator->getFullWeeks();
		
		$this->assertEquals(1, $weeks);
	}
	
	// ================ Test Holidays ======================
	// Since currently, Adelaide is the only city having public holiday, let's use that!
	
	public function testGetPublicHolidayInRange() {
		$startDate = new DateTime('2018-01-01', new DateTimeZone("Australia/Adelaide"));
		$endDate = new DateTime('2018-01-31', new DateTimeZone("Australia/Adelaide"));
		$dateCalculator = new DateCalculator($startDate, $endDate);
		
		$countOfPublicHolidays = $dateCalculator->_getPublicHolidaysBetweenDates($startDate, $endDate);
		$this->assertEquals(1, $countOfPublicHolidays);
	}
	
	public function testGetPublicHolidayWhenOnWeekend() {
		
		// 2018-03-31 is a Saturday, hence 3 public holidays shrink to 2.
		$startDate = new DateTime('2018-03-29', new DateTimeZone("Australia/Adelaide"));
		$endDate = new DateTime('2018-04-15', new DateTimeZone("Australia/Adelaide"));
		$dateCalculator = new DateCalculator($startDate, $endDate);
		
		$countOfPublicHolidays = $dateCalculator->_getPublicHolidaysBetweenDates($startDate, $endDate);
		$this->assertEquals(2, $countOfPublicHolidays);
	}
	
	// ================ Test get weekdays ==================

	public function testGetWorkdaysRangeLessThanAWeek() {
		$startDate = new DateTime('2018-08-01');
		$endDate = new DateTime('2018-08-07');
		$dateCalculator = new DateCalculator($startDate, $endDate);
		$weekdays = $dateCalculator->getWeekdays();
		
		$this->assertEquals(4, $weekdays);
	}
	
	public function testGetWorkdaysStartDateOnSaturdayAndEndDateOnSunday() {
		$startDate = new DateTime('2018-08-04');
		$endDate = new DateTime('2018-08-12');
		$dateCalculator = new DateCalculator($startDate, $endDate);
		$weekdays = $dateCalculator->getWeekdays();
		
		$this->assertEquals(5, $weekdays);
	}
	
	public function testGetWorkdaysWithSameDay() {

		$startDate = new DateTime('2018-08-13');
		$endDate = new DateTime('2018-08-13');
		$dateCalculator = new DateCalculator($startDate, $endDate);
		$weekdays = $dateCalculator->getWeekdays();
		
		$this->assertEquals(0, $weekdays);
	}
	
	public function testGetWorkdaysJumpingMonth() {
		$startDate = new DateTime('2018-02-26');
		$endDate = new DateTime('2018-03-04');
		$dateCalculator = new DateCalculator($startDate, $endDate);
		$weekdays = $dateCalculator->getWeekdays();
		
		$this->assertEquals(5, $weekdays);
	}
	
	public function testGetWorkdaysJumpingYears() {
		$startDate = new DateTime('2017-02-26');
		$endDate = new DateTime('2020-03-26');
		$dateCalculator = new DateCalculator($startDate, $endDate);
		$weekdays = $dateCalculator->getWeekdays();
		
		$this->assertEquals($dateCalculator->getWeekdaysUsingLoop(), $weekdays);
	}
	
	public function testGetWorkdaysWithSpecialWeekend() {

		// In current demonstration version, Sydney has been made as a special case for weekend.
		// The weekend has been made while Friday and Saturday.
		// Since it's the start date, Sydney will be used as the primary timezone during calculation.
		$startDate = new DateTime('2018-08-03', new DateTimeZone("Australia/Sydney"));
		$endDate = new DateTime('2018-08-13', new DateTimeZone("Australia/Adelaide"));
		$dateCalculator = new DateCalculator($startDate, $endDate);
		$weekdays = $dateCalculator->getWeekdays();
		
		$this->assertEquals(6, $weekdays);
	}
	
	public function testGetWorkdaysWithPublicHolidaysInRange() {
		$startDate = new DateTime('2018-01-01', new DateTimeZone("Australia/Adelaide"));
		$endDate = new DateTime('2018-01-02', new DateTimeZone("Australia/Adelaide"));
		$dateCalculator = new DateCalculator($startDate, $endDate);
		$weekdays = $dateCalculator->getWeekdays();
		
		$this->assertEquals(0, $weekdays);
	}
	
	public function testGetWorkdaysWithPublicHolidaysOnWeekend() {
		$startDate = new DateTime('2018-03-28', new DateTimeZone("Australia/Adelaide"));
		$endDate = new DateTime('2018-04-07', new DateTimeZone("Australia/Adelaide"));
		$dateCalculator = new DateCalculator($startDate, $endDate);
		$weekdays = $dateCalculator->getWeekdays();
		
		$this->assertEquals(6, $weekdays);
	}
	
	// Let's try some rediculous data
	//public function testGetWorkdaysWithRediculousData() {
	//	$startDate = new DateTime('0000-03-26', new DateTimeZone("Australia/Adelaide"));
	//	$endDate = new DateTime('9999-03-26', new DateTimeZone("Australia/Adelaide"));
	//	
	//	$dateCalculator = new DateCalculator($startDate, $endDate);
	//	$weekdays = $dateCalculator->getWeekdays();
		
	//	$this->assertNotEquals($dateCalculator->getWeekdaysUsingLoop(), $weekdays);
	//}
	
}

?>
