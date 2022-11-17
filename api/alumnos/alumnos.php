<?php
	
	require('../tools/functions.php');
	require('../tools/db.php');
	require('../tools/security.php');

	header('Access-Control-Allow-Origin: *');
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
		$request = json_decode(file_get_contents('php://input'), true);
		// asignacion de datos para la creacion de usuario
		$tipoIdentificacion = limpiarCadena($request['tipoIdentificacion']);
		$numeroIdentificacion = limpiarCadena($request['numeroIdentificacion']);
		$nombreAlumno = limpiarCadena($request['nombreAlumno']);
		$apellidoAlumno = limpiarCadena($request['apellidoAlumno']);
		$correElectronico = limpiarCadena($request['correElectronico']);
		$telefono = limpiarCadena($request['telefono']);
		$direccion = limpiarCadena($request['direccion']);
		$ciudad = limpiarCadena($request['ciudad']);
		$usuario = limpiarCadena($request['usuario']);
		$clave = md5(limpiarCadena($request['clave']));
		// envio a guardar
		$estadoInsersion = crearAlumno(
			$tipoIdentificacion, $numeroIdentificacion, 
			$nombreAlumno, $apellidoAlumno, $correElectronico, 
			$telefono, $direccion, $ciudad, $usuario, $clave);
		if ($estadoInsersion){
			echo(json_encode('message: usuario creado correctamente'));
			http_response_code(201);
		} else{
			echo(json_encode('message: no es posible crear el usuario'));
			http_response_code(400);
		}
	} else {
		if (!empty($_SERVER['HTTP_AUTHORIZATION'])){
			$jwt = substr($_SERVER['HTTP_AUTHORIZATION'], 7);
			$usuarioAuth = decifrarToken($jwt);
			if ($usuarioAuth == null){
				echo(json_encode('message: error en la autenticacion de usuario'));
				http_response_code(401);	
			} else {
				switch ($_SERVER['REQUEST_METHOD']){
					case 'GET':
						if (isset($_GET['numeroidentificacion'])) {
							$numeroIdentificacion = limpiarCadena($_GET['numeroidentificacion']);
							$alumno = consultaAlumnoIdentificacion($numeroIdentificacion);
							echo(json_encode($alumno));
							http_response_code(200);
						} elseif (isset($_GET['nombrealumno'])){
							$nombreAlumno = limpiarCadena($_GET['nombrealumno']);
							$alumno = consultaAlumnoNombre($nombreAlumno);
							echo(json_encode($alumno));
							http_response_code(200);
						} elseif (isset($_GET['apellidoalumno'])){
							$apellidoAlumno = limpiarCadena($_GET['apellidoalumno']);
							$alumno = consultaAlumnoApellido($apellidoAlumno);
							echo(json_encode($alumno));
							http_response_code(200);
						}
						else{
							$alumnos = consultaAlumnosTotal();
							echo(json_encode($alumnos));
							http_response_code(200);
						}
						break;						
					case 'PUT':
						$request = json_decode(file_get_contents('php://input'), true);

						$tipoIdentificacion = limpiarCadena($request['tipoIdentificacion']);
						$numeroIdentificacion = limpiarCadena($request['numeroIdentificacion']);
						$nombreAlumno = limpiarCadena($request['nombreAlumno']);
						$apellidoAlumno = limpiarCadena($request['apellidoAlumno']);
						$correElectronico = limpiarCadena($request['correElectronico']);
						$telefono = limpiarCadena($request['telefono']);
						$direccion = limpiarCadena($request['direccion']);
						$ciudad = limpiarCadena($request['ciudad']);
						$clave = md5(limpiarCadena($request['clave']));
						if (actualizarAlumno(
								$tipoIdentificacion, $numeroIdentificacion, $nombreAlumno, $apellidoAlumno, 
								$correElectronico, $telefono, $direccion, $ciudad, $clave, $usuarioAuth
							)
						){
							echo(json_encode('message: usuario actualizado correctamente'));
							http_response_code(200);
						} else {
							echo(json_encode('message: error al actualizar el usuario'));
							http_response_code(400);
						}
						break;
						
					default:
						echo(json_encode('message: esta realizando una peticion incorrecta'));
						http_response_code(400);
						break;
				}
			}
		} else{
			
			echo(json_encode('message: no se ha proporcionado la autenticacion'));
			http_response_code(401);
		}
		
	}
?>