<?php
  require('../../api/tools/functions.php');
	require('../../api/tools/db.php');
	require('../../api/tools/security.php');

  if (isset($_POST['brnRecuperarClave'])) {
    if (!empty($_POST['txtUsuario']) and !empty($_POST['txtCodigo']) and !empty($_POST['txtClave'])) {
      $usuario = limpiarCadena($_POST['txtUsuario']);
      $codigo = limpiarCadena($_POST['txtCodigo']);
      $clave = md5(limpiarCadena($_POST['txtClave']));
      if (actualizaClaveRecuperada($usuario, $codigo, $clave)) {
        $alert = alertJs('claveActualizada');
        echo($alert);
        sleep(3);
        header('location: login.php');
      } else {
        echo(alertJs('no es podible procesar la solicitud'));
      }
    } else {
      echo(alertJs('no ha ingresado datos'));
    }

  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <div>
    recuperacion de clave
    <form action="" method="POST">
      <table>
        <tr>
          <td>Usuario</td>
          <td><input type="text" name="txtUsuario"></td>
        </tr>
        <tr>
          <td>Codigo</td>
          <td><input type="password" name="txtCodigo"></td>
        </tr>
        <tr>
          <td>Clave</td>
          <td><input type="password" name="txtClave"></td>
        </tr>
        <tr>
          <td><input type="submit" value="Recuperar Clave" name="brnRecuperarClave"></td>
        </tr>
      </table>
    </form>
  </div>
</body>
</html>
