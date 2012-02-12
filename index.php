<?php
	include("includes/functions.php");	// Is it necessary for linking to universal functions if there's only going to be one .php file using them? Hmm...
	
	$title = "Matt Zanchelli";	//	Title for Blog

	$dir = "posts";	//	Directory for storing posts
	
	if ( $_GET["post"] ) { $post = $_GET["post"]; }

	$postsPerPage = 5;	//	Will be configurable
	if ( $_GET["postsPerPage"] ) { $postsPerPage = $_GET["postsPerPage"]; }	//	Overrides with URL arguments

	$page = 0;	//	Page # (Starts with 0)
	if ( $_GET["page"] ) { $page = $_GET["page"]; }	//	Overrides with URL arguments

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

</head>
<body>
	<h3 class="blogtitle"><?php echo "<a href=\"" . "./" . "\">" . $title . "</a>"; ?></h3>
	<div id="posts">
		<?php
			if ( $post ) // Dangerous ../ bug
			{
				$content = file($dir . "/" . strip_tags($post) . ".txt");
				echo "<article class='content'>";	// Start article
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
					echo "<article class='content'>";	// Start article
					echo "<span class='date'>" . $content[0] . "</span>";	// Display date with date formatting
					echo "<h1 class='title'><a href=\"?post=" . substr($posts[$i], strlen($dir) + 1, -4) . "\">" . $content[1] . "</a></h1>";	//	Display file name after the directory and / to the end, minus 4 for the '.txt' extension
					for ( $j=2; $j<count($content); $j++)	//	Prints all other lines
					{
						echo "<p>" . $content[$j] . "</p>";
					}
					echo "</article>";	// Take [0] and make date span out of it, take [1] and make linked title, take [2] to end and display normally. [2] will post only first paragraph - use for RSS description?
				}
				echo "</div> <div id='nav'>";	//	Only Displays if not individual post
				if ( $page > 0 )	//	Only display if previous page exists
				{
					echo "<a href=\"?page=";
					echo $page - 1;
					echo "\">Newer</a> ";
				}
				if ( $page < (count($posts) / $postsPerPage) - 1 )	// Only display is next page exists
				{
					echo "<a href=\"?page=";
					echo $page + 1;
					echo "\">Older</a>";
				}
			}
		?>
	</div>
	<script> $("pre.php").snippet("php",{style:"bright",transparent:true,showNum:true}); $("pre.html").snippet("html",{style:"bright",transparent:true,showNum:true}); </script>
</body>
</html>