*This is not a hacker rank problem but another programming challenge I solved*

# PaydateCalculator

- Write a class in PHP5 OOP named MyPaydateCalculator
- Must be able to run without syntax errors or warnings
- Must implement the interface PaydateCalculatorInterface.php (see below) 
- Return next 10 paydates from today, given the next paydate and a paydate model
- Generated paydates are the NEXT 10 paydates (from today) 

## Rules

- 4 hr time limit
- A valid paydate is a date that is neither a holiday or a weekend.
- If a paydate falls on a weekend, increase date until a valid date is reached.
- If a paydate falls on a holiday, decrease date until a valid date is reached.
- Holiday adjustment takes precedence over weekend adjustment
- The two given paydates given to your class will not be adjusted by weekends or a holiday
- The "next" paydate cannot be today
- Generated paydates need to be the next paydates (from today forward)

~~~php
$holidays = ['01-01-2014','20-01-2014','17-02-2014','26-05-2014','04-07-2014','01-09-2014','13-10-2014','11-11-2014','27-11-2014','25-12-2014','01-01-2015','19-01-2015','16-02-2015','25-05-2015','03-07-2015','07-09-2015','12-10-2015','11-11-2015','26-11-2015','25-12-2015'];
~~~ 

### Paydate Models

- **MONTHLY**: A person is paid on the same day of the month every month, for instance, 1/17/2012 and 2/17/2012
- **BIWEEKLY**: A person is paid on the same day of the week every other week, for instance, 4/6/2012 and  4/20/2012
- **WEEKLY**: A person is paid on the same day of the week every week, for instance 4/9/2012 and 4/16/2012

### Interface

The class should implement the following interface:

~~~php

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

~~~
