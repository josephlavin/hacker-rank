<?php 
require 'MyPaydateCalculator.php';

try {
	$calc = new MyPaydateCalculator();
	// Setup some test cases and loop through them.
	// requirements wanted:
	// - Return next 10 paydates from today, given the next paydate and a paydate model
	// - Generated paydates are the NEXT 10 paydates (from today)
	// so the test cases below calculate from today, return 10 results for each paydate model
	$tests = array();
	$tests[] = array('m' => 'WEEKLY', 'd' => date('Y-m-d'), 'n' => 10);
	$tests[] = array('m' => 'BIWEEKLY', 'd' => date('Y-m-d'), 'n' => 10);
	$tests[] = array('m' => 'MONTHLY', 'd' => date('Y-m-d'), 'n' => 10);

	foreach ($tests as $key => $t) {
		echo 'Calculating the next '.$t['n'].' '.$t['m'].' paydays starting after date '.$t['d'].'<br>'."\n";
		var_dump($calc->calculateNextPaydates($t['m'], $t['d'], $t['n']));
	}

} catch (Exception $e) {
	echo 'Message: ' .$e->getMessage();
}
