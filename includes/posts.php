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
require_once('config.php');
require_once('functions.php');

// Functions related to pages -- maybe use a class?

function recentPost($a, $b)	//	Reads post date from first line of .txt document
{
	checkPostDate($a);
	checkPostDate($b);

	$filea = file($a);
	$fileb = file($b);

	$timea = strtotime($filea[0]);
	$timeb = strtotime($fileb[0]);

	return $timea < $timeb;	// Returns true if post a is older
}

class Post
{
	public static $ROOT = '';
	public $file = '';
	public $ext = '';
	public $date = '';
	public $title = '';
	public $body = '';
	public $body_html = '';
	public $id = 0; // used by paginator
	public function __construct($file=null, $ext=".txt")
	{
		if ($file)
		{
			$realpath = basename(realpath($file), $ext);
			$this->file = self::$ROOT . '/' . $realpath . $ext;
		}
		$this->ext = $ext;
	}

	// returns true if the given post exists
	public function exists()
	{
		return $this->file && file_exists($this->file);
	}

	public function getBasename()
	{
		return basename($this->file, $this->ext);
	}

	public function load()
	{
		if (!$this->exists()) return false;

		$lines = file($this->file);
		$this->date = $lines[0];
		$this->title = $lines[1];
		$this->body = array_slice($lines, 2);
		$this->body_html = '<p>' . implode('</p><p>', $this->body) . '</p>';

		return true;
	}

	public static function getAll()
	{
		$files = glob(self::$ROOT . "/*.txt");	//	Only files that end in .txt in te $dir directory
		usort($files, 'recentPost');	//	Sorts list of .txt files by their Date (recent first)
		$posts = array();
		foreach($files as $file)
		{
			$p = new self($file);
			$p->load();
			array_push($posts, $p);
		}
		return $posts;
	}
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

// assign root to value from config.php
Post::$ROOT = $dir;

