<?php
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return substr($pageURL, 0, strlen($pageURL)-11);
}
?>
<!doctype html>
<html>
<head>
	<title>Configure and Install</title>
	<link rel="stylesheet" type="text/css" href="s.css">
</head>
<body>
	<h3>Let's Get Started!</h3>
	<h2>1. Configure</h2>
	<form submit="asdf.php">
		<label for="blog">Blog title: </label><input type="name" id="blog" /> <br />
		<label for="password">Password: </label><input type="password" id="password" />
	</form>	<br />
	<h2>2. Learn</h2>
	To log in, simply go to <?php echo curPageURL(); ?>login.php and enter your password<br />
	Once logged in, you can click on any post to edit, or use the plus button in the upper right hand corner to add a new post.<br />
	Additionally, you can drag any .txt file over the browser window to automatically create a post.<br />
	Click on the cog in the upper right hand corner to edit your settings.
</body>
</html>