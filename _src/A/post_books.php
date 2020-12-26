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
 
$requestPayloadString = <<<XML
<books>
    <book><name>Book7</name><author>Auth7</author><isbn>ISBN0007</isbn></book>
    <book><name>Book8</name><author>Auth8</author><isbn>ISBN0008</isbn></book>
</books>
XML;

try {

    $client = new WSClient( array("to" => "http://localhost/rest/A/library.php/book",
                                  "useSOAP" => FALSE,
                                  "HTTPMethod" => "POST"));

    $client->send($requestPayloadString);

} catch (Exception $e) {

    if ($e instanceof WSFault) {
	    printf("Error String: %s\n", $e->str);
	    printf("HTTP Code   : %s\n", $e->httpStatusCode);
    } else {
        printf("Message = %s\n",$e->getMessage());
    }
}
?>
