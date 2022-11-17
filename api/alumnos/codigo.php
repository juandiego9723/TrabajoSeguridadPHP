<?php
    // generacion de codigo
    require('../tools/functions.php');
	require('../tools/db.php');
	require('../tools/security.php');

    header('Access-Control-Allow-Origin: *');
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $request = $request = json_decode(file_get_contents('php://input'), true);
        $correoElectronico = limpiarCadena($request['correoElectronico']);
        $usuario = limpiarCadena($request['usuario']);
        if(consultaUSuariorecuperacionClave($correoElectronico, $usuario)){
            $aleatorio = rand(1,9999);
            if (guardarCodigoSeguridad($usuario, $aleatorio)) {
                if (enviarCorreo($aleatorio, $correoElectronico)) {
                    echo(json_encode('{message: codigo de verificacion creado correctamente}'));
                    http_response_code(200);
                } else {
                    echo(json_encode('{message: no es posible procesar la solicitud}'));
                    http_response_code(400);
                }
            } else {
                echo(json_encode('{message: no es posible procesar la solicitud}'));
                http_response_code(400);
            }
            
        } else {
            echo(json_encode('{message: no es posible procesar la solicitud}'));
            http_response_code(400);
        }
    } else {
        echo(json_encode('{message: no es posible procesar la solicitud}'));
        http_response_code(400);
    }
?>  