<?php
$_fp = fopen("php://stdin", "r");

/* Enter your code here. Read input from STDIN. Print output to STDOUT */

$num_children = (int)fgets($_fp); // First line
$score = array(1 => (int)fgets($_fp)); // position => score
$candies = array( 1 => 1); // position => # candies given

// First loop through score comparing "left to right"
$i = 2; // Start off loop in second position, first position already loaded
while ($i <= $num_children) {
	// ready score from file and give one candy
	$score[$i] = (int)fgets($_fp);
	$candies[$i] = 1;

	if($score[$i] != $score[$i-1]) {
		// The scores are different, check for a scenario that should not occur
		if($score[$i] < $score[$i-1] && $candies[$i] >= $candies[$i-1]) {
			$candies[$i-1]=$candies[$i]+1;
		}
		elseif($score[$i] > $score[$i-1] && $candies[$i] <= $candies[$i-1]) {
			$candies[$i]=$candies[$i-1]+1;
		}
	}

	$i++;
}

// Now loop though scores in reverse order, comparing "right to left"
$i = $num_children;
while ($i > 1) {
	if($score[$i] != $score[$i-1]) {
		// The scores are different, check for a scenario that should not occur
		if($score[$i] < $score[$i-1] && $candies[$i] >= $candies[$i-1]) {
			$candies[$i-1]=$candies[$i]+1;
		}
		elseif($score[$i] > $score[$i-1] && $candies[$i] <= $candies[$i-1]) {
			$candies[$i]=$candies[$i-1]+1;
		}
	}
	$i--;
}

// sum up all the candies given out
echo array_sum($candies);
?>