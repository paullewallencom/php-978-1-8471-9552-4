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
        'output' => 'php',
        'query' => $query,
        'location' => $in_location
    );
    $url = $base_url . "?" . build_query_string($params);
    $response = curl_get($url);
    
    $output = unserialize($response);
    return $output['ResultSet']['Result'];

}

function write_map_script(array $points) {
    $js = '';
    // init the map
    $js .= 'var map = new YMap(document.getElementById(\'mapHolder\'), YAHOO_MAP_REG' . ");\n";

    $js .= "map.addZoomShort();\n";
    $js .= "map.addPanControl();\n";

    // center map on the middle result and draw 
    if (count($points) > 0) {
        $middle_point = $points[count($points) / 2];
        $js .= 'var point' . count($points) . ' = new YGeoPoint(' .
            $middle_point[0] . ',' . $middle_point[1] . ");\n";

        $js .= 'map.drawZoomAndCenter(point' . count($points) .  ", 5);\n";

        foreach ($points as $id => $obj) {
            $js .= "\nvar point$id = " .
            "new YGeoPoint($obj[0],$obj[1]);\n";

            $js .= "var current_marker = new YMarker(point$id);\n";
            $js .= "current_marker.addLabel('$id');\n";
            $js .= "current_marker.addAutoExpand('<div class=\"mp\">" .
            addslashes($obj[2]) . "</div>');\n";
            $js .= "map.addOverlay(current_marker);\n\n";
        }
    }
    echo $js;
}

$results = location_search('Hotel', ' Cambridge, MA');

foreach ($results as $id => $data) {
    $points[$id] = array (
        $data['Latitude'],
        $data['Longitude'],
        $data['Title']
    );
}

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
    <?php write_map_script($points); ?>
    </script>
  </body>
</html> 
