<?php
/*
 * Copyright 2008 Samisa Abeysinghe
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

$base_url = 'http://api.flickr.com/services/rest/';
$query_string = '';

$params = array (
	'method' => 'flickr.photos.search',
	'api_key' => 'YOUR_API_KEY',
	'tags' => 'flowers',
	'per_page' => 10
);

foreach ($params as $key => $value) {
	$query_string .= "$key=" . urlencode($value) . "&";
}

$url = "$base_url?$query_string";

$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($client);
curl_close($client);

$xml = simplexml_load_string($response);

foreach ($xml->photos->photo as $photo) {
	$attributes = $photo->attributes();

	$image_url = 'http://farm' . $attributes['farm'] . '.static.flickr.com/' . $attributes['server'] . '/' . $attributes['id'] . '_' . $attributes['secret'] . '.jpg';
	echo '<img src=\'' . $image_url . '\'/>'."\n";

	$params = array (
		'method' => 'flickr.photos.getInfo',
		'api_key' => 'YOUR_API_KEY',
		'photo_id' => $attributes['id']
	);

	$query_string = '';
	foreach ($params as $key => $value) {
		$query_string .= "$key=" . urlencode($value) . "&";
	}

	$url = "$base_url?$query_string";

	$client = curl_init($url);
	curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($client);
	curl_close($client);
	
	$xml = simplexml_load_string($response);
	
	echo '<a href=\'' . $xml->photo->urls[0]->url . '\'>'. $xml->photo->title . '</a>' ."\n";
	
	echo "<ul>\n";
	foreach ($xml->photo->tags->tag as $tag) 
		echo '<li>'. $tag . '</li>'."\n";
	echo "</ul>\n";
}
?>
