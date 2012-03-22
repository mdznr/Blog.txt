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
	include("includes/posts.php");
	include("includes/helpers.php");
	include("config.php");

	if ( $_GET["p"] ) { $post = $_GET["p"]; }

	//	Posts Per Page
	$postsPerPage = updateNumPostsPerPage($_GET["postsPerPage"]);
	$page = getPageNumber($_GET["n"]); // starts at 0
	$posts = getPosts($dir);

	//	For file uploading
	if ( isset($_FILES["file_upload"]) ) {
		addPost($dir, $_FILES["file_upload"]);
	}

?>
<!doctype html>
<html lang=en>
<head>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.4.1/build/cssreset/cssreset-min.css" />
<link rel="stylesheet" type="text/css" href="css/core.css" /> <!-- Regular stylesheet -->

<?php echo stylesheetLink(); ?>

<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery.snippet.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.snippet.min.css" />

<title><?php echo $title ?></title>

<link rel="alternate" type="application/rss+xml" title=<?php echo "\"" . $title . "\""; ?> href="/rss.php">

<meta name="keywords" content="<?php echo implode(', ', $keywords); ?>" />

<meta name="description" content="<?php echo $description; ?>" />
<meta name="author" content="<?php echo $author; ?>" />
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

	<h3 class="blogtitle"><a href="./"><?php echo $title; ?></a></h3>
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
			if ( hasPost($dir, $post) ):
				$content = getPost($dir, $post);
		?>
				<article class="content" id="0" >
				<span class="date"><?php echo $content[0]; ?></span>
				<h1 class="title"><?php echo $content[1]; ?></h1>
				<?php
					for ( $j=2; $j<count($content); $j++)	//	Prints all other lines
					{
						echo "<p>" . $content[$j] . "</p>";
					}
					// Take [0] and make date span out of it, take [1] and make linked title, take [2] to end and display normally. [2] will post only first paragraph - use for RSS description?
				?>
				</article>
		<?php
			elseif ( count($posts) != 0 ):	//	For multiple posts
				$offset = getPostOffsetByPage($pageNumber, $postsPerPage, count($posts));
				// Loop to load posts' content
				$stop = indexOfNextPagePost($offset, $postsPerPage, count($posts));
				for ( $i=$offset; $i<$stop; $i++ ):
					$content = file($posts[$i]);
		?>
					<article class="content" id="<?php echo $i; // article id ?>">
						<span class="date">
							<a href="?p=<?php echo urlencode(getPostIdentifier($posts[$i])); ?>">
								<?php echo $content[0]; ?>
							</a>
						</span>
						<h1 class="title"><?php echo $content[1]; ?></h1>
						<?php
							for ( $j=2; $j<count($content); $j++)
							{	//	Prints all other lines
								echo "<p>" . $content[$j] . "</p>";
							}
						?>
					</article>
				<?php
					// Take [0] and make date span out of it
					// take [1] and make linked title
					// take [2] to end and display normally
					// [2] will post only first paragraph - use for RSS description?
				endfor;
				?>
				</div>
				<div id='nav'>
				<?php if ( hasNewerPage($page) ): ?>
					<a href="?n=<?php echo $page - 1; ?>">Newer</a>
				<?php endif; ?>
				<?php if ( hasOlderPage($page, $postsPerPage, count($posts))): ?>
					<a href="?n=<?php echo $page + 1; ?>">Older</a>
				<?php endif; ?>
		<?php endif; ?>
	</div>
	<script> $("pre.php").snippet("php",{style:"bright",transparent:true,showNum:true}); $("pre.html").snippet("html",{style:"bright",transparent:true,showNum:true}); </script>
</body>
<?php if ($retinaReady): ?>
	<script src="js/jquery.retina.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(document).ready( function() {
		$('img').retina();
	});
	</script>";
<?php endif; ?>
</html>
