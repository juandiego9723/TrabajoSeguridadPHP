<?php
    require_once('../tools/functions.php');
	require_once('../tools/db.php');
	require_once('../tools/security.php');

    header('Access-Control-Allow-Origin: *');
    
    if (!empty($_SERVER['HTTP_AUTHORIZATION'])){
        $jwt = substr($_SERVER['HTTP_AUTHORIZATION'], 7);
        $usuarioAuth = decifrarTokenAdmin($jwt);
        if ($usuarioAuth == null){
            echo(json_encode('message: error en la autenticacion de usuario'));
            http_response_code(401);	
        } else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $request = json_decode(file_get_contents('php://input'), true);
                $idAlumno = limpiarCadena($request['idalumno']);
                $idCurso = limpiarCadena($request['idcurso']);
                if (asignarAlumno($idCurso, $idAlumno)){
                    echo(json_encode('message: curos asignado correctamente'));
                    http_response_code(201);
                } else {
                    echo(json_encode('message: error de asignacion de curso'));
                    http_response_code(400);
                }
            }
        }
    } else {
        echo(json_encode('message: usuario no autenticado'));
        http_response_code(401);
    }

?>