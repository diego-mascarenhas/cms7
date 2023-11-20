<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Feeds extends REST_Controller {

	public function index_get($id = null)
	{
		// creating rss feed with our most recent 20 posts in variable $post
	
	    // first load the library
	    $this->load->library('feed');
	
	    // create new instance
	    $feed = new Feed();
	
	    // set your feed's title, description, link, pubdate and language
	    $feed->title = 'Rocoto';
	    $feed->description = 'Webcasting studio';
	    $feed->link = 'http://rocoto.com';
	    $feed->lang = 'en';

		$feed->pubdate = '2016-11-16 10:00:00';
		
		$feed->add('Título', 'Rocoto', 'http://www.rocoto.tv', '2016-11-16 10:00:00', 'Descripción del Feed');
/*
	    $feed->pubdate = $posts[0]->created; // date of your last update (in this example create date of your latest post)
	
	    // add posts to the feed
	    foreach ($posts as $post)
	    {
	        // set item's title, author, url, pubdate and description
	        $feed->add($post->title, $post->author, $post->slug, $post->created, $post->description);
	    }
*/
	
	    // show your feed (options: 'atom' (recommended) or 'rss')
	    $feed->render('rss');
	}
	

}