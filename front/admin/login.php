<?php
  require('../../api/tools/functions.php');
	require('../../api/tools/db.php');
	require('../../api/tools/security.php');

  regenerarCookie();

  if(isset($_POST['btnEnviar'])){
    if(($_POST['txtUsuario'] != null) and ($_POST['txtClave'] != null)){
      $usuario = limpiarCadena($_POST['txtUsuario']);
      $clave = md5(limpiarCadena($_POST['txtClave']));
      $usuarioLog = inicioSesionAdmin($usuario, $clave);
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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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
                                <div id="register-link" class="text-right my-2">
                                    <button type="submit" name="btnEnviar" class="btn-primary">Ingresar</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>

</html>