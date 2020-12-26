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
	'method' => 'flickr.people.findByUsername',
	'api_key' => 'YOUR_API_KEY',
	'username' => 'Sami'
);

foreach ($params as $key => $value) {
	$query_string .= "$key=" . urlencode($value) . "&";
}

$url = "$base_url?$query_string";

echo $url; 

$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($client);
curl_close($client);

echo $response;
$xml = simplexml_load_string($response);

foreach ($xml->user as $user) {
	$attributes = $user->attributes();
	echo 'User ID : ' . $attributes['id'] . "\n";
	echo 'User NSID : ' . $attributes['nsid'] . "\n";
}
?>
