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
 
$link = mysql_connect('localhost', 'sam', 'pass') or die('Could not connect: ' . mysql_error());
mysql_select_db('library') or die('Could not select database');

// Check for the path elements
$path = $_SERVER[PATH_INFO];    
if ($path != null) {
    $path_params = spliti ("/", $path);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents("php://input");
    $xml = simplexml_load_string($input);
    foreach ($xml->book as $book) {
        $query = "INSERT INTO book (name, author, isbn) VALUES ('$book->name', '$book->author', '$book->isbn')";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        mysql_free_result($result);
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($path_params[1] != null) {
      	    $query = "SELECT b.id, b.name, b.author, b.isbn FROM book as b WHERE b.id = $path_params[1]";
    } else {  
    	$query = "SELECT b.id, b.name, b.author, b.isbn FROM book as b";
    }
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo "<books>";
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        echo "<book>";
        foreach ($line as $key => $col_value) {
            echo "<$key>$col_value</$key>";
        }
        echo "</book>";
    }
    echo "</books>";

    mysql_free_result($result);
}

mysql_close($link);

?>
