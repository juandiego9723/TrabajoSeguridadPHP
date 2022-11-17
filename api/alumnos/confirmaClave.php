<?php
    require('../tools/functions.php');
    require('../tools/db.php');

    header('Access-Control-Allow-Origin: *');

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $request = json_decode(file_get_contents('php://input'), true);
        $usuario = limpiarCadena($request['usuario']);
        $codigo = limpiarCadena($request['codigoSeguridad']);
        $clave = md5(limpiarCadena($request['clave']));
        if (actualizaClaveRecuperada($usuario, $codigo, $clave)) {
            echo(json_encode('{message: clave restablecida}'));
            http_response_code(200);
        } else {
            echo(json_encode('{message: no es posible procesar la solicitud}'));
            http_response_code(400);
        }

    } else {
        echo(json_encode('{message: no es posible procesar la solicitud}'));
        http_response_code(400);
    }

?>