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
require_once('functions.php');

// This class handles the activities relating to supporting pages of items.
class Paginator
{
	public $page = 0;
	public $itemsPerPage = 5;
	public function __construct($options=array())
	{
		// the current page
		$page = get($options, 'page');
		$this->page = is_numeric($page) ? intval($page) : 0;

		$this->itemsPerPage = get($options, 'itemsPerPage') or die('itemsPerPage argument is required');
	}

	public function itemsForPage($items, $options=array())
	{
		$autoload = get($options, 'autoload', 1);
		$offset = $this->postOffset(count($items));
		$stop = $this->indexOfPostForNextPage(count($items), $offset);
        $subsetOfItems = array();
		for ( $i=$offset; $i<$stop; $i++ )
		{
			if ($autoload)
				$items[$i]->load();
			$items[$i]->id = $i;
			array_push($subsetOfItems, $items[$i]);
		}
		return $subsetOfItems;
	}

	protected function postOffset($postCount)
	{
		$offset = $this->itemsPerPage * $this->page;
		if ( $offset >= $postCount ) {
			$page = ceil( $postCount / $this->itemsPerPage ) - 1;	//	Calculates the last page
			$offset = postOffset($this->itemsPerPage, $this->page);	//	Then calculates the new $offsetgi
		}
		return $offset;
	}

	protected function indexOfPostForNextPage($postCount, $offset)
	{
		return min($postCount, $this->itemsPerPage + $offset);
	}

	public function hasNewerPage()
	{
		return $this->page > 0;
	}

	// returns true if the given page has a previou on
	public function hasOlderPage($postCount)
	{
		//	Possible "division" by zero error– default back to default
		return $this->page < ($postCount / $this->itemsPerPage) - 1;
	}
}

