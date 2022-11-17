<?php
  require('../../api/tools/functions.php');
	require('../../api/tools/db.php');
	require('../../api/tools/security.php');

  regenerarCookie();
  $usuarioAdmin = $_SESSION['usuario'];
  /**
   * consulta de materias y cursos
   */
  $materias = consultarMaterias();
  $cursos = consultaCursos();
  $alumnos = consultaAlumnosTotal();
  /**
   * consulta de usuario si es necesario
   */
  if (isset($_POST['btnConsultarAlumno'])) {
    if ($_POST['txtNumeroIdentificacion'] != null) {
          $numeroIdentificacion = limpiarCadena($_POST['txtNumeroIdentificacion']);
          $datosUsuario = consultaAlumnoIdentificacion($numeroIdentificacion);
          if($datosUsuario){
            $datosAlumnosMaterias = consultaMateriasAlumnosTodas($datosUsuario[0]['id_alumno']);
            // echo(json_encode($datosAlumnosMaterias));
          }else {
            echo(alertJS('Alumno no existe'));
          }
      // echo(json_encode($datosUsuario));
    } else {
      echo(alertJS('No ha ingresado ninguno de los parametros'));
    }
  }
  /**
   * Creacion de materias
   */
  if (isset($_POST['btnCrearMateria'])) {
    if (!empty($_POST['txtNombreMateria']) and !empty($_POST['txtDescripcionMateria'])) {
      $nombreMateria = limpiarCadena($_POST['txtNombreMateria']);
      $descripcionMateria = limpiarCadena($_POST['txtDescripcionMateria']);
      if (crearMateria($nombreMateria, $descripcionMateria)) {
        echo(alertJS('Materia creada correctamente'));
      }
    } else {
      echo(alertJS('No ha ingresado datos'));
    }
  }
  /**
   * creacion de curso
   */
  if (isset($_POST['btnCrearCurso'])) {
    //echo("presionado");
    if (!empty($_POST['txtNombreCurso']) and !empty($_POST['txtDescripcionCurso'])){
      $nombreCurso = limpiarCadena($_POST['txtNombreCurso']);
      $descripcionCurso = limpiarCadena($_POST['txtDescripcionCurso']);
      if (crearCurso($nombreCurso, $descripcionCurso)) {
        echo(alertJS('Curso creado correctamente'));
      } else {
        echo(alertJs('No ha ingresado datos'));
      }
    }
  }
  /**
   * asignacion de materia a curso
   */
  if (isset($_POST['btnAsignarMateria'])) {
    if (!empty($_POST['txtIdMateria']) and !empty($_POST['txtIdCurso'])){
      $idMateria = limpiarCadena($_POST['txtIdMateria']);
      $idCurso = limpiarCadena($_POST['txtIdCurso']);
      if (asignarMateria($idMateria, $idCurso)) {
        echo(alertJs('Materia asignada correctamente'));
      }
    } else {
      echo(alertJs('No ha ingresado datos'));
    }
  }
  /**
   * asignar estuduantes a curso
   */
  if(isset($_POST['btnAsignarAlumno'])){
    if(!empty($_POST['txtIdAlumno']) and !empty($_POST['txtIdCurso'])){
      $idAlumno = limpiarCadena($_POST['txtIdAlumno']);
      $idCurso = limpiarCadena($_POST['txtIdCurso']);
      if(asignarAlumno($idAlumno, $idCurso)){
        echo(alertJs('Estudiante asignada correctamente'));
      } else {
        echo(alertJs('No ha ingresado datos'));
      }
    }
  }
  /**
   * creacion de estudiante
   */
  if (isset($_POST['btnRegistrarAlumno'])) {
    if (!empty($_POST['txtNumeroIdentificacion'])
      and !empty($_POST['txtNombreAlumno'])
      and !empty($_POST['txtApellidoAlumno'])
      and !empty($_POST['txtCorreoElectronico'])
      and !empty($_POST['txtTelefono'])
      and !empty($_POST['txtDireccion'])
    ) {
      $tipoIdentificacion = '1';
      $numeroIdentificacion = limpiarCadena($_POST['txtNumeroIdentificacion']);
      $nombreAlumno = limpiarCadena($_POST['txtNombreAlumno']);
      $apellidoAlumno = limpiarCadena($_POST['txtApellidoAlumno']);
      $correoElectronico = limpiarCadena($_POST['txtCorreoElectronico']);
      $telefono = limpiarCadena($_POST['txtTelefono']);
      $direccion = limpiarCadena($_POST['txtDireccion']);
      $ciudad = '1';
      $usuario = limpiarCadena($_POST['txtUsuario']);
      $clave = md5(limpiarCadena($_POST['txtClave']));
      if (crearAlumno(
        $tipoIdentificacion, $numeroIdentificacion, $nombreAlumno,
        $apellidoAlumno, $correoElectronico, $telefono, $direccion,
        $ciudad, $usuario, $clave
      )) {
        echo(alertJs('Usuario creado correctamente'));
      }
    } else {
      echo(alertJs('No ha ingresado los datos'));
    }
  }

  if (isset($_POST['btnEliminarAlumno'])){
    echo(alertJS($_POST['txtUsuario']));
    $usuarioDel = limpiarCadena($_POST['txtUsuario']);
    if (eliminarAlumno($usuarioDel)) {
      echo(alertJs('Usuario Eliminado'));
    } else {
      echo(alertJs('Error al eliminar el usuario'));
    }
  }

  if (isset($_POST['btnConsultarMateria'])) {
    $nombreMateria = limpiarCadena($_POST['txtNombreMateria']);
    $datosMaterias = consultarMateriasNombres($nombreMateria);
  }

  if (isset($_POST['btnEditarMateria'])) {
    $idMateria = limpiarCadena($_POST['txtIdMateria']);
    $nombreMateria = limpiarCadena($_POST['txtNombreMateria']);
    $descripcionMateria = limpiarCadena($_POST['txtDescripcionMateria']);
    if (editarMateria($idMateria, $nombreMateria, $descripcionMateria)) {
      echo(alertJs('Materia editada correctamente'));
    } else {
      echo(alertJs('Error al editar materia'));
    }
  }

  if (isset($_POST['btnEliminarMateria'])) {
    echo(alertJs($_POST['txtIdMateria']));
    $idMateria = limpiarCadena($_POST['txtIdMateria']);
    if (eliminarMateria($idMateria)) {
      echo(alertJs('Materia eliminada correctamente'));
    } else {
      echo(alertJs('Error al eliminar materia'));
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrador</title>

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


    <!-- Button trigger modal  Registrar Alumno-->
    <button type="button" class="btn btn-primary m-2 mx-2" data-bs-toggle="modal" data-bs-target="#Registrar">
        Registrar Alumnos
    </button>


    <!-- Modal -->
    <div class="modal" id="Registrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Alumnos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" class="row" method="POST">
                      <label class="col-6" for="">Numero de identificacion: </label>
                      <input class="col-6" type="number" name="txtNumeroIdentificacion">
                      <label class="col-6" for="">Nombre usuario: </label>
                      <input class="col-6" type="text" name="txtNombreAlumno">
                      <label class="col-6" for="">Apellido usuario: </label>
                      <input class="col-6" type="text" name="txtApellidoAlumno">
                      <label class="col-6" for="">Correo electronico: </label>
                      <input class="col-6" type="email" name="txtCorreoElectronico">
                      <label class="col-6" for="">Telefono: </label>
                      <input class="col-6" type="tel" name="txtTelefono">
                      <label class="col-6" for="">Direccion: </label>
                      <input class="col-6" type="text" name="txtDireccion">
                      <label class="col-6" for="">Usuario: </label>
                      <input class="col-6" type="text" name="txtUsuario">
                      <label class="col-6" for="">Clave: </label>
                      <input class="col-6" type="password" name="txtClave">
                      <div   class="col-6"></div>
                      <div   class="col-6">
                      <button type="submit" class="btn btn-primary m-2" value="Registar Alumno"
                                    name="btnRegistrarAlumno">Registrar Alumno</button>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Button trigger modal Crear Materia-->
    <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#Crear">
        Crear Materia
    </button>


    <!-- Modal -->
    <div class="modal fade" id="Crear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear Materia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" class="row" method="POST">
                      <label for="" class="col-6">Nombre Materia</label>
                      <input type="text" class="col-6" name="txtNombreMateria">
                      <label for="" class="col-6">Descripcion Materia</label>
                      <input type="text" class="col-6" name="txtDescripcionMateria">
                      <div class="col-6"></div>
                      <div class="col-6"><button type="submit" class="btn btn-primary m-2" name="btnCrearMateria"
                                    value="Crear materia">Crear materia</button>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Button trigger modal Crear Curso-->
    <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#CrearCurso">
        Crear Curso
    </button>


    <!-- Modal -->
    <div class="modal fade" id="CrearCurso" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form action="" class="row" method="POST">
                      <label for="" class="col-6">Nombre curso</label>
                      <input type="text" class="col-6" name="txtNombreCurso">
                      <label for="" class="col-6">Descripcion Materia</label>
                      <input type="text" class="col-6" name="txtDescripcionCurso">
                      <div class="col-6"></div>
                      <div class="col-6"><button type="submit" class="btn btn-primary m-2" name="btnCrearCurso"
                                    value="Crear materia">Crear Curso</button>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal Asignar Materia-->
    <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#Asignar">
        Asignar Materia
    </button>


    <!-- Modal -->
    <div class="modal fade" id="Asignar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Asignar Materia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <table>
                            <tr>
                                <td>Curso</td>
                                <td>
                                    <Select name="txtIdCurso">
                                        <option value="" disabled selected>seleccione</option>
                                        <?php
                    foreach ($cursos as $curso) {
                      echo('<option value='.$curso['id_curso'].'>'.$curso['nombre_curso'].'</option>');
                    }
                  ?>
                                    </Select>
                                </td>
                            </tr>
                            <tr>
                                <td>Materia</td>
                                <td>
                                    <Select name="txtIdMateria">
                                        <option value="" disabled selected>seleccione</option>
                                        <?php
                    foreach ($materias as $materia) {
                      echo('<option value='.$materia['id_materia'].'>'.$materia['nombre_materia'].'</option>');
                    }
                  ?>
                                    </Select>
                                </td>
                            </tr>
                            <tr>
                                <button type="submit" class="btn btn-primary m-2" value="Asignar Materia"
                                    name="btnAsignarMateria">Asignar Materia</button>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal Asignar Alumno-->
    <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#AsignarAlumno">
        Asignar Alumno
    </button>


    <!-- Modal -->
    <div class="modal fade" id="AsignarAlumno" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Asignar Alumno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <table>
                            <tr>
                                <td>Alumno</td>
                                <td>
                                    <Select name="txtIdAlumno">
                                        <option value="" disabled selected>seleccione</option>
                                        <?php
                    foreach ($alumnos as $alumno) {
                      echo('<option value='.$alumno['id_alumno'].'>'.$alumno['numero_identificacion'].'</option>');
                    }
                  ?>
                                    </Select>
                                </td>
                            </tr>
                            <tr>
                                <td>Curso</td>
                                <td>
                                    <Select name="txtIdCurso">
                                        <option value="" disabled selected>seleccione</option>
                                        <?php
                    foreach ($cursos as $curso) {
                      echo('<option value='.$curso['id_curso'].'>'.$curso['nombre_curso'].'</option>');
                    }
                  ?>
                                    </Select>
                                </td>
                            </tr>
                            <tr>
                                <button type="submit" class="btn btn-primary m-2" value="Asignar alumno"
                                    name="btnAsignarAlumno">Asignar Alumno</button>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal Consultar Alumno-->
    <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#Consultar">
        Consultar Alumno
    </button>


    <!-- Modal -->
    <div class="modal fade" id="Consultar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Consultar Alumno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                      <label for="">Numero de identificación</label>
                      <input type="text" name="txtNumeroIdentificacion">
                      <button type="submit" class="btn btn-primary m-2" value="Consultar alumno"
                      name="btnConsultarAlumno">Consultar Alumno</button>
                    </form>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Button trigger modal Consultar Materia-->
    <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#Consultarmateria">
        Consultar Materia
    </button>


    <!-- Modal -->
    <div class="modal fade" id="Consultarmateria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Consultar Materia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                      <label for="">Nombre materia</label>
                      <input type="text" name="txtNombreMateria">
                      <button type="submit" class="btn btn-primary m-2" value="Consultar materia"
                          name="btnConsultarMateria">Consultar Materia</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Consultar Alumno Tabla -->
    <div>
                        <?php
          if (!empty($datosUsuario)) {
            echo'
              <form action="" method="POST">
                <table class="table">
                <thead>
                  <tr>
                    <td>Numero identificacion</td>
                    <td>Nombre alumno</td>
                    <td>Apellido alumno</td>
                    <td>Correo electronico</td>
                    <td>Usuario</td>
                  </tr>
                  </thead>
                  <tbody>
              ';
            foreach ($datosUsuario as $datoUsuario){
                ?>
                        <tr>
                            <td><label name="txtNumeroIdentificacion"><?php echo($datoUsuario['numero_identificacion'])?></label></td>
                            <td><input type="label" value="<?php echo($datoUsuario['nombre_alumno'])?>"
                                    name="txtNombreAlumno"></td>
                            <td><input type="label" value="<?php echo($datoUsuario['apellido_alumno'])?>"
                                    name="txtApellidoAlumno"></td>
                            <td><input type="label" value="<?php echo($datoUsuario['correo_electronico'])?>"
                                    name="txtCorreoElectronico"></td>
                            <td><label for=""><?php echo($datoUsuario['usuario'])?></label>
                              <input type="hidden" value="<?php echo($datoUsuario['usuario'])?>" name="txtUsuario" >
                            </td>
                            <td><input type="submit" value="Eliminar" name="btnEliminarAlumno"></td>
                        </tr>
                        <?php
            }
            echo'</tbody>
                </table>
              </form>
            ';
          }
        ?>
    </div>


    <!-- Consultar Materia Tabla -->

    <div>
                    <?php
          if (!empty($datosMaterias)) {
            echo('
              <form action="" method="POST">
                <table class="table">
                <thead>
                  <tr>
                    <td>Código materia</td>
                    <td>Nombre materia</td>
                    <td>Descripcion materia</td>
                  </tr>
                  </thead>
                  <tbody>
            ');
            foreach ($datosMaterias as $datoMateria){
              ?>
                    <tr>
                        <td><label for=""><?php echo($datoMateria['id_materia'])?></label>
                          <input type="hidden" value="<?php echo($datoMateria['id_materia'])?>" name="txtIdMateria">
                        </td>
                        <td><input type="text" value="<?php echo($datoMateria['nombre_materia'])?>"
                                name="txtNombreMateria"></td>
                        <td><input type="text" value="<?php echo($datoMateria['descripcion_materia'])?>"
                                name="txtDescripcionMateria"></td>
                        <td><input type="submit" value="Editar" name="btnEditarMateria"></td>
                        <td><input type="submit" value="Eliminar" name="btnEliminarMateria"></td>
                    </tr>
                    <?php
            }
            echo('</tbody>
                </table>
              </form>
            ');
  
          }
        ?>
                </div>
    <script src="./../js/main.js"></script>
</body>
</html>
