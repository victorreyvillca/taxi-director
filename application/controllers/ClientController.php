<?php

class ClientController extends Zend_Controller_Action {

    public function init() {
    	/* Initialize action controller here */
    }

    public function indexAction() {
//         $client = new Zend_XmlRpc_Client('http://localhost/taxi-director/public/server');
        $client = new Zend_XmlRpc_Client('http://localhost/server');

        try {
//         	$data = $client->call('cf.test');
        	$data = $client->call('cf.getData', 5);
        	$this->view->data = $data;
        	$httpClient = $client->getHttpClient();
        	$response = $httpClient->getLastResponse();
        	echo(strlen($response->getRawBody())/1024);
        } catch (Zend_XmlRpc_Client_HttpException $e) {
        	require_once 'Zend/Exception.php';
        	throw new Zend_Exception($e);
        } catch (Zend_XmlRpc_Client_FaultException $e) {
        	require_once 'Zend/Exception.php';
        	throw new Zend_Exception($e);
        }
    }

    public function jsonAction()
    {
//     	$this->view->serverUrl = 'http://localhost/taxi-director/public/server/json';
    	$this->view->serverUrl = 'http://localhost/server/json';
    	$this->view->dataNumber = 200;
    }
}