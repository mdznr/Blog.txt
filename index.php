<!doctype html>
<html lang=en>
<head>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.4.1/build/cssreset/cssreset-min.css" />
<link rel="stylesheet" type="text/css" href="css/core.css" />
<link href="http://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery.snippet.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.snippet.min.css" />

<title>Matt Zanchelli</title>
</head>
<body>
	<h3 class="blogtitle">Matt Zanchelli</h3>
	<div id="posts">
			<?php
				include("includes/functions.php");	// Is it necessary for linking to universal functions if there's only going to be one .php file using them? Hmm...
				$dir = "posts";	//	Directory for storing posts
				$postsPerPage = 4;	//	Will be configurable
				if ( $_GET["postsPerPage"] ) { $postsPerPage = $_GET["postsPerPage"]; }	//	Overrides with URL arguments
				$page = 0;	//	Page # (Starts with 0)
				// $page = $_GET["page"];	//	Overrides with URL arguments
				if ( $_GET["page"] ) { $page = $_GET["page"]; }	//	Overrides with URL arguments
				$offset = $postsPerPage * $page;	//	Calculates the offset for loading posts 
				opendir( $dir );
				$posts = glob( $dir . '/*.txt' );
				usort($posts, earlierPost);
				for ( $i=$offset; $i<(count($posts)+$offset) && $i<($postsPerPage+$offset); $i++ ) {
					echo "<div class='content'>" . file_get_contents( $posts[$i] ) . "</div>";
				}
			?>
	</div>
	<script> $("pre.php").snippet("php",{style:"bright",transparent:true,showNum:true}); $("pre.html").snippet("html",{style:"bright",transparent:true,showNum:true}); </script>
</body>
</html>