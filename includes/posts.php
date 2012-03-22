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

// Functions related to pages -- maybe use a class?

// sets the number of posts per page for the user
// returns the expected number of posts per page
function updateNumPostsPerPage($getValue, $cookieKey="postsPerPage")
{
	if ( isset($getValue) ){
		setcookie($cookieKey, intval($getValue));
	}
	$num = 0;
	if ( $_COOKIE[$cookieKey] ) {
		$num = intval($_COOKIE[$cookieKey]);
	}
	return $num || 5;
}

// Returns the page number for posts to load
function getPageNumber($getValue)
{
	return is_numeric($getValue) ? intval($getValue) : 0;
}

// returns the post index of the first post to get for a given page number
function getPostOffsetByPage($pageNumber, $postsPerPage, $postCount)
{
	$offset = $postsPerPage * $pageNumber;
	if ( $offset >= $postCount ) {
		$page = ceil( $postCount / $postsPerPage ) - 1;	//	Calculates the last page
		$offset = getPostOffsetByPageNumber($postsPerPage, $page);	//	Then calculates the new $offsetgi
	}
	return $offset;
}

function indexOfNextPagePost($offset, $postsPerPage, $postCount)
{
	return min($postCount, $postsPerPage + $offset);
}

// returns the filename, without any directory or extension
// used for linking to a particular post
function getPostIdentifier($filepath)
{
	return basename($filepath, ".txt");
}


function hasNewerPage($page)
{
	return $page > 0;
}

// returns true if the given page has a previou on
function hasOlderPage($page, $postsPerPage, $postCount)
{
	//	Possible "division" by zero error– default back to default
	return $page < ($postCount / $postsPerPage) - 1;
}

function getPosts($directory)
{
	$posts = glob($directory . "/*.txt");	//	Only files that end in .txt in te $dir directory
	usort($posts, recentPost);	//	Sorts list of .txt files by their Date (recent first)
	return $posts;
}

function getPostPath($directory, $file)
{
	return $directory . "/" . realpath(post) . ".txt";
}

function hasPost($directory, $file)
{
	return file_exists(getPostPath($directory, $file));
}

function getPost($directory, $file)
{
	return file(getPostPath($directory, $file));
}

function addPost($directory, $file)
{
	$name = $file['name'];
	$tmp = $file['tmp_name'];
	$size = $file['size'];
	$type = $file['type'];
	$max_size = 50 * 1024 * 1024;	// 50 megabytes 
	$upload_dir = $directory . '/';

	if(($size > 0) && ($type !== "text/php")) {
		if(!is_dir($upload_dir)){ echo $upload_dir . ' is not a directory'; }
		else if($size > $max_size){ echo 'The file you are trying to upload is too big.'; }
		else{
			if(!is_uploaded_file($tmp)){ echo 'Could not upload your file at this time, please try again'; }	
			else{
				if(!move_uploaded_file($tmp, $upload_dir . $name)){ echo 'Could not move the uploaded file.'; }
				else{ $message = $name . " was successfully uploaded!"; }	
			}
		}
	}
	elseif($type === "text/php"){ echo "You cannot upload that file here."; }
}
