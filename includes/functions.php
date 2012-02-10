<?php

function earlier($a, $b)
{
	return filemtime($a) < filemtime($b);
}

function earlierPost($a, $b)
{
	$filea = file($a);
	$fileb = file($b);
	$timea = strtotime($filea[0]);
	$timeb = strtotime($fileb[0]);
	return $timea < $timeb;
}

?>