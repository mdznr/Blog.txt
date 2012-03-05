<?php
	
	header("Content-Type: text/xml;charset=iso-8859-1");

	include("includes/functions.php");

	$title = "Blog.txt";	//	Title for Blog
	$keywords = array("Matt Zanchelli", "Think Different", "Thinks Different", "Think Differently", "Apple", "blog");
	$description = "Matt Zanchelli runs a blog.";
	$author = "Matt Zanchelli";
	
	$dir = "posts";	//	Directory for storing posts
	
	$posts = glob( $dir . '/*.txt' );	//	Only files that end in .txt in te $dir directory
	usort($posts, recentPost);	//	Sorts list of .txt files by their Date (recent first)
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
?>
<rss version="2.0">
	<channel>
		
		<title> <?php echo $title; ?> </title>
		<description> <?php echo $description; ?> </description>
		<pubDate> <!-- Imported ONCE using Install.php --> </pubDate>
		<language>en</language>
		
		<?php //	Loads all "items" aka posts
			//	limit to ensure if not enough posts, it doesn't go out of bounds
			for ( $i=0; $i<5 /* Standard maximum for RSS Feeds, right? */ && /* Post Exists */ true; $i++ )
			{
				$content = file($posts[$i]);	//	Loads content first
				echo "\n<item>\n";
					echo "<title>" . $content[1] . "</title>\n";
					echo "<link>" . substr(curPageURL(), 0, -7) . "?p=" . substr($posts[$i], strlen($dir) + 1, -4) . "</link>\n";
					echo "<pubDate>" . $content[0] . "</pubDate>\n";
					echo "<description>" . $content[2] . "</description>\n";
				echo "</item>\n";
			}
		?>
		
	</channel>
</rss>