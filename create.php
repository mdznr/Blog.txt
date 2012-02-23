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
	if ( isset($_POST["filename"]) && isset($_POST["date"]) && isset($_POST["content"]) ) {
		$dir = "posts";
		$filename = preg_replace("/[^a-zA-Z0-9\._-]/", "", $_POST["filename"]);
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
			fwrite ( $existing, substr($filename, 0, -4) . "\n" . date . "\n" . $content );
		} else {
			$new = fopen( $full, w );	//	Or creates a new file
			fwrite( $new,  substr($filename, 0, -4) . "\n" . $date . "\n" . $content );
		}
	}
?>
<!doctype html>
<html>
<head>
	<title>Edit</title>
	<script type="text/javascript">
		var bool = true;
		function edit() {
			var text;
			if (bool) {
				bool = false;
				document.getElementById("submit").style.display = "";
				
				document.getElementById("1").style.display = "none";
				document.getElementById("2").style.display = "none";
				document.getElementById("3").style.display = "none";
				
				document.getElementById("a").style.display = "";
				document.getElementById("a").value = document.getElementById("1").innerHTML;
				
				document.getElementById("b").style.display = "";
				document.getElementById("b").value = document.getElementById("2").innerHTML;
				
				document.getElementById("c").style.display = "";
				document.getElementById("c").value = document.getElementById("3").innerHTML;
			}
			else {
				bool = true;
				document.getElementById("submit").style.display = "none";
				
				document.getElementById("1").style.display = "";
				document.getElementById("1").innerHTML = document.getElementById("a").value;
				
				document.getElementById("2").style.display = "";
				document.getElementById("2").innerHTML = document.getElementById("b").value;
				
				document.getElementById("3").style.display = "";
				document.getElementById("3").innerHTML = document.getElementById("c").value;
				
				document.getElementById("a").style.display = "none";
				document.getElementById("b").style.display = "none";
				document.getElementById("c").style.display = "none";
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
			width: 512px;
			padding: 3px;
		}
		textarea {
			padding: 0px;
			padding-left: 2px;
			width: 512px;
			font: inherit;
		}
	</style>
</head>
<body>
	
	<div id="1" class="original" onclick="edit()"><?php echo "File"; ?></div>
	<div id="2" class="original" onclick="edit()"><?php echo "Date"; ?></div>
	<div id="3" class="original" onclick="edit()"><?php echo "Content goes here"; ?></div>
	
	<form name="newPost" action="create.php" method="post" enctype="multipart/form-data">
		<textarea id="a" name="filename" rows="1" style="display:none;resize:none;"></textarea><br />
		<textarea id="b" name="date" rows="1" style="display:none;resize:none;"></textarea><br />
		<textarea id="c" name="content" style="display:none;"></textarea><br />
		<input id="submit" type="submit" value="Done" style="display:none;resize:resize;" onclick="edit()"/>
	</form>
	
</body>
</html>