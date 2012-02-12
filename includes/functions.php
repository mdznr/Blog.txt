<?php

function recentPost($a, $b)	//	Reads post date from first line of .txt document
{
	$filea = file($a);
	$fileb = file($b);
	$timea = strtotime($filea[0]);
	$timeb = strtotime($fileb[0]);
	return $timea < $timeb;	// Returns true if post a is older
}

?>