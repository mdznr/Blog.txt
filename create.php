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
	if ( isset($_POST["filename"]) && isset($_POST["content"]) ) {
		$dir = "posts";
		$filename = preg_replace("/[^a-zA-Z0-9\._-]/", "", $_POST["filename"]);
		if ( substr($filename, -4) != ".txt" ) {
			$filename .= ".txt";
		}
		$content = $_POST["content"];
		if ( file_exists( $dir . "/" . $filename . ".txt" ) ) {
			echo "File already exists.";
			return false;
		} else {
			$new = fopen( $dir . "/" . $filename, "w" );
			fwrite( $new,  $content);
		}
	}
?>
<!doctype html>
<html>
<head>
</head>
<body>
	<form name="newPost" action="create.php" method="post" enctype="multipart/form-data">
	
		<label for="filename">Filename: </label><br />
		<input name="filename" type="text" /><br />
		
		<label for="text">Content:</label><br />
		<textarea name="content"></textarea><br />
		
		<input type="submit" />
		
	</form>
</body>
</html>