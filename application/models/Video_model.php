<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_model extends CI_Model {

  // constructor
	function __construct()
	{
		parent::__construct();
	}

	// parse video id from youtube embed url
	function get_youtube_video_id($embed_url = '') {
		preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $embed_url, $match);
		$video_id = $match[1];
		return $video_id;
	}
	// parse video id from vimeo embed url
	function get_vimeo_video_id($embed_url = '') {
        if(preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $embed_url, $output_array)) {
            $video_id = $output_array[5];
            return $video_id;
        }
	}

	// fetch video information from youtube video id old code
	function get_youtube_video_information($video_id = '') {
		// api base url
		$url = 'https://www.googleapis.com/youtube/v3/videos';
		// get api key from system settings
		$api_key = get_settings('youtube_api_key');
		// specify the parts that are needed from response_json
		$parts = 'snippet,contentDetails';
		// make a request to youtube api with video id and api key
		$response_json = file_get_contents($url.'?id='.$video_id.'&key='.$api_key.'&part='.$parts);
		$response = json_decode($response_json);
		// keep the data in own associative Array
		$data['title']	=	$response->items[0]->snippet->title;
		$data['description']	=	$response->items[0]->snippet->description;
		if(!isset($response->items[0]->snippet->thumbnails->standard->url)){
			$data['thumbnail']	=	$response->items[0]->snippet->thumbnails->default->url;
		}
		else{
			$data['thumbnail']	=	$response->items[0]->snippet->thumbnails->standard->url;

		}
		//$duration	=	$response->items[0]->contentDetails->duration;
		$duration 			  = new DateInterval($response->items[0]->contentDetails->duration);
		$data['duration'] = $duration->format('%H:%I:%S');
		return $data;
	}

    // Get video details new code
    function getVideoDetails($url)
	{
	    $host = explode('.', str_replace('www.', '', strtolower(parse_url($url, PHP_URL_HOST))));
	    $host = isset($host[0]) ? $host[0] : $host;

        $vimeo_api_key = get_settings('vimeo_api_key');
        $youtube_api_key = get_settings('youtube_api_key');

		if ($host == 'vimeo') {
			$video_id = substr(parse_url($url, PHP_URL_PATH), 1);
			$options = array('http' => array(
				'method'  => 'GET',
				'header' => 'Authorization: Bearer '.$vimeo_api_key
			));
			$context  = stream_context_create($options);

			$hash = json_decode(file_get_contents("https://api.vimeo.com/videos/{$video_id}",false, $context));

			//header("Content-Type: text/plain");
			return array(
				'provider'          => 'Vimeo',
				'video_id'			=> $video_id,
				'title'             => $hash->name,
				'description'       => str_replace(array("<br>", "<br/>", "<br />"), NULL, $hash->description),
				'description_nl2br' => str_replace(array("\n", "\r", "\r\n", "\n\r"), NULL, $hash->description),
				'thumbnail'         => $hash->pictures->sizes[0]->link,
				'video'             => $hash->link,
				'embed_video'       => "https://player.vimeo.com/video/" . $video_id,
				'duration'			=>	gmdate("H:i:s", $hash->duration)
			);
		}elseif ('youtube' || 'youtu') {
			$video_id = $this->get_youtube_video_id($url);
			$hash = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=".$video_id."&key=".$youtube_api_key.""));
			//header("Content-Type: text/plain");

			$duration = new DateInterval($hash->items[0]->contentDetails->duration);
			return array(
				'provider'          => 'YouTube',
				'video_id'			=> $video_id,
				'title'             => $hash->items[0]->snippet->title,
				'description'       => str_replace(array("", "<br/>", "<br />"), NULL, $hash->items[0]->snippet->description),
				'description_nl2br' => str_replace(array("\n", "\r", "\r\n", "\n\r"), NULL, nl2br($hash->items[0]->snippet->description)),
				'thumbnail'         => 'https://i.ytimg.com/vi/'.$hash->items[0]->id.'/default.jpg',
				'video'             => "http://www.youtube.com/watch?v=" . $hash->items[0]->id,
				'embed_video'       => "http://www.youtube.com/embed/" . $hash->items[0]->id,
				'duration'       	=> $duration->format('%H:%I:%S'),
			);
		}
	}
}
