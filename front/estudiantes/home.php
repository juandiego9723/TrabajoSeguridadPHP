<?php
  require('../../api/tools/functions.php');
	require('../../api/tools/db.php');
	require('../../api/tools/security.php');

  regenerarCookie();
  /**
   * consulta de usuario
   */
  $usuario = $_SESSION['usuario'];
  $datosUsuario = consultaAlumnoUsuario($usuario);
  /**
   * consulta de materias
   */
  $datosMaterias = consultaMateriasAlumnosTodas($usuario);

  /**
   * actualizacion de usuario
   */
  if(isset($_POST['btnActualizar'])){
    $numeroIdentificacion = limpiarCadena($_POST['txtNumeroIdentificacion']);
    $nombreAlumno = limpiarCadena($_POST['txtNombreAlumno']);
    $apellidoAlumno = limpiarCadena($_POST['txtApellidoAlumno']);
    $correoElectronico = limpiarCadena($_POST['txtCorreoElectronico']);
    $direccion = limpiarCadena($_POST['txtDireccion']);

    if(actualizarAlumnoFront($numeroIdentificacion, $nombreAlumno, $apellidoAlumno, $correoElectronico, $direccion, $usuario)){
      echo(alertJs('Usuario actualizado'));
    } else {
      echo(alertJs('error al actualizar el usuario'));
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes</title>
 <!--Boostrap vs1.3 -->
 <style src="/libs/css/styles.css"></style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> 

</head>
<body style="display: flex;
    justify-content: space-around;
    margin: 10vw;    
    align-content: center;
    flex-wrap: wrap;">
  <div>
    <h1> Datos de Alumno</h1>
  </div>
  <form method="post">
  <table class="table table-success table-striped">
      <thead>
        <td>Numero de identificacion</td>
        <td>Nombre</td>
        <td>Apellido</td>
        <td>Correo Electronico</td>
        <td>Direccion</td>
        <td>Usuario</td>
        <td>Acciones</td>
      </thead>
      <tbody>
      <tr>
        <td><input type="text" name="txtNumeroIdentificacion" value="<?php echo($datosUsuario['numero_identificacion']);?>"></td>
        <td><input type="text" name="txtNombreAlumno" value="<?php echo($datosUsuario['nombre_alumno']);?>"></td>
        <td><input type="text" name="txtApellidoAlumno" value="<?php echo($datosUsuario['apellido_alumno']);?>"></td>
        <td><input type="text" name="txtCorreoElectronico" value="<?php echo($datosUsuario['correo_electronico']);?>"></td>
        <td><input type="text" name="txtDireccion" value="<?php echo($datosUsuario['direccion']);?>"></td>
        <td><input type="text" name="txtUsuario" value="<?php echo($datosUsuario['usuario']);?>" disabled></td>
        <td><input type="submit" value="Editar" name="btnActualizar"></td>
      </tr>
      </tbody>
    </table>
  </form>
  <div>
    <h1 style="text-align: center;">Datos de materia</h1>
    <table class="table table-success table-striped">
      <thead>

        <tr>
          <td>Materia</td>
          <td>Descripcion de materia</td>
          <td>Curso</td>
          <td>Descripcion de curso</td>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach($datosMaterias as $datoMateria){
            echo('
              <tr>
                <td>'.$datoMateria['nombre_materia'].'</td>
                <td>'.$datoMateria['descripcion_materia'].'</td>
                <td>'.$datoMateria['nombre_curso'].'</td>
                <td>'.$datoMateria['descripcion_curso'].'</td>
              </tr>
              ');
            }
            ?>
      </tbody>
    </table>
  </div>
</body>
</html>

