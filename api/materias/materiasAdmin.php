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
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    $request = json_decode(file_get_contents('php://input'), true);
                    $nombreMateria = limpiarCadena($request['nombremateria']);
                    $descripcionMateria = limpiarCadena($request['descripcionmateria']);
                    $idProfesor = limpiarCadena($request['idprofesor']);
                    if (crearMateria($nombreMateria, $descripcionMateria, $idProfesor)) {
                        echo(json_encode('message: materia creada satisfactoriamente'));
                        http_response_code(201);
                    } else {
                        echo(json_encode('message: no es posible crear la materia'));
                        http_response_code(400);
                    }
                    break;
                case 'GET':
                    if (isset($_GET['idmateria'])) {
                        $idMateria = limpiarCadena($_GET['idmateria']);
                        $materias = consultarMateriasId($idMateria);
                        
                    } elseif(isset($_GET['nombremateria'])) {
                        $nombreMateria = limpiarCadena($_GET['nombremateria']);
                        $materias = consultarMateriasNombres($nombreMateria);
                    } else {
                        $materias = consultarMaterias();
                    }
                    echo(json_encode($materias));
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