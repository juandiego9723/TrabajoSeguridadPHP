<?php
    require('../tools/functions.php');
	require('../tools/db.php');

    header('Access-Control-Allow-Origin: *');
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $request = json_decode(file_get_contents('php://input'), true);
        $usuario = limpiarCadena($request['usuario']);
        $clave = md5(limpiarCadena($request['clave']));
        $estadoRegAdmin = regAdmin($usuario, $clave);
        if ($estadoRegAdmin){
            echo(json_encode('message: administrador creado correctamente'));
            http_response_code(201);
        } else {
            echo(json_encode('message: no es posible crear el administrador'));
            http_response_code(400);
        }
    } else {
        echo(json_encode('message: no es posible crear el administrador'));
        http_response_code(400);
    }
?>