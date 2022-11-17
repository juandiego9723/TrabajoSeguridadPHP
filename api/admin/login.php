<?php
    require('../tools/functions.php');
	require('../tools/db.php');
	require('../tools/security.php');

    header('Access-Control-Allow-Origin: *');

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $request = json_decode(file_get_contents('php://input'), true);
        if (isset($request['usuario']) && isset($request['clave'])) {
            $usuario = limpiarCadena($request['usuario']);
            $clave = md5(limpiarCadena($request['clave']));
            $usuarioSesion = inicioSesionAdmin($usuario, $clave);
            if ($usuarioSesion == null){
                echo(json_encode('message: usuario o clave son incorrectos'));
                http_response_code(401);
            } else{
                $jwt = crearToken($usuarioSesion);
                echo(json_encode($jwt));
                http_response_code(200);
            }
        } else {
            echo(json_encode("message: se necesita el usuario y la clave"));
            http_response_code(400);    
        }
    } else{
        echo(json_encode("message: no es permitido el metodo"));
        http_response_code(401);
    }
?>