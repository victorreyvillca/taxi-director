<?php
use Model\Backtrack;

/**
 * Controller for Taxi Director.
 *
 * @category Dist
 * @author Victor Villca <victor.villca.v@gmail.com>
 * @copyright Copyright (c) 2014 LeaderSoft A/S
 * @license Proprietary
 */
class MovilController extends Dis_Controller_Action
{

    public function init ()
    {
        /* Initialize action controller here */
    }

    public function indexAction ()
    {
        if (isset($_POST['tag']) && $_POST['tag'] != '') {
            // get tag
            $tag = $_POST['tag'];

            // response Array
            $response = array(
                    "success" => ''
            );

            switch ($tag) {
                case 'registrar':
                    // *--- recepcion de datos
                    $phone = $_POST['tel'];
                    $taxiRepo = $this->_entityManager->getRepository(
                            'Model\Taxi');
                    if ($taxiRepo->verifyExistPhone($phone)) {
                        $codeuser = substr(
                        str_shuffle(str_repeat('123456789', 2)), 0, 5);
                        $codeactivation = $this->generateKey();

                        $response["success"] = "OK"; // vacio en caso de errorad
                        $response["codigoactivacion"] = $codeactivation;
                        $response["codigouser"] = $codeuser;

                        $taxi = $taxiRepo->findByPhone($phone);

                        $taxi->setCodeactivation($codeactivation)->setCodeuser(
                                $codeuser);

                        $this->_entityManager->persist($taxi);
                        $this->_entityManager->flush();
                    } else {
                        $response["success"] = ""; // vacio en caso de error
                    }
                    // *--- envio de datos

                    $this->_helper->json($response);

                    break;
                case 'estaactivado':
                    // *--- recepcion de datos
                    $phone = $_POST['tel'];
                    $codigoactivacion = $_POST['codigoactivacion'];
                    // *--- envio de datosverifyActivavionTaxi
                    $response["success"] = "";

                    $taxiRepository = $this->_entityManager->getRepository(
                            'Model\Taxi');
                    if ($taxiRepository->verifyActivavionTaxi($codigoactivacion,
                            $phone)) {
                        $response["success"] = "OK";
                    }
                    $this->_helper->json($response);
                    break;
                case 'envioposicion':
                    // *--- recepcion de datos
                    $tel = $_POST['tel'];
                    $codigoactivacion = $_POST['codigoactivacion'];
                    $latitud = $_POST['lat'];
                    $longitud = $_POST['long'];
                    $timenow = $_POST['tmp'];
                    $status = $_POST['estado'];
                    // *--OBTENER MODEL TAXIL
                    $taxiRepo = $this->_entityManager->getRepository('Model\Taxi');
                    $taxi = $taxiRepo->findByPhoneCodigoActivacion($tel, $codigoactivacion);
                    if ($taxi != NULL) {
                        $taxi->setStatus($status);

                        $backtrack = new Backtrack();
                        $backtrack->setTaxi($taxi)
                            ->setLatitud($latitud)
                            ->setLongitud($longitud)
                            ->setTimenow(new DateTime('now'))
                            ->setStatus($status)
                            ->setCreated(new DateTime('now'))
                            ->setState(TRUE);

                        $this->_entityManager->persist($taxi);
                        $this->_entityManager->flush();

                        $this->_entityManager->persist($backtrack);
                        $this->_entityManager->flush();

                        // *--- envio de datos
                        $response["success"] = "OK"; // vacio en caso de error
                        $response["peticion"] = ""; // si hay una petición para
                                                      // asignar carrera, envía "OK"
                                                      // y esto sólo ocurre cuando
                                                      // el estado enviado por el
                                                      // taxi, sea libre (0). En
                                                      // este momento el taxi deberá
                                                      // cambiar a estado ocupado.
                                                      // En otro caso se envía vacío
                                                      // "". Recordar que la
                                                      // aplicación android debe
                                                      // cambiar de libre a ocupado
                                                      // e, inmediatamente, informar
                                                      // su cambio de estado. Ver el
                                                      // diagráma de estados
                                                  // mostrado arriba.
                    }

                    $this->_helper->json($response);

                    break;
            }
        }
    }

    /**
     * This method generates the key to request password, and returns if it is
     * not duplicate
     *
     * @return string (random key)
     */
    public function generateKey ()
    {
        $sw = TRUE;
        do {
            $key = substr(
                    str_shuffle(
                            str_repeat(
                                    'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789',
                                    4)), 0, 32);
            $taxiRepo = $this->_entityManager->getRepository('Model\Taxi');
            $taxi = $taxiRepo->findByCodeactivation($key);

            if ($taxi == NULL) {
                $sw = FALSE;
                break;
            }
        } while ($sw);

        return $key;
    }
}