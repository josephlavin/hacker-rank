<?php
/* Head ends here */
/**
 * Given N integers, count the total pairs of integers that have
 * a difference of K.
 * 
 * @param array $numbers     contains array of numbers in an array
 * @param int   $difference  the numeric value which is the difference
 */

/**
 * paris
 * $numbers: contains array of numbers in an array
 * $difference: the numeric value which is the difference
 */
function pairs( $numbers, $difference) {
	// Sort the items from high to low
	rsort($numbers, SORT_NUMERIC);

	// $hm = Hash Map
	$hm = array();
	$count_of_difference = 0;

	foreach ($numbers as $pos => $num) {
		// Only need to search for difference when adding to this number
		// becuase the list is sorted from high to low
		if( isset($hm[($num + $difference)]) ) {
			$count_of_difference++;
		}

		// Add this value to the hash map
		// just set they key as the num which allows us to use 
		// isset() (rather than in_array which is slower)
		$hm[$num] = 1;
	}

	return $count_of_difference;
}
// if(!headers_sent()) header('Content-Type: text/plain');

/* Tail starts here */
// $__fp = fopen(dirname(__FILE__)."/test_input.txt", "r");
$__fp = fopen("php://stdin", "r");

fscanf($__fp, "%d %d", $_a_cnt, $_k);
$_a = trim(fgets($__fp));

$_a = @split(' ',$_a);
$res = pairs($_a,$_k);
echo "Andswer=$res\n";

?>