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
 
/**
 * Connect to framework.zend.com server and retrieve a greeting
 */
require_once 'Zend/Rest/Client.php';

$client = new Zend_Rest_Client('http://localhost');

$options['method'] = 'sayHello';

$response = $client->restGet('/rest/06/hello.php', $options);

echo htmlspecialchars($response->getBody());

?>