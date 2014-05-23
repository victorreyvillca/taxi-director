<?php

/**
 * Controller for DIST 2.
 *
 * @category Dist 2
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_IndexController extends Dis_Controller_Action {

	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
 	public function init() {
 		parent::init();
		$uri = $this->_request->getPathInfo();
    }

	public function indexAction() {

	}

	public function actionViewdraw($id) {

		$criteria = new CDbCriteria();
		$criteria->condition = "linId = $id";
		$dataProvider = new CActiveDataProvider('rutas', array('criteria' => $criteria));
		$data = array();
		$i = 0;
		$iterator = new CDataProviderIterator($dataProvider);
		foreach ($iterator as $user) {
			//            echo $user->rutLatitud . "\n";
			$aux["latitud"] = $user->rutLatitud;
			$aux["longitud"] = $user->rutLongitud;
			$data[$i] = $aux; //CJavaScript::jsonEncode($aux);

			$i = $i + 1;
		}
// 		//        print_r($data);

// 		echo CJavaScript::jsonEncode($data);
// 		Yii::app()->end();
	}

	/**
	 * Draws the position of the taxis
	 * @access public
	 */
	public function drawAction() {
	    $this->_helper->viewRenderer->setNoRender(TRUE);

	    $taxiId = $this->_getParam('taxiId', 0);
	    $taxi = $this->_entityManager->find('Model\Taxi', $taxiId);

	    $data = array();
	    if ($taxi != NULL) {
	        $backtrackRepo = $this->_entityManager->getRepository('Model\Backtrack');
	        $routes = $backtrackRepo->findByTaxi($taxi);
	        foreach ($routes as $route) {
	            $row = array();
	            $row['latitud'] = $route->getLatitud();
	            $row['longitud'] = $route->getLongitud();
                $data[] = $row;
	        }
	    }

	    $this->stdResponse = new stdClass();
	    $this->stdResponse = $data;
	    $this->_helper->json($this->stdResponse);
	}


	public function drawTaxisAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$backtrackRepo = $this->_entityManager->getRepository('Model\Backtrack');

		$taxiRepo = $this->_entityManager->getRepository('Model\Taxi');
        $taxis = $taxiRepo->findByStatus(Taxi::WITHOUT_CAREER);

        $data = array();
        foreach ($taxis as $taxi) {
            $route = $backtrackRepo->findLastPositionByTaxi($taxi);
            if ($route != NULL) {
            	$row = array();
                $row['latitud'] = $route->getLatitud();
                $row['longitud'] = $route->getLongitud();
                $data[] = $row;;
            }
        }

		$this->stdResponse = new stdClass();
		$this->stdResponse = $data;
		$this->_helper->json($this->stdResponse);
	}

	public function drawTaxiAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$taxiId = $this->_getParam('taxiId', 0);
		$taxi = $this->_entityManager->find('Model\Taxi', $taxiId);

		$data = array();
		if ($taxi != NULL) {
			$backtrackRepo = $this->_entityManager->getRepository('Model\Backtrack');
			$routes = $backtrackRepo->findLastPositionByTaxi($taxi);
			foreach ($routes as $route) {
				$row = array();
				$row['latitud'] = $route->getLatitud();
				$row['longitud'] = $route->getLongitud();
				$data[] = $row;
			}
		}

		$this->stdResponse = new stdClass();
		$this->stdResponse = $data;
		$this->_helper->json($this->stdResponse);
	}
}