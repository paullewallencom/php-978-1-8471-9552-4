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
 
require_once 'Zend/Rest/Client.php';

class IndexController extends Zend_Controller_Action {

    function indexAction() {
        $this->view->title = "Books";
        $client = new Zend_Rest_Client('http://localhost');
        $response = $client->restGet('/rest/06/library/public/index.php/book');

        $this->view->books = simplexml_load_string($response->getBody());
    }

    function addbookAction() {
        $this->view->title = "Add New Book";

        $form = new BookForm();
        $form->submit->setLabel('Add');
        $this->view->form = $form;

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $client = new Zend_Rest_Client('http://localhost');

                $request = '<?xml version="1.0" encoding="UTF-8"?><books>';
                $request .= "<book><name>" . $form->getValue('name') . "</name>" .
                "<author>" . $form->getValue('author') . "</author>" .
                "<isbn>" . $form->getValue('isbn') . "</isbn></book>";
                $request .= "</books>";

                $response = $client->restPost('/rest/06/library/public/index.php/book', $request);
                $this->_redirect('/');
            }
        }
    }

    function membersAction() {
        $this->view->title = "Members";
        $client = new Zend_Rest_Client('http://localhost');
        $response = $client->restGet('/rest/06/library/public/index.php/member');

        $this->view->members = simplexml_load_string($response->getBody());
    }

    function addmemberAction() {
        $this->view->title = "Add New Member";

        $form = new MemberForm();
        $form->submit->setLabel('Add');
        $this->view->form = $form;

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $client = new Zend_Rest_Client('http://localhost');

                $request = '<?xml version="1.0" encoding="UTF-8"?><members>';
                $request .= "<member><first_name>" . $form->getValue('first_name') . "</first_name>" .
                "<last_name>" . $form->getValue('last_name') . "</last_name></member>";
                $request .= "</members>";

                $xml = simplexml_load_string($request);

                $response = $client->restPost('/rest/06/library/public/index.php/member', $request);
                $this->_redirect('/index/members');
            }
        }
    }

}