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
 
class BookForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('book');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');

        $author = new Zend_Form_Element_Text('author');
        $author->setLabel('Author')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');

		$isbn = new Zend_Form_Element_Text('isbn');
        $isbn->setLabel('ISBN')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');
        
        $id = new Zend_Form_Element_Hidden('id');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($id, $name, $author, $isbn, $submit));
    }
}