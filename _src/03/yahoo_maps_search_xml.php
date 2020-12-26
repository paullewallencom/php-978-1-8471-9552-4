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

function location_search($query, $in_location) {
    $base_url = 'http://local.yahooapis.com/LocalSearchService/V3/localSearch';

    $params = array (
        'appid' => 'YahooDemo',
        'query' => $query,
        'location' => $in_location
    );
    $url = $base_url . "?" . build_query_string($params);
    $response = curl_get($url);
    
    $xml = simplexml_load_string($response);
    foreach ($xml->Result as $location) {
		$data = array((string)$location->Latitude, (string)$location->Longitude, 
			(string)$location->Title);
    	$output[] = $data;
	}
    return $output;

}

function write_map_script(array $points) {
    // center map on the middle result and draw 
    if (count($points) > 0) {
        $middle_point = $points[count($points) / 2];
        $js_middle = <<<JAVA_SCRIPT
        	var points = new YGeoPoint($middle_point[0], $middle_point[1]);
 			map.drawZoomAndCenter(points, 5);
 			
JAVA_SCRIPT;
        foreach ($points as $id => $obj) {
        	$map_point_name = addslashes($obj[2]);
            $js_end = <<<JAVA_SCRIPT
            	var point$id = 
            	new YGeoPoint($obj[0],$obj[1]);
	            var current_marker = new YMarker(point$id);
            	current_marker.addLabel('$id');
            	current_marker.addAutoExpand('<div class="mp">$map_point_name</div>');
            	map.addOverlay(current_marker);
JAVA_SCRIPT;
			$js_middle .= $js_end;
        }
    }
    echo $js_middle . $js_end;
}

$points = location_search('Hotel', 'Cambridge, MA');

?>

<html>
  <head>
  	<script type="text/javascript" src="http://api.maps.yahoo.com/ajaxymap?v=3.0&appid=YOUR_API_KEY">
  	</script>
    <style>
      #mapHolder {
        height: 700px;
        width: 700px;
      }
    </style>
  </head>
  <body>
    <div id="mapHolder"></div>
    <script type="text/javascript">
	    var map = new YMap(document.getElementById('mapHolder'), YAHOO_MAP_REG);
    	map.addZoomShort();
    	map.addPanControl();
    	<?php write_map_script($points); ?>
    	
    </script>
  </body>
</html> 
