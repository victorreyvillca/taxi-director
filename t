[1mdiff --git a/application/Bootstrap.php b/application/Bootstrap.php[m
[1mindex ff972b6..24b4ec6 100644[m
[1m--- a/application/Bootstrap.php[m
[1m+++ b/application/Bootstrap.php[m
[36m@@ -51,7 +51,7 @@[m [mclass Bootstrap extends Zend_Application_Bootstrap_Bootstrap {[m
     	}[m
 [m
     	// jquery core[m
[31m-    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jquery/jquery.min.js','text/javascript');[m
[32m+[m[41m    [m	[32m$this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jquery/jquery-1.11.0.min.js','text/javascript');[m
     	//         $this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jquery/jquery-2.0.3.min.js','text/javascript');[m
 [m
     	// Jquery ui[m
[1mdiff --git a/application/modules/admin/controllers/RideController.php b/application/modules/admin/controllers/RideController.php[m
[1mindex 634f423..0048fb2 100644[m
[1m--- a/application/modules/admin/controllers/RideController.php[m
[1m+++ b/application/modules/admin/controllers/RideController.php[m
[36m@@ -34,13 +34,14 @@[m [mclass Admin_RideController extends Dis_Controller_Action {[m
 	 * @access public[m
 	 */[m
 	public function addAction() {[m
[32m+[m	[32m    //$this->_helper->viewRenderer->setNoRender(TRUE);[m
 		$this->_helper->layout()->disableLayout();[m
 [m
[31m-		$taxiRepo = $this->_entityManager->getRepository('Model\Taxi');[m
[31m-		$taxisArray = $taxiRepo->findByStatusArrayNumber(Taxi::WITHOUT_CAREER);[m
[32m+[m[32m       $taxiRepo = $this->_entityManager->getRepository('Model\Taxi');[m
[32m+[m[32m        $taxisArray = $taxiRepo->findByStatusArrayNumber(Taxi::WITHOUT_CAREER);[m
 [m
 		$form = new Dis_Form_Ride();[m
[31m-		$form->getElement('taxi')->setMultiOptions($taxisArray);[m
[32m+[m[32m        $form->getElement('taxi')->setMultiOptions($taxisArray);[m
 [m
 		$this->view->form = $form;[m
 	}[m
