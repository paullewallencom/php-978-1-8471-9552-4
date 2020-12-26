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
 
// Connect to database
$link = mysql_connect('localhost', 'sam', 'pass') or die('Could not connect: ' . mysql_error());
mysql_select_db('library') or die('Could not select database');

// Prepare the query, and execute the query
$query = 'SELECT b.name, b.author, b.isbn FROM book as b';
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

// Write the table hadders
echo "<table border='1'>\n";
$line = mysql_fetch_assoc($result);
if ($line == null)
    return;
echo "\t<tr>\n";
foreach ($line as $key => $col_value) {
    echo "\t\t<td>$key</td>\n";
}
echo "\t</tr>\n";

// Write the data into the table 
mysql_data_seek($result, 0);
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "\t<tr>\n";
    foreach ($line as $key => $col_value) {
        echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";
}
echo "</table>\n";

// Free the results and close database connection 
mysql_free_result($result);
mysql_close($link);
?>
