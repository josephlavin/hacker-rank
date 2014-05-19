<?php
/**
 * https://www.hackerrank.com/challenges/stockmax
 *
 * MAIN IDEA
 * create an array of the highest values.. this tells us what days to sell on 
 * and what it took to buy all shares to get to that point
 */

// Test cases provided via. stdin
$_fp = fopen("php://stdin", "r");

// First line is # of test cases
$num_tests = (int)fgets($_fp);

$curr_test_num = 1;
while ($curr_test_num <= $num_tests) {
	// First line in test = number of days
	$num_days = (int)fgets($_fp);

	// Second line in test = all the values of stocks for each day
	$prices = array_map('intval', explode(' ', fgets($_fp)));

	// Sort this array by highest to lowest, so we know the days when we 
	// should sell first
	$sorted_prices = $prices;
	arsort($sorted_prices, SORT_NUMERIC);

	// need to clean up days where the price is the same
	// only need to preserve the LAST occurance (day wise, highest key #)
	// of that high price as that is the day we will be selling shares for
	// maximum profit!
	$prev_day = 0;
	$prev_price = 0;
	foreach ($sorted_prices as $day => $price) {
		// if we have the same price in a row, we only care about the last one..
		// so unset the previous day from the sorted array
		if($prev_price == $price) {
			$remove_day = 0;
			// figure out which day to remove, we only care about the highest #
			// PHP arsort does preserve key, but it does not accurately sort them
			// from high/low when values are the same
			if($prev_day > $day) {
				$remove_day = $day;

				// Now we want to compare the next entry againsts the one kept
				$prev_day = $prev_day;
				$prev_price = $prev_price;
			}
			else {
				$remove_day = $prev_day;

				// Now we want to compare the next entry againsts the one kept
				$prev_day = $day;
				$prev_price = $price;
			}

			// Remove from regular sorted array as this will
			unset($sorted_prices[$remove_day]);
		}
		else {
			// we have a different price, set vars for comparison
			$prev_day = $day;
			$prev_price = $price;
		}
	}

	// Now start the buying / selling
	$current_day = 0;
	$monies = 0;
	foreach ($sorted_prices as $high_price_day_num => $high_price) {
		if( $current_day == $high_price_day_num ) {
			// means the next highest price occurs on the next day
			// buying & selling would do nothing.. so skip
			$current_day++;
		}

		if( $current_day > $high_price_day_num ) {
			// We are looking @ a day which is past our previous high date, 
			// so ignore but we still need to advance the day
			continue;
		}

		// BUY & SELL!
		// create new array which is current day to day right before the high 
		// price day 

		// Determine array starting position of slice (current day)
		$start_pos = array_search($current_day, array_keys($prices));

		// Determine array ending position (highest day)
		$end_pos =  array_search($high_price_day_num, array_keys($prices));

		// SLICE!
		$purchasing_prices = array_slice($prices, $start_pos, ($end_pos - $start_pos));

		// count($purchasing_prices) = the # of shares
		// array_sum($purchasing_prices) = the amount we paid to get to buy # of shares
		// Purchasing multiple days @ once.. AND selling them all @ todays price
		$monies += (count($purchasing_prices) * $high_price) - (array_sum($purchasing_prices));

		// now advanced to the next day, after the high price and see if we have
		// another high price to buy shares at
		$current_day = $high_price_day_num+1;
	}

	// Completed the calculations, output what monies we have made
	echo $monies."\n";
	$curr_test_num++;
}
?>