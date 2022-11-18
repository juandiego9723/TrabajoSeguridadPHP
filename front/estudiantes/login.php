<?php
  require('../../api/tools/functions.php');
	require('../../api/tools/db.php');
	require('../../api/tools/security.php');

  regenerarCookie();

  /**
   * inicio de sesion
   */
  if(isset($_POST['btnIniciar'])){
    if(($_POST['txtUsuario'] != null) and ($_POST['txtClave'] != null)){
      $usuario = limpiarCadena($_POST['txtUsuario']);
      $clave = md5(limpiarCadena($_POST['txtClave']));
      $usuarioLog = inicioSesion($usuario, $clave);
      if($usuarioLog != null){
        $_SESSION['usuario'] = $usuarioLog;
        header('location: home.php');
      } else {
        echo(alertJs('Las credenciales no son correctas'));
      }
    } else {
      echo(alertJs('Usuario o contraseña estan vacios'));
    }
  }

  /**
   * recuperar clave
   */
  if (isset($_POST['btnRecuperarClave'])) {
    echo("sirve");
    if (!empty($_POST['txtUsuario']) and !empty($_POST['txtCoreoElectronico'])) {
      $usuario = $_POST['txtUsuario'];
      $correoElectronico = $_POST['txtCoreoElectronico'];
      if (consultaUSuariorecuperacionClave($correoElectronico, $usuario)) {
        $aleatorio = rand(1000,9999);
        if (guardarCodigoSeguridad($usuario, $aleatorio)) {
          if (enviarCorreo($aleatorio, $correoElectronico)) {
            header('location: confirmarClave.php');
          } else {
            echo(alertJs('no es posible procesar la solicitd'));
          }
        } else {
          echo(alertJs('no es posible procesar la solicitd'));
        }
      } else {
        echo(alertJs('no es posible procesar la solicitd'));
      }
    }
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login estudiante</title>

    <!--Boostrap vs1.3 -->
    <style src="/libs/css/styles.css"></style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</head>


<body>



    <form method="POST">
        <div id="login">
            <h3 class="text-center text-info pt-5 mt-5">Login</h3>
            <div class="container">
                <div id="login-row" class="row justify-content-center align-items-center">
                    <div id="login-column" class="col-md-6">
                        <div id="login-box" class="col-md-12">
                            <form id="login-form" class="form" action="" method="post">
                                <div class="form-group">
                                    <label for="username" class="text-info">Usuario:</label><br>
                                    <input type="text" name="txtUsuario" id="txtUsuario" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="password" class="text-info">Clave:</label><br>
                                    <input type="password" name="txtClave" id="txtClave" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" value="<?php echo $anticsrf; ?>" class="form-control"
                                        name="anticsrf">
                                </div>
                                <div id="register-link" class="my-2 d-flex justify-content-around">
                                    <button type="submit" name="btnIniciar" class="btn mx-auto btn-primary">Ingresar</button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                Olvido su contraseña</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recuperar contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">

                        <div class="mb-3">
                            <label for="exampleDropdownFormPassword1" class="form-label">Usuario</label>
                            <input type="password" class="form-control" name="txtUsuario" placeholder="Password">
                        </div>
                        <div class="mb-3">
                            <label for="exampleDropdownFormPassword1" class="form-label">Correo Electronico</label>
                            <input type="password" class="form-control" name="txtCoreoElectronico"
                                placeholder="Password">
                        </div>
                        <input type="submit" name="btnRecuperarClave" class="btn btn-primary" value="Recuperar contraseña">
                        <!-- <button type="button" name="btnRecuperarClave" class="btn btn-primary">Recuperar contraseña</button> -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>