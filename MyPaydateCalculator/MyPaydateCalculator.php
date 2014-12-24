<?php 
interface PaydateCalculatorInterface {
 
    /**
     * This function takes a paydate model and two paydates and generates the next $number_of_paydates paydates. 
     *
     * @param string $paydate_model The paydate model, one of the items in the spec
     * @param string $paydate_one   An example paydate as a string in Y-m-d format, different from the second
     * @param int $number_of_paydates The number of paydates to generate
     *
     * @return array the next paydates (from today) as strings in Y-m-d format
     */
    public function calculateNextPaydates($paydate_model, $paydate_one, $number_of_paydates);
 
    /**
     * This function determines whether a given date in Y-m-d format is a holiday.
     *
     * @param string $date A date as a string formatted as Y-m-d
     *
     * @return boolean whether or not the given date is on a holiday
     */
    public function isHoliday($date);
 
    /**
     * This function determines whether a given date in Y-m-d format is on a weekend.
     *
     * @param string $date A date as a string formatted as Y-m-d
     *
     * @return boolean whether or not the given date is on a weekend
     */
    public function isWeekend($date);
 
    /**
     * This function determines whether a given date in Y-m-d format is a valid paydate according to specification rules.
     *
     * @param string $date A date as a string formatted as Y-m-d
     *
     * @return boolean whether or not the given date is a valid paydate
     */
    public function isValidPaydate($date);
 
    /**
     * This function increases a given date in Y-m-d format by $count $units
     *
     * @param string $date A date as a string formatted as Y-m-d
     * @param integer $count The amount of units to increment
     *
     * @return string the calculated day's date as a string in Y-m-d format
     */
    public function increaseDate($date, $count, $unit = 'days');
 
    /**
     * This function decreases a given date in Y-m-d format by $count $units
     *
     * @param string $date A date as a string formatted as Y-m-d
     * @param integer $count The amount of units to decrement
     *
     * @return string the calculated day's date as a string in Y-m-d format
     */
    public function decreaseDate($date, $count, $unit = 'days');
 
}

/**
* Class created for coding challenge.
* Calculate the next paydays
* 
* @author Joseph Lavin <josephlavin@gmail.com>
*/
class MyPaydateCalculator implements PaydateCalculatorInterface
{
    private $unit = null;
    private $count = null;

	/**
     * This function takes a paydate model and two paydates and generates the next $number_of_paydates paydates. 
     *
     * @param string $paydate_model The paydate model, one of the items in the spec
     * @param string $paydate_one   An example paydate as a string in Y-m-d format, different from the second
     * @param int $number_of_paydates The number of paydates to generate
     *
     * @return array the next paydates (from today) as strings in Y-m-d format
     */
    public function calculateNextPaydates($paydate_model, $paydate_one, $number_of_paydates)
    {
        if(!$this->isValidDate($paydate_one)) {
            throw new Exception("non valid date given");
        }

        // cast as int and make sure a valid number is provided
        $number_of_paydates = (int)$number_of_paydates;
        if($number_of_paydates <= 0) {
            throw new Exception("invalid number_paydates given");
        }

        // Make sure the first paydate given is a valid paydate
        // enforce rule: * A valid paydate is a date that is neither a holiday or a weekend.
        if (!$this->isValidPaydate($paydate_one)) {
            throw new Exception("Initial Paydate is not valid");
        }

        // Set the class properties
        $this->setUnitAndCount($paydate_model);

        $ret = array();
        $i = 0;
        $current_paydate = $paydate_one;
        while ($i < $number_of_paydates) {
            // Adjusted paydates do not effect when the next paydate should occur
            // it only effects that specific paydate
            $adjusted_paydate = $current_paydate = $this->increaseDate($current_paydate, $this->count, $this->unit);

            if(!$this->isValidPaydate($adjusted_paydate)) {
                $adjusted_paydate = $this->adjustPaydate($adjusted_paydate);
            }

            $ret[] = $adjusted_paydate;
            $i++;
        }

        return $ret;

    }
 
    /**
     * This function determines whether a given date in Y-m-d format is a holiday.
     *
     * @param string $date A date as a string formatted as Y-m-d
     *
     * @return boolean whether or not the given date is on a holiday
     */
    public function isHoliday($date)
    {
        if(!$this->isValidDate($date)) {
            throw new Exception("non valid date given");
        }

        // holidays are hard coded & provided in challenge specs
        // manually re-formatted dates from d-m-Y to Y-m-d format to match app date specs 
        // used this loop for quick reformatting:
        // foreach ($holidays as $key => $date) {   echo "'".date('Y-m-d', strtotime($date))."', "; }
    	$holidays = ['2014-01-01', '1970-01-01', '2014-02-17', '2014-05-26', '2014-07-04', '2014-09-01', '2014-10-13', '2014-11-11', '2014-11-27', '2014-12-25', '2015-01-01', '2015-01-19', '2015-02-16', '2015-05-25', '2015-07-03', '2015-09-07', '2015-10-12', '2015-11-11', '2015-11-26', '2015-12-25'];

        return in_array($date, $holidays);
    }
 
    /**
     * This function determines whether a given date in Y-m-d format is on a weekend.
     *
     * @param string $date A date as a string formatted as Y-m-d
     *
     * @return boolean whether or not the given date is on a weekend
     */
    public function isWeekend($date)
    {    	
    	if(!$this->isValidDate($date)) {
    		throw new Exception("non valid date given");
    	}
    	
    	// ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0)
    	$day_of_week = date('N', strtotime($date));

    	// 6 = Saturday, 7 = Sunday
    	return ($day_of_week == 6 || $day_of_week == 7);
    }
 
    /**
     * This function determines whether a given date in Y-m-d format is a valid paydate according to specification rules.
     *
     * @param string $date A date as a string formatted as Y-m-d
     *
     * @return boolean whether or not the given date is a valid paydate
     */
    public function isValidPaydate($date)
    {
        // is it a valid looking date?
        if(!$this->isValidDate($date)) {
            throw new Exception("non valid date given");
        }

        // is it a holiday?
        if($this->isHoliday($date)) {
            return false;
        }
        // is it a weekend
        if($this->isWeekend($date)) {
            return false;
        }

        // passed all tests, so it is a valid pay date
        return true;
    }
 
    /**
     * This function increases a given date in Y-m-d format by $count $units
     *
     * @param string $date A date as a string formatted as Y-m-d
     * @param integer $count The amount of units to increment
     *
     * @return string the calculated day's date as a string in Y-m-d format
     */
    public function increaseDate($date, $count, $unit = 'days')
    {
        if(!$this->isValidDate($date)) {
            throw new Exception("non valid date given in increaseDate");
        }
        
        $count = (int)$count;
        if($count <= 0) {
            throw new Exception("invalid count given in increaseDate");
        }

        // use DateInterval & DateTime class (>= 5.3.0) 
        switch ($unit) {
            case 'months':
                // need to account for the short months
                // this sets the next month to the previous last day of the last month
                // however it means the users next paycheck will be calculated for the returned day
                // they will eventually get stuck on the 28th if given date is 28/29/30/31
                // provided guidelines did not specify how to deal w/ this case
                // however common payroll practices typically do not pay on 28/29/30/31 to avoid this issue
                // thus this code will adjust and eventually set the user on the 28th, but try its best to pay out
                // on provided day until this short month is encountered
                $d = new DateTime($date);
                $day = $d->format('j');
                $d->add(new DateInterval('P'.$count.'M'));

                if($d->format('j') != $day) {
                    $d->modify('last day of last month');
                }

                return $d->format('Y-m-d');
                break;

            case 'days':
                $d = new DateTime($date);
                $d->add(new DateInterval('P'.$count.'D'));
                return $d->format('Y-m-d');
                break;
            
            default:
                throw new Exception("non supported unit given to increaseDate");
                break;
        }


    }
 
    /**
     * This function decreases a given date in Y-m-d format by $count $units
     *
     * @param string $date A date as a string formatted as Y-m-d
     * @param integer $count The amount of units to decrement
     *
     * @return string the calculated day's date as a string in Y-m-d format
     */
    public function decreaseDate($date, $count, $unit = 'days')
    {
        if(!$this->isValidDate($date)) {
            throw new Exception("non valid date given");
        }
        
        $count = (int)$count;
        if($count <= 0) {
            throw new Exception("invalid count given");
        }

        // use DateInterval & DateTime class (>= 5.3.0) 
        switch ($unit) {
            case 'months':
                // never subtracting months (only days)
                // no need to worry about edge case of short months here
                $interval = new DateInterval('P'.$count.'M');
                break;

            case 'days':
                $interval = new DateInterval('P'.$count.'D');
                break;
            
            default:
                throw new Exception("non supported unit given to increaseDate");
                break;
        }

        $d = new DateTime($date);
        $d->sub($interval);
        return $d->format('Y-m-d');
    }

    /**
     * This function adjusts the provided date depending to the next valid paydate
     * following provided business rules as defined in the challenge
     * 
     * @param string $date A date as a string formatted as Y-m-d
     * 
     * @return string the adjusted paydate
     */
    public function adjustPaydate($date)
    {
        if(!$this->isValidDate($date)) {
            throw new Exception("non valid date given in adjustPaydate");
        }

        while (!$this->isValidPaydate($date)) {
            // If a paydate falls on a holiday, decrease date until a valid date is reached.
            // Holiday adjustment takes precedence over weekend adjustment 
            if($this->isHoliday($date)) {
                $date = $this->decreaseDate($date, 1, 'days');
                // check to see if holiday fell on a Sunday or Monday and adjust for weekend
                // checking here to avoid infinite loop of holiday -1 followed by weekend +1
                // keep decrementing until it is no longer the weekend
                while ($this->isWeekend($date)) {
                    $date = $this->decreaseDate($date, 1, 'days');
                }
            }
            // If a paydate falls on a weekend, increase date until a valid date is reached.
            elseif($this->isWeekend($date)) {
                $date = $this->increaseDate($date, 1, 'days');
            }
            else {
                // This should NEVER happen, simply last measure infinite loop protection
                die('FATAL ERROR: adjustPaydate infinite loop');
            }
        }

        return $date;
    }

    /**
     * util function to validate the date format, make sure it matches Y-m-d format
     * 
     * @param string $date A date as a string formatted as Y-m-d
     *
     * @return boolean whether or not given date matches format
     **/
    private function isValidDate($date)
    {
        // run a preg match to make sure all items are digits
        // Y = A full numeric representation of a year, 4 digits
        // m = Numeric representation of a month, with leading zeros
        // d = Day of the month, 2 digits with leading zeros
        if((bool)preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $date) == true) {
            // make sure it can be translated into a valid time string
            // meaning the date actually exists and its not something like 2014-07-33
            return (bool)strtotime($date);
        }

        return false;
    }

    /**
     * helper function to set class properties
     * 
     * @param string the valid paydate model
     * @return null
     */
    private function setUnitAndCount($paydate_model)
    {
        // determine until and count from the payout model given
        switch ($paydate_model) {                
            case 'WEEKLY':
                $this->unit = 'days';
                $this->count = 7;
                break;
            
            case 'BIWEEKLY':
                $this->unit = 'days';
                $this->count = 14;
                break;

            case 'MONTHLY':
                $this->unit = 'months';
                $this->count = 1;
                break;

            default:
                throw new Exception("Invalid paydate_model give");            
                break;
        }
    }
}
