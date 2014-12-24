<?php
	/**
	 * Lonely Integer Coding Challange
	 * https://www.hackerrank.com/challenges/lonely-integer
	 * 
	 * https://www.hackerrank.com/challenges/lonely-integer/submissions/code/10337444
	 * @author JosephL <josephlavin@gmail.com>
	 */
	
	/**
	 * loneyinteger
	 * 
	 * @param array $a array of integers
	 * @return the integer that occurs once
	 */
	function lonelyinteger($a) {
		$single = array();
		$double = array();

		foreach ($a as $k => $v) {
			if( array_key_exists($v, $single) ) {
				unset($single[$v]);
				$double[$v] = true;
			}
			else {
				$single[$v] = true;
			}
		}

		// should only have one key in this array
		return key($single);
	}

	$__fp = fopen("php://stdin", "r");

	fscanf($__fp, "%d", $_a_cnt);
	$_a = fgets($__fp);
	$_a = split(' ',$_a);
	$res = lonelyinteger($_a);
	echo $res;
?>