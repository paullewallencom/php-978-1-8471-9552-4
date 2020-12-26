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

function init_database() {
    $link = mysql_connect('localhost', 'sam', 'pass') or die('Could not connect: ' . mysql_error());
    mysql_select_db('library') or die('Could not select database');
    return $link;
}

function handle_borrow_book($member_id, $book_id) {
    $today = date("Y-m-d");
    $query = "INSERT INTO borrowing (member_id, book_id, start_date) VALUES ($member_id, $book_id, '$today')";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    mysql_free_result($result);
}

function add_member() {
    $input = file_get_contents("php://input");
    $xml = simplexml_load_string($input);
    foreach ($xml->member as $member) {
        $query = "INSERT INTO member (first_name, last_name) VALUES ('$member->first_name', '$member->last_name')";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        mysql_free_result($result);
    }
}

function print_result($query, $root_element_name, $wrapper_element_name) {
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo "<$root_element_name>";
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        echo "<$wrapper_element_name>";
        foreach ($line as $key => $col_value) {
            echo "<$key>$col_value</$key>";
        }
        echo "</$wrapper_element_name>";
    }
    echo "</$root_element_name>";
    mysql_free_result($result);
}

function get_books_borrowed($member_id) {
    $query = "SELECT b.id, b.name, b.author, b.isbn, br.start_date, br.end_date FROM member as m, book as b, borrowing as br WHERE br.member_id = m.id AND br.book_id = b.id AND m.id = $member_id AND br.end_date is NULL";
    $root_element_name = 'books';
    $wrapper_element_name = 'book';
    print_result($query, $root_element_name, $wrapper_element_name);
}

function get_member($member_id) {
    $query = "SELECT m.id, m.first_name, m.last_name FROM member as m WHERE m.id = $member_id";
    $root_element_name = 'members';
    $wrapper_element_name = 'member';
    print_result($query, $root_element_name, $wrapper_element_name);
}

function get_members() {
    $query = "SELECT m.id, m.first_name, m.last_name FROM member as m";
    $root_element_name = 'members';
    $wrapper_element_name = 'member';
    print_result($query, $root_element_name, $wrapper_element_name);
}

function handle_return_book($member_id, $book_id) {
    $today = date("Y-m-d");
    $query = "Update borrowing as br SET end_date = '$today' where br.member_id = $member_id and br.book_id = $book_id";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    mysql_free_result($result);
}

$database = init_database();

// Set the content type to text/xml
header("Content-Type: text/xml");

// Check for the path elements
$path = $_SERVER[PATH_INFO];
if ($path != null) {
    $path_params = spliti("/", $path);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle POST request. Insert the data posted to the database.
    if ($path_params[1] != null && $path_params[2] != null && $path_params[3] != null) {
        if ($path_params[2] == 'books') {
            // a book being borrowed by member
            handle_borrow_book($path_params[1], $path_params[3]);
        }
    } else {
        add_member();
    }
} else
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // Handle GET request. Return the list of members.

        if ($path_params[1] != null) {
            if ($path_params[2] != null) {
                if ($path_params[2] == 'books') {
                    // GET books borrowed by member
                    get_books_borrowed($path_params[1]);
                }
            } else {
                // GET member details for given ID
                get_member($path_params[1]);
            }
        } else {
            // GET all members
            get_members();
        }
    } else
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            // Handle DELETE request. Handle the book return operation.
            if ($path_params[1] != null && $path_params[2] != null && $path_params[3] != null) {
                if ($path_params[2] == 'books') {
                    // a book being returned by member
                    handle_return_book($path_params[1], $path_params[3]);
                }
            }
        }

mysql_close($database);
?>
