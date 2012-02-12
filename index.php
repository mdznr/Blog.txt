<?php
	include("includes/functions.php");	// Is it necessary for linking to universal functions if there's only going to be one .php file using them? Hmm...

	$dir = "posts";	//	Directory for storing posts

	$postsPerPage = 3;	//	Will be configurable
	if ( $_GET["postsPerPage"] ) { $postsPerPage = $_GET["postsPerPage"]; }	//	Overrides with URL arguments

	$page = 0;	//	Page # (Starts with 0)
	if ( $_GET["page"] ) { $page = $_GET["page"]; }	//	Overrides with URL arguments

	$offset = $postsPerPage * $page;	//	Calculates the offset for loading posts

	$posts = glob( $dir . '/*.txt' );
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

<title>Matt Zanchelli</title>

</head>
<body>
	<h3 class="blogtitle">Matt Zanchelli</h3>
	<div id="posts">
		<?php // Loop to load posts' content
			for ( $i=$offset; $i<(count($posts)+$offset) && $i<($postsPerPage+$offset); $i++ ) {
				echo "<article class='content'>" . file_get_contents( $posts[$i] ) . "</article>";
			}
		?>
	</div>
	<?php
		if ( $page > 0 )	//	Only display if previous page exists
		{
			echo "<a href=\"?page=";
			echo $page - 1;
			echo "\">Previous</a>";
		}
		if ( true )	// Only display is next page exists
		{
			echo "<a href=\"?page=";
			echo $page + 1;
			echo "\">Next</a>";
		}
	?>
	<script> $("pre.php").snippet("php",{style:"bright",transparent:true,showNum:true}); $("pre.html").snippet("html",{style:"bright",transparent:true,showNum:true}); </script>
</body>
</html>