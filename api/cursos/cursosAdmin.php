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
                case 'GET':
                    if (isset($_GET['idcurso'])){
                        $idCurso = limpiarCadena($_GET['idcurso']);
                        $cursos = consultaCursoId($idCurso);
                    } elseif (isset($_GET['nombrecurso'])){
                        $nombreCurso = limpiarCadena($_GET['nombrecurso']);
                        $cursos = consultaCursoNombre($nombreCurso);
                    } else {
                        $cursos = consultaCursos();
                    }
                    echo(json_encode($cursos));
                    http_response_code(200);
                    break;
                case 'POST':
                    $request = json_decode(file_get_contents('php://input'), true);
                    $nombreCurso = limpiarCadena($request['nombrecurso']);
                    $descripcionCurso = limpiarCadena($request['descripcioncurso']);
                    $estadoCreacion = crearCurso($nombreCurso, $descripcionCurso);
                    if ($estadoCreacion) {
                        echo(json_encode('message: curso creado correctamente'));
                        http_response_code(201);
                    } else {
                        echo(json_encode('message: no es posible crear el curso'));
                        http_response_code(400);
                    }
                    break;
                case 'PUT':
                    if (isset($_GET['idcurso'])){
                        $idCurso = limpiarCadena($_GET['idcurso']);
                        $request = json_decode(file_get_contents('php://input'), true);
                        $nombreCurso = limpiarCadena($request['nombrecurso']);
                        $descripcionCurso = limpiarCadena($request['descripcioncurso']);
                        if (actualizarCurso($idCurso, $nombreCurso, $descripcionCurso)){
                            echo(json_encode('message: curso actualizado correctamente'));
                            http_response_code(200);
                        } else {
                            echo(json_encode('message: error de actualizacion'));
                            http_response_code(400);
                        }
                    }
                default:
                    echo(json_encode('message: la peticion no es correcta'));
                    http_response_code(400);
                    break;
            }
        }
    } else {
        echo(json_encode('message: usuario no autenticado'));
        http_response_code(401);
    }


?>