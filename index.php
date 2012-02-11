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
				include("includes/functions.php");
				$dir = "posts";
				opendir( $dir );
				$posts = glob( $dir . '/*.txt' );
				usort($posts, earlierPost);
				for ( $i=0; $i<count($posts); $i++ ) {
					echo "<div class='content'>" . file_get_contents( $posts[$i] ) . "</div>";
				}
			?>
	</div>
	<script> $("pre.php").snippet("php",{style:"bright",transparent:true,showNum:true}); $("pre.html").snippet("html",{style:"bright",transparent:true,showNum:true}); </script>
</body>
</html>