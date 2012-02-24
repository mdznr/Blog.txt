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

	// Is it necessary for linking to universal functions if there's only going to be one .php file using them? Hmm...
	include("includes/functions.php");	
	
	$title = "Matt Thinks Different";	//	Title for Blog
	$keywords = array("Apple", "blog", "Think Different", "Matt Zanchelli");
	$description = "Matt Zanchelli runs a blog.";
	$author = "Matt Zanchelli";
	
	$dir = "posts";	//	Directory for storing posts
	
	if ( $_GET["p"] ) { $post = $_GET["p"]; }

	$postsPerPage = 5;	//	Will be configurable
	if ( $_GET["postsPerPage"] ) { $postsPerPage = $_GET["postsPerPage"]; }	//	Overrides with URL arguments

	$page = 0;	//	Page # (Starts with 0)
	if ( $_GET["n"] ) { $page = $_GET["n"]; }	//	Overrides with URL arguments

	$offset = $postsPerPage * $page;	//	Calculates the offset for loading posts

	$posts = glob( $dir . '/*.txt' );	//	Only files that end in .txt in te $dir directory
	usort($posts, recentPost);	//	Sorts list of .txt files by their Date (recent first)
?>
<!doctype html>
<html lang=en>
<head>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.4.1/build/cssreset/cssreset-min.css" />
<link rel="stylesheet" type="text/css" href="css/core.css" />
<link href="http://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery.snippet.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.snippet.min.css" />

<title><?php echo $title ?></title>

<?php echo "<meta name=\"keywords\" content=\"";
for ($i=0;$i<sizeof($keywords);$i++) {
	echo $keywords[$i] . ", ";	// echos each keyword with a comma following it
}
echo "\" />" ?>

<?php echo "<meta name=\"description\" content=\"" . $description . "\" />" ?>
<?php echo "<meta name=\"author\" content=\"" . $author . "\" />" ?>
</head>
<body>
	<h3 class="blogtitle"><?php echo "<a href=\"" . "./" . "\">" . $title . "</a>"; ?></h3>
	<div id="posts">
		<?php
			if ( $post ) // Dangerous '../' bug
			{
				$content = file($dir . "/" . strip_tags($post) . ".txt");
				echo "<article class='content' >";	// Start article
				echo "<span class='date'>" . $content[0] . "</span>";	// Display date with date formatting
				echo "<h1 class='title'>" . $content[1] . "</h1>";	//	Display Title
				for ( $j=2; $j<count($content); $j++)	//	Prints all other lines
				{
					echo "<p>" . $content[$j] . "</p>";
				}
				echo "</article>";	// Take [0] and make date span out of it, take [1] and make linked title, take [2] to end and display normally. [2] will post only first paragraph - use for RSS description?
			}
			else {	// Loop to load posts' content
				for ( $i=$offset; $i<count($posts) && $i<( $postsPerPage + $offset ); $i++ ) {
					$content = file($posts[$i]);
					echo "<article class='content' id=\"" . $i . "\" >";	// Start article & ID #
					echo "<span class='date'><a href=\"?p=" . substr($posts[$i], strlen($dir) + 1, -4) . "\">" . $content[0] . "</a></span>";	// Display date with date formatting
					echo "<h1 class='title'>" . $content[1] . "</h1>";	//	Display file name after the directory and / to the end, minus 4 for the '.txt' extension
					for ( $j=2; $j<count($content); $j++)	//	Prints all other lines
					{
						echo "<p>" . $content[$j] . "</p>";
					}
					echo "</article>";	// Take [0] and make date span out of it, take [1] and make linked title, take [2] to end and display normally. [2] will post only first paragraph - use for RSS description?
				}
				echo "</div> <div id='nav'>";	//	Only Displays if not individual post
				if ( $page > 0 )	//	Only display if previous page exists
				{
					echo "<a href=\"?n=";
					echo $page - 1;
					echo "\">Newer</a> ";
				}
				if ( $page < (count($posts) / $postsPerPage) - 1 )	// Only display is next page exists
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
</html>