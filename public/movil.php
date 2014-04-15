<?php

if (isset($_POST['tag']) && $_POST['tag'] != '') {
    // get tag
    $tag = $_POST['tag'];

    // response Array
    $response = array("success" => "OK");

    switch ($tag) {
        case 'registrar':
				//*--- recepcion de datos
				$tel = $_POST['tel'];
				//*--- envio de datos
				$response["success"] = "OK";//vacio en caso de error
				$response["codigoactivacion"] = "adsvsdfsvsdvdsvsersdcsSFSbsdfs";
				$response["codigouser"] = "98789";
            echo json_encode($response);
            break;
		case 'estaactivado':
				//*--- recepcion de datos
				$tel = $_POST['tel'];
				$codigoactivacion = $_POST['codigoactivacion'];
				//*--- envio de datos
				$response["success"] = "OK";
            echo json_encode($response);
            break;
		case 'envioposicion':
				//*--- recepcion de datos
				$tel = $_POST['tel'];
				$codigoactivacion = $_POST['codigoactivacion'];
				$lat = $_POST['lat'];
				$long = $_POST['long'];
				$tmp = $_POST['tmp'];
				$estado = $_POST['estado'];

				//*--- envio de datos
				$response["success"] = "OK";//vacio en caso de error
				$response["peticion"] = "OK"; //si hay una petición para asignar carrera, envía "OK" y esto sólo ocurre cuando el estado enviado por el taxi, sea libre (0). En este momento el taxi deberá cambiar a estado ocupado. En otro caso se envía vacío "". Recordar que la aplicación android debe cambiar de libre a ocupado e, inmediatamente, informar su cambio de estado. Ver el diagráma de estados mostrado arriba.
            echo json_encode($response);
         break;
		case 'cambioestado':
				//*--- recepcion de datos
				$tel = $_POST['tel'];
				$codigoactivacion = $_POST['codigoactivacion'];
				$estado = $_POST['estado'];

				//*--- envio de datos
				$response["success"] = "OK";//vacio en caso de error
				$response["peticion"] = "OK"; //si hay una petición para asignar carrera, envía "OK" y esto sólo ocurre cuando el estado enviado por el taxi, sea libre (0). En este momento el taxi deberá cambiar a estado ocupado. En otro caso se envía vacío "". Recordar que la aplicación android debe cambiar de libre a ocupado e, inmediatamente, informar su cambio de estado. Ver el diagráma de estados mostrado arriba.


            echo json_encode($response);
           break;
    }
}
?>