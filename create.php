<?php
	function createFile()
	{
		$filename = $post_["filename"];
		$content = $post_["content"];
		if ( file_exists( $post_["filename"] . ".txt" ) )
		{
			echo "File already exists.";
			return false;
		}
		$new = fopen( $filename, "w" );
		fwrite( $new,  $content);
	}
	
	createFile();
?>