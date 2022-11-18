<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require '../../vendor/phpmailer/phpmailer/src/Exception.php';
	require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
	require '../../vendor/phpmailer/phpmailer/src/SMTP.php';

	require '../../vendor/autoload.php';

	function limpiarCadena($cadena){
	    $patron = array('/<script>.*<\/script>/');
	    $cadena = preg_replace($patron, '',$cadena);
	    $cadema = htmlspecialchars($cadena);
	    return $cadena;
	}

	function enviarCorreo($aleatorio, $correoElectronico){
		echo $aleatorio;
		echo $correoElectronico;
		try{
			$mail = new PHPMailer;

			$mail -> isSMTP();
			$mail -> SMTPDebug = 2;
			$mail -> Host = 'smtp.hostinger.com';
			$mail -> Port = 587;
			$mail -> SMTPAuth = true;
			$mail -> Username = 'recupera@jdiegocanon.com';
			$mail -> Password = 'Diego4023.';
			$mail -> setFrom('recupera@jdiegocanon.com', 'Recuperacion de contraseña jdiegocanon.com');
			$mail -> addReplyTo('recupera@jdiegocanon.com', 'recuperacion');
			$mail -> addAddress($correoElectronico, '');
			$mail -> Subject = 'Codigo de seguridad';
			$mail -> Body = $aleatorio;
			if($mail -> send()){
				return true;
			} else {
				return false;
			}
		} catch (Exception $e) {
			echo "error de envio : {$mail->ErrorInfo}";
			return false;
		}
	}

  function regenerarCookie()	{
		{
		// Obliga a la sesión a utilizar solo cookies.
		// Habilitar este ajuste previene ataques que impican pasar el id de sesión en la URL.
		if (ini_set('session.use_only_cookies', 1) === FALSE) {
			$action = "error";
			$error = "No puedo iniciar una sesion segura (ini_set)";
		}

		// Obtener los parámetros de la cookie de sesión
		$cookieParams = session_get_cookie_params();
		$path = $cookieParams["path"];

		// Inicio y control de la sesión
		$secure = false;
		$httponly = true;
		$samesite = 'strict';

		session_set_cookie_params([
			'lifetime' => $cookieParams["lifetime"],
			'path' => $path,
			'domain' => $_SERVER['HTTP_HOST'],
			'secure' => $secure,
			'httponly' => $httponly,
			'samesite' => $samesite
		]);

		session_start();
		session_regenerate_id(true);
		}
	}

  function alertJs($mensaje){
    return "<script>alert('".$mensaje."');</script>";
  }

  function cargarPag($pag){
    echo "<script>window.location.href('".$pag."');</script>";
  }
?>
