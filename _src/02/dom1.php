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

$xmlstr = <<<XML
<books>
    <book>
        <title/>
    </book>
</books>
XML;

$doc = new DOMDocument;
$doc->preserveWhiteSpace = false;
$doc->loadXML($xmlstr);


$books = $doc->getElementsByTagName('book');
$books->item(0)->setAttribute('type', 'Computer');

$books->item(0)->childNodes->item(0)->nodeValue = 'PHP Web Services';

$author_node = $doc->createElement('author');
$books->item(0)->appendChild($author_node);

$name_node = $doc->createElement('name');
$name_node->nodeValue = 'Sami';
$author_node->appendChild($name_node);

echo $doc->saveXML();
?> 