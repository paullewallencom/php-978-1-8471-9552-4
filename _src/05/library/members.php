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

<!-- Add member -->
<?php
if (isset ($_POST['fname'])) {

    $url = 'http://localhost/rest/04/library/member.php';

    $data = "<members><member><first_name>" . $_POST['fname'] . "</first_name><last_name>" .
    $_POST['lname'] . "</last_name></member></members>";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    curl_close($ch);
}
?>

<!-- List members -->
	<h2>Members</h2>
	<table>
	<tr>
	  <th> Member ID </th>
	  <th> First Name </th>
	  <th> Last Name </th>
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
    " <td> " . htmlspecialchars($member->first_name) . " </td> " .
    " <td> " . htmlspecialchars($member->last_name) . " </td> </tr>";
}
?>
    </table>
    
	<!-- Display the form -->    
    <h3> Add a Member </h3>
    

        <form action="members.php" method="POST">
            <p>First name: <input type="text" name="fname" /></p>
            <p>Last name: <input type="text" name="lname" /></p>
            <p><input type="submit" name="submit" value="Add Member" /></p>

    </form>
</body>
</html>
