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

require_once 'RESTUtil.php';

$url = 'http://newsrss.bbc.co.uk/rss/newsonline_world_edition/technology/rss.xml';

$response = curl_get($url);

$xml = simplexml_load_string($response);

$base_url = 'http://search.yahooapis.com/NewsSearchService/V1/newsSearch';

foreach ($xml->channel->item as $item) {
	echo '<h2>' . $item->title . '</h2>'. "\n";

	$params = array (
		'appid' => 'YahooDemo',
		'query' => $item->title,
		'results' => 2,
		'language' => 'en'
	);

	$url = "$base_url?" . build_query_string($params);

	$response = curl_get($url);
	$xml = simplexml_load_string($response);

	echo '<ul>' ."\n";
	foreach ($xml->Result as $news) {
		echo '<li><a href=\'' . $news->Url . '\'/>' . $news->Title . '</a></li>' . "\n";
	}
	echo '</ul>' ."\n";
}
?>