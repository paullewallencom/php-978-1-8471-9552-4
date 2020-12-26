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

<!-- Handle borrow or return book operations -->
<?php
if (isset ($_POST['borrow']) || isset ($_POST['return'])) {

    $url = "http://localhost/rest/04/library/member.php/" . $_POST['m_id'] .
    "/books/" . $_POST['b_id'];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    if (isset ($_POST['borrow'])) {
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        $data = "";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    } else
        if (isset ($_POST['return'])) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

    curl_exec($ch);
    curl_close($ch);
}
?>

<!-- List book borrowings by members -->
	<h2>Member Borrowings</h2>
	<table>
	<tr>
	  <th> Member ID </th>
	  <th> First Name </th>
	  <th> Last Name </th>
	  <th> Borrowing1 </th>
	  <th> Borrowing2 </th>
	</tr>
<?php


$url = 'http://localhost/rest/04/library/member.php';

$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($client);
curl_close($client);

$xml = simplexml_load_string($response);

foreach ($xml->member as $member) {
    echo "<tr> <td> " . htmlspecialchars($member->id) . " </td> " .
    "<td> " . htmlspecialchars($member->first_name) . " </td> " .
    "<td> " . htmlspecialchars($member->last_name) . " </td>";

    $url = "http://localhost/rest/04/library/member.php/$member->id/books";

    $client = curl_init($url);
    curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($client);
    curl_close($client);

    $xml = simplexml_load_string($response);

    foreach ($xml->book as $book) {
        echo "<td> $book->id , $book->name</td>";
    }
    echo "</tr>";
}
?>
    </table>
    
    <!-- Display the form to borrow or return books -->
    <h3> Add a Member </h3>
    
        <form action="borrowings.php" method="POST">
            <p>Member ID: <input type="text" name="m_id" /></p>
            <p>Book ID: <input type="text" name="b_id" /></p>
            <p><input type="submit" name="borrow" value="Borrow Book" /> 
            <input type="submit" name="return" value="Return Book" /></p>

    </form>
</body>
</html>
