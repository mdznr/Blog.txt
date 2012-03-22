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

	// Is it necessary for linking to universal functions if there's only going to be one .php file using them? Hmm...
	include("includes/functions.php");
	include("config.php");

	if ( $_GET["p"] ) { $post = $_GET["p"]; }

	//	Posts Per Page
	if ( isset($_GET["postsPerPage"]) ) {	//	Overrides with URL arguments
		$postsPerPage = intval($_GET["postsPerPage"]);
		setcookie("postsPerPage", $postsPerPage);	//	Sets a cookie with the variable
	} elseif ( $_COOKIE["postsPerPage"] ) {	//	If there's a cookie
		$postsPerPage = intval($_COOKIE["postsPerPage"]);	//	sets local variable to the value stored in cookie (must convert to int)
	}
	if ( !(is_int($postsPerPage)) || $postsPerPage == 0 ) {	//	If they entered some invalid "number"
		// echo "NOT AN INTEGER!";
		$postsPerPage = 5;	//	Should just be their default setting
	}

	$page = 0;	//	Page # (Starts with 0)
	if ( $_GET["n"] ) { $page = $_GET["n"]; }	//	Overrides with URL arguments

	function calcOffset($postsPerPage, $page) {	//	Calculates the offset for loading posts
		$offset = $postsPerPage * $page;
		return $offset;
	}
	
	$offset = calcOffset($postsPerPage, $page);

	$posts = glob( $dir . '/*.txt' );	//	Only files that end in .txt in te $dir directory
	usort($posts, recentPost);	//	Sorts list of .txt files by their Date (recent first)

	//	For file uploading
	if ( isset($_FILES["file_upload"]) ) {
		$file = $_FILES['file_upload'];	// This is our file variable
		$name = $file['name'];
		$tmp = $file['tmp_name'];
		$size = $file['size'];
		$type = $file['type'];
		$max_size = 50 * 1024 * 1024;	// 50 megabytes 
		$upload_dir = $dir . '/';

		if(($size > 0) && ($type !== "text/php")) {
			if(!is_dir($upload_dir)){ echo $upload_dir . ' is not a directory'; }
			else if($size > $max_size){ echo 'The file you are trying to upload is too big.'; }
			else{
				if(!is_uploaded_file($tmp)){ echo 'Could not upload your file at this time, please try again'; }	
				else{
					if(!move_uploaded_file($tmp, $upload_dir . $name)){ echo 'Could not move the uploaded file.'; }
					else{ $message = $name . " was successfully uploaded!"; }	
				}
			}
		}
		elseif($type === "text/php"){ echo "You cannot upload that file here."; }
	}

?>
<!doctype html>
<html lang=en>
<head>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.4.1/build/cssreset/cssreset-min.css" />
<link rel="stylesheet" type="text/css" href="css/core.css" /> <!-- Regular stylesheet -->

<?php
	//	For custom CSS Styling
	if ( $_COOKIE["style"] ) {			//	If there's already a cookie
		$style = $_COOKIE["style"];		//	Retrieve that cookie
	}
	if ( $_GET["style"] ) {				//	Or if found in URL
		$style = $_GET["style"];		//	Set variable equal
		setcookie("style", $style);		//	Set cookie
	}
	
	if ( isset($style) ) {	//	If there's a style set, link it!
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/" . $style . ".css\" />";
	}
?>

<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery.snippet.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.snippet.min.css" />

<title><?php echo $title ?></title>

<link rel="alternate" type="application/rss+xml" title=<?php echo "\"" . $title . "\""; ?> href="/rss.php">

<?php echo "<meta name=\"keywords\" content=\"";
for ($i=0;$i<sizeof($keywords);$i++) {
	echo $keywords[$i] . ", ";	// echos each keyword with a comma following it
}
echo "\" />" ?>

<?php echo "<meta name=\"description\" content=\"" . $description . "\" />" ?>
<?php echo "<meta name=\"author\" content=\"" . $author . "\" />" ?>
<meta name="viewport" content="width=device-width; initial-scale=1.0; minimum-scale=1; maximum-scale=1; user-scalable=0" />	<!-- Especially helpful for mobile devices -->
</head>
<body>
	<!--
	<form name="uploader" action="index.php" method="post" enctype="multipart/form-data" >
		<input type="file" name="file_upload" onchange="document.uploader.submit()" style="background:#ff0000;position:fixed;top:0;right:0;height:100%;width:256px;opacity:0;" />
		<input style="visibility:hidden;" name="upload_button" type="submit" value="Upload" />
	</form>
	
	<div id="post" style="text-align:right;right:2.618em;top:1.618em;position:absolute;">
	<a href="#" onclick="newPost.style.display=''"><img src="css/plus.png" alt="New Post" height="15px" width="15px" border="0" /></a>
	</div>
	-->
	<!-- replace img with data:// -->

	<!--
	<div id="newPost" style="display:none;text-align:center;">
		<form>
			<input id="" type="text" placeholder="Date" /><br />
			<input id="" type="text" placeholder="Title"><br />
			<textarea id="content"></textarea>
		</form>
	</div>
	-->

	<h3 class="blogtitle"><?php echo "<a href=\"" . "./" . "\">" . $title . "</a>"; ?></h3>
	<?php
	/*
	bool signedIn = true;	//	Just for testing
	//	For displaying new post fields
	if ( signedIn ) {
		echo "<div id=\"newPost\">";
			echo "<form id=\"newPost\">";
				//	inputs
			echo "</form>";
		echo "</div>";
	}
	*/
	
	?>
	<div id="posts">
		<?php
			// Dangerous '../' bug
			if ( $post )
			{
				$content = file($dir . "/" . strip_tags($post) . ".txt");
				echo "<article class=\"content\" id=\"" . 0 . "\" >";	// Start article & ID #0
				echo "<span class=\"date\">" . $content[0] . "</span>";	// Display date with date formatting
				echo "<h1 class=\"title\">" . $content[1] . "</h1>";	//	Display Title
				for ( $j=2; $j<count($content); $j++)	//	Prints all other lines
				{
					echo "<p>" . $content[$j] . "</p>";
				}
				echo "</article>";
				// Take [0] and make date span out of it, take [1] and make linked title, take [2] to end and display normally. [2] will post only first paragraph - use for RSS description?
			}
			elseif ( count($posts) != 0 ) {	//	For multiple posts
				// Loop to load posts' content
				if ( $offset >= count($posts) ) {
					$page = ceil( count($posts) / $postsPerPage ) - 1;	//	Calculates the last page
					$offset = calcOffset($postsPerPage, $page);	//	Then calculates the new $offsetgi
				}
				for ( $i=$offset; $i<count($posts) && $i<( $postsPerPage + $offset ); $i++ ) {
					$content = file($posts[$i]);
					echo "<article class=\"content\" id=\"" . $i . "\" >";	// Start article & ID #
					echo "<span class=\"date\"><a href=\"?p=" .  urlencode(substr($posts[$i], strlen($dir) + 1, -4)) . "\">" . $content[0] . "</a></span>";	// Display date with date formatting
					echo "<h1 class=\"title\">" . $content[1] . "</h1>";	//	Display file name after the directory and / to the end, minus 4 for the '.txt' extension
					for ( $j=2; $j<count($content); $j++)	//	Prints all other lines
					{
						echo "<p>" . $content[$j] . "</p>";
					}
					echo "</article>";
					// Take [0] and make date span out of it
					// take [1] and make linked title
					// take [2] to end and display normally
					// [2] will post only first paragraph - use for RSS description?
				}
				echo "</div> <div id='nav'>";	//	Only Displays if not individual post
				if ( $page > 0 )	//	Only display if previous page exists
				{
					echo "<a href=\"?n=";
					echo $page - 1;
					echo "\">Newer</a> ";
				}
				if ( $page < (count($posts) / $postsPerPage) - 1 )	// Only display is next page exists 
				//	Possible "division" by zero error– default back to default
				{
					echo "<a href=\"?n=";
					echo $page + 1;
					echo "\">Older</a>";
				}
			}
		?>
	</div>
	<script> $("pre.php").snippet("php",{style:"bright",transparent:true,showNum:true}); $("pre.html").snippet("html",{style:"bright",transparent:true,showNum:true}); </script>
</body>
<?php
	if ($retinaReady) {
		echo "<script src=\"js/jquery.retina.js\" type=\"text/javascript\"></script>";
		echo "<script type=\"text/javascript\">";
			echo "$(document).ready( function() {";
				echo "$('img').retina();";
			echo "});";
		echo "</script>";
	}
?>
</html>