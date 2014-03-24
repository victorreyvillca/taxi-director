<?php

class ClientController extends Zend_Controller_Action
{

    public function init ()
    {
        /* Initialize action controller here */
    }

    public function indexAction ()
    {
        // $client = new
        // Zend_XmlRpc_Client('http://localhost/taxi-director/public/server');
        $client = new Zend_XmlRpc_Client('http://localhost/server');

        try {
            // $data = $client->call('cf.test');
            $data = $client->call('getData', 10);
            $this->view->data = $data;
            $httpClient = $client->getHttpClient();
            $response = $httpClient->getLastResponse();
            echo (strlen($response->getRawBody()) / 1024);
        } catch (Zend_XmlRpc_Client_HttpException $e) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception($e);
        } catch (Zend_XmlRpc_Client_FaultException $e) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception($e);
        }
    }

    public function jsonAction ()
    {
        // $this->view->serverUrl =
        // 'http://localhost/taxi-director/public/server/json';
//         $this->view->serverUrl = 'http://localhost/server/json';
        $this->view->serverUrl = 'http://localhost/server/json';
        $this->view->dataNumber = 15;
    }

    public function pintarAction ()
    {
//         $data = array(array('value' => 1,'value2' => 2, 'value3' => 3));
//         $this->stdResponse = new stdClass();
//         $this->stdResponse->passenger = array(1,3,4);
//         $this->_helper->json($this->stdResponse);

//         foreach ($data as $value) {
//         	$this->stdResponse = new stdClass();
//             $this->stdResponse->passenger =$value;
//             $this->_helper->json($this->stdResponse);;
//             sleep(1000);
//         }
    }

    public function drawAction() {
        $client = new Zend_XmlRpc_Client('http://localhost/server');

        try {
            // $data = $client->call('cf.test');
            $data = $client->call('getData', 3);
            $this->view->data = $data;
            $httpClient = $client->getHttpClient();
            $response = $httpClient->getLastResponse();
            echo (strlen($response->getRawBody()) / 1024);
        } catch (Zend_XmlRpc_Client_HttpException $e) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception($e);
        } catch (Zend_XmlRpc_Client_FaultException $e) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception($e);
        }
    }
}