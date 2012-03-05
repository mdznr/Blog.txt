<?php

function recentPost($a, $b)	//	Reads post date from first line of .txt document
{
	$filea = file($a);
	$fileb = file($b);
	$timea = strtotime($filea[0]);
	$timeb = strtotime($fileb[0]);
	return $timea < $timeb;	// Returns true if post a is older
}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

?>