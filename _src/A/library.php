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
 
function getBooksFunction($inMessage) {
    $link = mysql_connect('localhost', 'sam', 'pass') or die('Could not connect: ' . mysql_error());
    mysql_select_db('library') or die('Could not select database');

    $query = "SELECT b.id, b.name, b.author, b.isbn FROM book as b";

    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    $response = "<books>";
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $response .= "<book>";
        foreach ($line as $key => $col_value) {
            $response .= "<$key>$col_value</$key>";
        }
        $response .= "</book>";
    }
    $response .= "</books>";

    mysql_free_result($result);

    mysql_close($link);

    $outMessage = new WSMessage($response);

    return $outMessage;
}

function addBooksFunction($inMessage) {
    $link = mysql_connect('localhost', 'sam', 'pass') or die('Could not connect: ' . mysql_error());
    mysql_select_db('library') or die('Could not select database');

    $xml = simplexml_load_string($inMessage->str);
    foreach ($xml->book as $book) {
        $query = "INSERT INTO book (name, author, isbn) VALUES ('$book->name', '$book->author', '$book->isbn')";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        mysql_free_result($result);
    }
    mysql_close($link);
    
    return;
}

$operations = array (
    "getBooks" => "getBooksFunction",
    "addBooks" => "addBooksFunction"
);

$restmap = array (
    "getBooks" => array (
        "HTTPMethod" => "GET",
        "RESTLocation" => "book"
    ),
    "addBooks" => array (
        "HTTPMethod" => "POST",
        "RESTLocation" => "book"
    )
);


$service = new WSService(array (
    "operations" => $operations,
    "RESTMapping" => $restmap
));

$service->reply();
?>
