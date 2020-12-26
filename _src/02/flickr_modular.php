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

function build_query_string(array $params) {
	$query_string = '';
	foreach ($params as $key => $value) {
		$query_string .= "$key=" . urlencode($value) . "&";
	}
	return $query_string;
}

function curl_get($url) {
	$client = curl_init($url);
	curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($client);
	curl_close($client);
	return $response;
}

$base_url = 'http://api.flickr.com/services/rest/';
$api_key = 'YOUR_API_KEY';

$params = array (
	'method' => 'flickr.photos.search',
	'api_key' => $api_key,
	'tags' => 'flowers',
	'per_page' => 10
);

$url = "$base_url?" . build_query_string($params);

$response = curl_get($url);

$xml = simplexml_load_string($response);

foreach ($xml->photos->photo as $photo) {
	$attributes = $photo->attributes();

	$image_url = 'http://farm' . $attributes['farm'] . '.static.flickr.com/' . $attributes['server'] . '/' . $attributes['id'] . '_' . $attributes['secret'] . '.jpg';
	echo '<img src=\'' . $image_url . '\'/>' . "\n";

	$params = array (
		'method' => 'flickr.photos.getInfo',
		'api_key' => $api_key,
		'photo_id' => $attributes['id']
	);

	$url = "$base_url?" . build_query_string($params);

	$response = curl_get($url);

	$xml = simplexml_load_string($response);

	echo '<a href=\'' . $xml->photo->urls[0]->url . '\'>' . $xml->photo->title . '</a>' . "\n";

	echo "<ul>\n";
	foreach ($xml->photo->tags->tag as $tag)
		echo '<li>' . $tag . '</li>' . "\n";
	echo "</ul>\n";
}
?>
