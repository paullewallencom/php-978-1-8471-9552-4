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
 
require_once 'Zend/Rest/Server.php';

function getBooks($book_list) {
    $result = '<?xml version="1.0" encoding="UTF-8"?><books>';
    foreach ($book_list as $book) {
        $result .= "<book><id>" . $book->id . "</id>" .
        "<name>" . $book->name . "</name>" .
        "<author>" . $book->author . "</author>" .
        "<isbn>" . $book->isbn . "</isbn></book>";
    }
    $result .= "</books>";

    $xml = simplexml_load_string($result);
    return $xml;
}

class BookController extends Zend_Controller_Action {

    function indexAction() {
        $books = new Books();
        $this->_helper->viewRenderer->setNoRender();

        if ($this->_request->isGet()) {
            $server = new Zend_Rest_Server();
            $server->addFunction('getBooks');
            $params['method'] = 'getBooks';
            $params['book_list'] = $books->fetchAll();
            $server->handle($params);
        } else
            if ($this->_request->isPost()) {
                $xml = simplexml_load_string($this->_request->getRawBody());
                foreach ($xml->book as $book) {
                    $row = $books->createRow();
                    $row->name = $book->name;
                    $row->author = $book->author;
                    $row->isbn = $book->isbn;
                    $row->save();
                }
            }
    }
}

