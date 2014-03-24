<?php

class ServerController extends Zend_Controller_Action
{

    public function indexAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        $server = new Zend_XmlRpc_Server();
        $server->setClass('Dis_Model_Data');

        echo $server->handle();
    }

    public function jsonAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        $server = new Zend_Json_Server();
        $server->setClass('Dis_Model_Data', 'cf');

        if ('GET' == $_SERVER['REQUEST_METHOD']) {
            $server->setTarget('http://localhost/server/json')->setEnvelope(
                    Zend_Json_Server_Smd::ENV_JSONRPC_2);
            $smd = $server->getServiceMap();

            header('Content-Type: application/json');
            echo $smd;
            return;
        }

        echo $server->handle();
    }

    public function recibirdatoAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        $server = new Zend_Json_Server();
        $server->setClass('Dis_Model_Taxi', 'cf');

        if ('GET' == $_SERVER['REQUEST_METHOD']) {
            $server->setTarget('http://localhost/server/recibirdato')->setEnvelope(
                    Zend_Json_Server_Smd::ENV_JSONRPC_2);
            $smd = $server->getServiceMap();
            header('Content-Type: application/json');
            // var_dump($this->_request->getParams());

            echo $smd;
            return;
        }
        // $latitud = $this->_request->getParam("latitud");
        // $latitud = $this->_request->getParam("longitud");
        // $latitud = $this->_request->getParam("time");
        echo $server->handle();
    }
}