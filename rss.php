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
	
	header("Content-Type: text/xml;charset=iso-8859-1");

	include("includes/functions.php");
	include("config.php");
	
	$posts = glob( $dir . '/*.txt' );	//	Only files that end in .txt in te $dir directory
	usort($posts, recentPost);	//	Sorts list of .txt files by their Date (recent first)
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
?>
<rss version="2.0">
	<channel>
		
		<title> <?php echo $title; ?> </title>
		<description> <?php echo $description; ?> </description>
		<link></link>	<!-- Link to blog (to be automatic) -->
		<?php if (count($posts)): ?>
		<lastBuildDate> <?php $content = file($posts[0]); echo $content[0]; ?> </lastBuildDate>	<!-- Errr... -->
		<?php endif; ?>
		<pubDate> <?php $content = file($posts[count($posts) - 1]); echo $content[0]; ?> </pubDate>	<!-- Will be imported ONCE using Install.php -->
		<language>en</language>
		
		<?php //	Loads all "items" aka posts
			//	limit to ensure if not enough posts, it doesn't go out of bounds
			for ( $i=0; $i<min(count($posts), 5) /* Standard maximum for RSS Feeds, right? */ && /* Post Exists */ true; $i++ )
			{
				$content = file($posts[$i]);	//	Loads content first
				echo "\n<item>\n";
					echo "<title>" . $content[1] . "</title>\n";
					echo "<link>" . substr(curPageURL(), 0, -7) . "?p=" . urlencode(substr($posts[$i], strlen($dir) + 1, -4)) . "</link>\n";
					echo "<pubDate>" . $content[0] . "</pubDate>\n";
					echo "<description>" . $content[2] . "</description>\n";
				echo "</item>\n";
			}
		?>
		
	</channel>
</rss>