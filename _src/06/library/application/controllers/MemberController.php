<?php
require_once 'Zend/Rest/Server.php';

function getMembers($member_list) {
    $members = array ();
    $result = '<?xml version="1.0" encoding="UTF-8"?><members>';
    foreach ($member_list as $member) {
        $result .= "<member><id>" . $member->id . "</id>" .
        "<first_name>" . $member->first_name . "</first_name>" .
        "<last_name>" . $member->last_name . "</last_name></member>";
    }
    $result .= "</members>";

    $xml = simplexml_load_string($result);
    return $xml;
}

class MemberController extends Zend_Controller_Action {

    function indexAction() {
        $members = new Members();
        $this->_helper->viewRenderer->setNoRender();

        if ($this->_request->isGet()) {
            $server = new Zend_Rest_Server();
            $server->addFunction('getMembers');
            $params['method'] = 'getMembers';
            $params['member_list'] = $members->fetchAll();
            $server->handle($params);
        } else
            if ($this->_request->isPost()) {
                $xml = simplexml_load_string($this->_request->getRawBody());
                foreach ($xml->member as $member) {
                    $row = $members->createRow();
                    $row->first_name = $member->first_name;
                    $row->last_name = $member->last_name;
                    $row->save();
                }
            }
    }
}

