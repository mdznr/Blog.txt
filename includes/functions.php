<?php
/*
	Copyright (c) 2012, Matt Zanchelli
	All rights reserved.

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:
		* Redistributions of source code must retain the above copyright
		  notice, this list of conditions and the following disclaimer.
		* Redistributions in binary form must reproduce the above copyright
		  notice, this list of conditions and the following disclaimer in the
		  documentation and/or other materials provided with the distribution.
		* Neither the name of the <organization> nor the
		  names of its contributors may be used to endorse or promote products
		  derived from this software without specific prior written permission.

	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
	ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
	WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
	DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
	(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
	ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
function checkPostDate($a)
{
	include("config.php");
	$file = file($a);
	if ( !strtotime($file[0]) ) {
		$old_content = file_get_contents($a);
		unlink($a);
		$date = "";
		if ( filemtime($a) ) {
			$date = date($dateFormat, filemtime($a));	//	The date based on last modification
		} elseif ( filectime($a) ) {
			$date = date($dateFormat, filectime($a));	//	The date based on time of creation?	
		} else {
			$date = date($dateFormat);	//	Today's date if there is no filemtime
		}
		$open = fopen($a, w);
		fwrite($open, $date . "\n" . $old_content);
		fclose($open);
	}
}

function recentPost($a, $b)	//	Reads post date from first line of .txt document
{
	checkPostDate($a);
	checkPostDate($b);

	$filea = file($a);
	$fileb = file($b);

	$timea = strtotime($filea[0]);
	$timeb = strtotime($fileb[0]);

	return $timea < $timeb;	// Returns true if post a is older
}

function curPageURL()
{
	$pageURL = 'http';

	if ( $_SERVER["HTTPS"] == "on" ) {
		$pageURL .= "s";
	}

	$pageURL .= "://";

	if ( $_SERVER["SERVER_PORT"] != "80" ) {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}

	return $pageURL;
}

?>