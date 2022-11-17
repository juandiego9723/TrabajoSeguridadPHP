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
                    $tipoIdentificacion = limpiarCadena($request['tipoidentificacion']);
                    $numeroIdentificacion = limpiarCadena($request['numeroidentificacion']);
                    $nombreProfesor = limpiarCadena($request['nombreprofesor']);
                    $apellidoProfesor = limpiarCadena($request['apellidoprofesor']);
                    $direccion = limpiarCadena($request['direccion']);
                    $idCiudad = limpiarCadena($request['idciudad']);
                    $telefono = limpiarCadena($request['telefono']);
                    $correoElectronico = limpiarCadena($request['correoelectronico']); 
                    $estadoCreacion = crearProfesor(
                        $tipoIdentificacion, $numeroIdentificacion, $nombreProfesor, $apellidoProfesor,
                        $direccion, $idCiudad, $telefono, $correoElectronico
                    );
                    if ($estadoCreacion) {
                        echo(json_encode('message: usuario creado satisfactoriamente'));
                        http_response_code(201);
                    }
                    break;
                case 'GET':
                    if(isset($_GET['idprofesor'])){
                        $idProfesor = limpiarCadena($_GET['idprofesor']);
                        $profesor = consultaProfesorId($idProfesor) ;
                        echo(json_encode($profesor));
                        http_response_code(200);
                        break;
                    } elseif (isset($_GET['nombreprofesor'])) {
                        $nombreProfesor = limpiarCadena($_GET['nombreprofesor']);
                        $profesores = consultaProfesorNombre($nombreProfesor);
                        echo(json_encode($profesores));
                        http_response_code(200);

                    } else {
                        echo(json_encode('message: no es posible procesar la solicitud'));
                        http_response_code(400);
                    }

                    break;
                default:
                    echo(json_encode('message: no ha seleccionado un metodo valido'));
                    http_response_code(400);
            }
        }
    } else {
        echo(json_encode('message: usuario no autenticado'));
        http_response_code(401);
    }

?>