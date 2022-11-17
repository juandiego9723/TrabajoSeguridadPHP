<?php

    require_once('../tools/functions.php');
	require_once('../tools/db.php');
	require_once('../tools/security.php');

    header('Access-Control-Allow-Origin: *');
    
    if (!empty($_SERVER['HTTP_AUTHORIZATION'])){
        $jwt = substr($_SERVER['HTTP_AUTHORIZATION'], 7);
        $usuarioAuth = decifrarToken($jwt);
        if ($usuarioAuth == null){
            echo(json_encode('message: error en la autenticacion de usuario'));
            http_response_code(401);	
        } else {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    $cursosAlumno = consultaCursoAlumno($usuarioAuth);
                    echo(json_encode($cursosAlumno));
                    http_response_code(200);
                default:
                    # code...
                    break;
            }
        }
    } else {
        echo(json_encode('message: usuario no autenticado'));
        http_response_code(401);
    }

?>