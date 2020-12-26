<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--
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
-->
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
</head>
<body>

<!-- Create book -->
<?php
if (isset ($_POST['name'])) {

    $url = 'http://localhost/rest/04/library/book.php';

    $data = "<books><book><name>" . $_POST['name'] . "</name><author>" . $_POST['author'] .
    "</author><isbn>" . $_POST['isbn'] . "</isbn></book></books>";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    curl_close($ch);
}
?>

	<h2>Books</h2>
	<table>
	<tr>
	  <th> Book ID </th>
	  <th> Name </th>
	  <th> Author </th>
	  <th> ISBN </th>
	</tr>

<!-- List Books -->
<?php

$url = 'http://localhost/rest/04/library/book.php';

$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($client);
curl_close($client);

$xml = simplexml_load_string($response);

foreach ($xml->book as $book) {
    echo "<tr> <td> " . htmlspecialchars($book->id) . "</td> ".
		 " <td> " . htmlspecialchars($book->name) . "</td> " . 
		 " <td> " . htmlspecialchars($book->author) . " </td> ".
		 " <td> " . htmlspecialchars($book->isbn) . " </td></tr>";
}
?>
    </table>
    
    <h3> Add a Book to Library </h3>
    
        <form action="books.php" method="POST">
            <p>Book name: <input type="text" name="name" /></p>
            <p>Author: <input type="text" name="author" /></p>
            <p>ISBN: <input type="text" name="isbn" /></p>
            <p><input type="submit" name="submit" value="Add Book" /></p>

    </form>
</body>
</html>
