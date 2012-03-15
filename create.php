<!--
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
-->
<?php
	if ( isset($_POST["filename"]) && isset($_POST["date"]) && isset($_POST["content"]) ) {	// Only works if all three fields are filled out!
		$dir = "posts";
		
		// $filename = preg_replace("/[^a-zA-Z0-9\._-]/", "", $_POST["filename"]);	// Eh, I'll fix that later
		$filename = $_POST["filename"];	//	(Temporary) Works with spaces
		
		if ( substr($filename, -4) != ".txt" ) {
			$filename .= ".txt";
		}
		if ( $filename == ".txt" ) {	//	Does not create blank '.txt' files
			echo "Must enter a filename!";
			return false;
		}
		$full = $dir . "/" . $filename;	// dir/filename.txt
		$date = $_POST["date"];
		$content = $_POST["content"];
		if ( file_exists( $full ) ) {	//	Overwrites existing file
			$existing = fopen( $full, w );
			fwrite ( $existing, $date . "\n" . substr($filename, 0, -4) . "\n" . $content );
			fclose($existing);
		} else {
			$new = fopen( $full, w );	//	Or creates a new file
			fwrite( $new,  $date . "\n" . substr($filename, 0, -4) . "\n" . $content );
			fclose($new);
		}
	}
?>
<!doctype html>
<html>
<head>
	<title>Edit</title>
	<script type="text/javascript">
		var bool = true;
		function edit(one, two, three, a, b, c, submit) {
			if (bool) {
				bool = false;
				document.getElementById(submit).style.display = "";
				
				//var elements = document.getElementsByClassName("original");
				//for (var i=0; i<elements.length; i++) {
				//	elements[i].style.display = "none";
				//}
				document.getElementById(one).style.display = "none";
				document.getElementById(two).style.display = "none";
				document.getElementById(three).style.display = "none";
				
				document.getElementById(a).style.display = "";
				document.getElementById(a).value = document.getElementById(one).innerHTML;
				
				document.getElementById(b).style.display = "";
				document.getElementById(b).value = document.getElementById(two).innerHTML;
				
				document.getElementById(c).style.display = "";
				document.getElementById(c).value = document.getElementById(three).innerHTML;
			}
			else {
				bool = true;
				document.getElementById(submit).style.display = "none";
				
				//var editables = document.getElementsByClassName("editable");
				//for (var i=0; i<editables.length; i++) {
				//	editables[i].style.display = "";
				//	editables[i].innerHTML = document.getElementById("a").value;
				//}
				
				document.getElementById(one).style.display = "";
				document.getElementById(one).innerHTML = document.getElementById(a).value;
				
				document.getElementById(two).style.display = "";
				document.getElementById(two).innerHTML = document.getElementById(b).value;
				
				document.getElementById(three).style.display = "";
				document.getElementById(three).innerHTML = document.getElementById(c).value;
				
				document.getElementById(a).style.display = "none";
				document.getElementById(b).style.display = "none";
				document.getElementById(c).style.display = "none";
			}
		}
		function textAreaAdjust(o) {
			o.style.height = "1px";
			o.style.height = (25+o.scrollHeight)+"px";
		}
	</script>
	<style>
		body {
			-webkit-font-smoothing: antialiased;
			font: 14px/1.8em "PT Serif", serif;
		}
		.original {
			width: 657.586px;
			padding: 3px;
		}
		textarea {
			padding: 0px;
			padding-left: 2px;
			width: 657.586px;
			font: inherit;
		}
	</style>
</head>
<body>
	<article id="0" onclick="edit('1', '2', '3', 'a', 'b', 'c', 'submit1')">
		<span class="date" id="1"><?php echo "Date & Time"; ?></span>
		<h1 class="title" id="2" class="original"><?php echo "File Name"; ?></h1>
		<div id="3" class="original"><?php echo "Content goes here"; ?></div>
	</article>
	
	<form name="newPost" action="create.php" method="post" enctype="multipart/form-data">
		<textarea id="a" class="editable" name="date" rows="1" style="display:none;resize:none;"></textarea><br />
		<textarea id="b" class="editable" name="filename" rows="1" style="display:none;resize:none;"></textarea><br />
		<textarea id="c" class="editable" name="content" style="display:none;"></textarea><br />
		<input id="submit1" class="editable" type="submit" value="Done" style="display:none;resize:resize;" onclick="edit()"/>
	</form>
	
	<article id="1" onclick="edit('4', '5', '6', 'd', 'e', 'f', 'submit2')">
		<div id="4" class="original"><?php echo "2Date & Time"; ?></div>
		<div id="5" class="original"><?php echo "2File Name"; ?></div>
		<div id="6" class="original"><?php echo "2Content goes here"; ?></div>
	</article>

	<form name="newPost" action="create.php" method="post" enctype="multipart/form-data">
		<textarea id="d" class="editable" name="date" rows="1" style="display:none;resize:none;"></textarea><br />
		<textarea id="e" class="editable" name="filename" rows="1" style="display:none;resize:none;"></textarea><br />
		<textarea id="f" class="editable" name="content" style="display:none;"></textarea><br />
		<input id="submit2" class="editable" type="submit" value="Done" style="display:none;resize:resize;" onclick="edit()"/>
	</form>
	
</body>
</html>