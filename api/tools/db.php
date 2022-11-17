<?php
    function pruebaDB(){
        echo'hola que hace/';
    }

    function conexionBD(){
        /**
         * conexion con la base de datos, usada para cualquier proceso de manipulacion de datos
         */
        $conexion = new PDO('mysql:host=localhost;dbname=lp3', 'root', 'root', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        if ($conexion) {
            return $conexion;
        }
        else{
            $conexion = null;
            return $conexion;
        }
    }

    function regAdmin($usuario, $clave){
        $conexion = conexionBD();
        $consultaAdmin = $conexion -> prepare('SELECT * FROM administradores WHERE usuario = :usuario');
        $consultaAdmin -> execute([':usuario' => $usuario]);
        $datoAdmin = $consultaAdmin -> fetch();
        if (empty($datoAdmin['usuario'])){
            $registro = $conexion -> prepare('INSERT INTO administradores (usuario, clave) VALUES (:usuario, :clave);');
            $registro -> bindParam(':usuario', $usuario);
            $registro -> bindParam(':clave', $clave);
            if($registro->execute()){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function inicioSesionAdmin($usuario, $clave){
        $conexion = conexionBD();
        $consultaUsuario = $conexion -> prepare('SELECT usuario FROM administradores WHERE usuario = :usuario AND clave = :clave;');
        $consultaUsuario -> bindParam(':usuario', $usuario);
        $consultaUsuario -> bindParam(':clave', $clave);
        $consultaUsuario -> execute();
        $usuarioLog = $consultaUsuario -> fetch();
        if (empty($usuarioLog)) {
            return null;
        } else {
            return $usuarioLog['usuario'];
        }

    }

    function crearAlumno(
      $tipoIdentificacion, $numeroIdentificacion, $nombreAlumno,
      $apellidoAlumno, $correElectronico, $telefono, $direccion,
      $ciudad, $usuario, $clave
    ){
        /**
         * funcion de creacion del usuario en la base de datos
         */
        $conexion = conexionBD();
        if ($conexion == null){
            return false;
        } else{
            $consultaAlumnoDB = $conexion -> prepare(
                "SELECT
                    usuario
                FROM alumnos
                WHERE
                    usuario = :usuario
                    OR numero_identificacion = :numero_identificacion
                    OR correo_electronico = :correo_electronico;
                "
            );
            // verificacion de que el usuario no exista en la base de datos
            $consultaAlumnoDB -> bindParam(":usuario", $usuario);
            $consultaAlumnoDB -> bindParam(":numero_identificacion", $numeroIdentificacion);
            $consultaAlumnoDB -> bindParam(":correo_electronico", $correElectronico);
            $consultaAlumnoDB -> execute();
            $alumnoDB = $consultaAlumnoDB -> fetch();
            // guardado de alumno
            if (empty($alumnoDB['usuario'])){
                $insertarAlumno = $conexion -> prepare("
                    INSERT INTO alumnos(
                        id_tipo_identificacion, numero_identificacion, nombre_alumno,
                        apellido_alumno, correo_electronico, telefono, direccion,
                        id_ciudad, fechaCreacion, fechaModificacion, usuario, clave
                    ) VALUES (
                        :id_tipo_identificacion, :numero_identificacion, :nombre_alumno, :apellido_alumno,
                        :correo_electronico, :telefono, :direccion, :id_ciudad,
                        now(), now(), :usuario, :clave
                    );
                ");
                $insertarAlumno -> bindParam(':id_tipo_identificacion', $numeroIdentificacion);
                $insertarAlumno -> bindParam(':numero_identificacion', $numeroIdentificacion);
                $insertarAlumno -> bindParam(':nombre_alumno', $nombreAlumno);
                $insertarAlumno -> bindParam(':apellido_alumno', $apellidoAlumno);
                $insertarAlumno -> bindParam(':correo_electronico', $correElectronico);
                $insertarAlumno -> bindParam(':telefono', $telefono);
                $insertarAlumno -> bindParam(':direccion', $direccion);
                $insertarAlumno -> bindParam(':id_ciudad', $ciudad);
                $insertarAlumno -> bindParam(':usuario', $usuario);
                $insertarAlumno -> bindParam(':clave', $clave);
                // retornando los estados de sesion
                if ($insertarAlumno -> execute()) {
                    $estadoCreacion = true;
                } else {
                    $estadoCreacion = false;
                }
            } else {
                $estadoCreacion = false;
            }
        }
        return $estadoCreacion;
    }

    function inicioSesion($usuario, $clave){
        /**
         * funcion de inicio de sesion
         */
        $conexion = conexionBD();
        $consultaUsuario = $conexion -> prepare('SELECT usuario FROM alumnos WHERE usuario = :usuario AND clave = :clave;');
        $consultaUsuario -> bindParam(':usuario', $usuario);
        $consultaUsuario -> bindParam(':clave', $clave);
        $consultaUsuario -> execute();
        $usuarioLog = $consultaUsuario -> fetch();
        if (empty($usuarioLog)) {
            return null;
        } else {
            return $usuarioLog['usuario'];
        }

    }

    function confirmarToken($usuario){
        $conexion = conexionBD();
        $consultaUsuario = $conexion -> prepare('SELECT usuario From alumnos WHERE usuario = :usuario;');
        $consultaUsuario -> execute([":usuario" => $usuario]);
        $usuarioConfirm = $consultaUsuario -> fetch();
        if (empty($usuarioConfirm)){
            return false;
        } else {
            return true;
        }
    }

    function confirmarTokenAdmin($usuario){
        $conexion = conexionBD();
        $consultaUsuario = $conexion -> prepare('SELECT usuario From administradores WHERE usuario = :usuario;');
        $consultaUsuario -> execute([":usuario" => $usuario]);
        $usuarioConfirm = $consultaUsuario -> fetch();
        if (empty($usuarioConfirm)){
            return false;
        } else {
            return true;
        }
    }

    function consultaAlumnoIdentificacion($numerIdentificacion){
        $conexion = conexionBD();
        $consultaUsuario = $conexion -> prepare(
            'SELECT
                id_alumno, id_tipo_identificacion, numero_identificacion, nombre_alumno,
                apellido_alumno, correo_electronico, telefono, direccion, id_ciudad,
                fechaCreacion, fechaModificacion, usuario
            FROM alumnos
            WHERE numero_identificacion = :numero_identificacion'
        );
        $consultaUsuario -> execute([':numero_identificacion' => $numerIdentificacion]);
        $datosUsuario = $consultaUsuario -> fetchAll();
        return $datosUsuario;
    }

    function consultaAlumnoUsuario($usuario){
      $conexion = conexionBD();
      $consultaUsuario = $conexion -> prepare(
          'SELECT
              id_alumno, id_tipo_identificacion, numero_identificacion, nombre_alumno,
              apellido_alumno, correo_electronico, telefono, direccion, id_ciudad,
              fechaCreacion, fechaModificacion, usuario
          FROM alumnos
          WHERE usuario = :usuario'
      );
      $consultaUsuario -> execute([':usuario' => $usuario]);
      $datosUsuario = $consultaUsuario -> fetch();
      return $datosUsuario;
  }

    function consultaAlumnoNombre($nombreAlumno){
        $conexion = conexionBD();
        $consultaUsuario = $conexion -> prepare(
            'SELECT
                id_alumno, id_tipo_identificacion, numero_identificacion, nombre_alumno,
                apellido_alumno, correo_electronico, telefono, direccion, id_ciudad,
                fechaCreacion, fechaModificacion, usuario
            FROM alumnos
            WHERE nombre_alumno = :nombre_alumno'
        );
        $consultaUsuario -> execute([':nombre_alumno' => $nombreAlumno]);
        $datosUsuario = $consultaUsuario -> fetchAll();
        return $datosUsuario;
    }

    function consultaAlumnoApellido($apellidoAlumno){
        $conexion = conexionBD();
        $consultaUsuario = $conexion -> prepare(
            'SELECT
                id_alumno, id_tipo_identificacion, numero_identificacion, nombre_alumno,
                apellido_alumno, correo_electronico, telefono, direccion, id_ciudad,
                fechaCreacion, fechaModificacion, usuario
            FROM alumnos
            WHERE apellido_alumno = :apellido_alumno'
        );
        $consultaUsuario -> execute([':apellido_alumno' => $apellidoAlumno]);
        $datosUsuario = $consultaUsuario -> fetchAll();
        return $datosUsuario;
    }

    function consultaAlumnosTotal(){
        $conexion = conexionBD();
        $consultaAlumnos = $conexion -> prepare(
            'SELECT
                id_alumno, id_tipo_identificacion, numero_identificacion, nombre_alumno,
                apellido_alumno, correo_electronico, telefono, direccion, id_ciudad,
                fechaCreacion, fechaModificacion, usuario
            FROM alumnos;'
        );
        $consultaAlumnos -> execute();
        $alumnos = $consultaAlumnos -> fetchAll();
        return $alumnos;
    }

    function actualizarAlumno(
        $tipoIdentificacion, $numeroIdentificacion, $nombreAlumno, $apellidoAlumno,
        $correElectronico, $telefono, $direccion, $ciudad, $clave, $usuarioAuth
    ){
        echo($nombreAlumno);
        echo($usuarioAuth);
        $conexion = conexionBD();
        $update = $conexion -> prepare(
            "UPDATE alumnos
            SET
                id_tipo_identificacion = :id_tipo_identificacion,
                numero_identificacion = :numero_identificacion,
                nombre_alumno = :nombre_alumno,
                apellido_alumno = :apellido_alumno,
                correo_electronico = :correo_electronico,
                telefono = :telefono,
                direccion = :direccion,
                id_ciudad = :id_ciudad,
                clave = :clave
            WHERE usuario = :usuario;"
        );
        $update -> bindParam(':id_tipo_identificacion', $tipoIdentificacion);
        $update -> bindParam(':numero_identificacion', $numeroIdentificacion);
        $update -> bindParam(':nombre_alumno', $nombreAlumno);
        $update -> bindParam(':apellido_alumno', $apellidoAlumno);
        $update -> bindParam(':correo_electronico', $correElectronico);
        $update -> bindParam(':telefono', $telefono);
        $update -> bindParam(':direccion', $direccion);
        $update -> bindParam(':id_ciudad', $ciudad);
        $update -> bindParam(':clave', $clave);
        $update -> bindParam(':usuario', $usuarioAuth);
        if ($update -> execute()){
            return true;
        } else {
            return false;
        }
    }

  function actualizarAlumnoFront(
    $numeroIdentificacion, $nombreAlumno, $apellidoAlumno,
    $correoElectronico, $direccion, $usuario
  ){
    $conexion = conexionBD();
    $update = $conexion -> prepare(
        "UPDATE alumnos
        SET
          numero_identificacion = :numero_identificacion,
          nombre_alumno = :nombre_alumno,
          apellido_alumno = :apellido_alumno,
          correo_electronico = :correo_electronico,
          direccion = :direccion
        WHERE usuario = :usuario;"
    );

    $update -> bindParam(':numero_identificacion', $numeroIdentificacion);
    $update -> bindParam(':nombre_alumno', $nombreAlumno);
    $update -> bindParam(':apellido_alumno', $apellidoAlumno);
    $update -> bindParam(':correo_electronico', $correoElectronico);
    $update -> bindParam(':direccion', $direccion);
    $update -> bindParam(':usuario', $usuario);
    if ($update -> execute()){
        return true;
    } else {
        return false;
    }
  }

    function consultaCursoId($idCurso){
        $conexion = conexionBD();
        $consulta = $conexion -> prepare('SELECT * FROM cursos WHERE id_curso = :id_curso;');
        $consulta -> execute([":id_curso" => $idCurso]);
        $cursos = $consulta -> fetch();
        return $cursos;
    }

    function consultaCursoNombre($nombreCurso){
        $conexion = conexionBD();
        $consulta = $conexion -> prepare("SELECT * FROM cursos WHERE nombre_curso = :nombre_curso;");
        $consulta -> execute([":nombre_curso" => $nombreCurso]);
        $cursos = $consulta -> fetchAll();
        return $cursos;
    }

    function consultaCursos(){
        $conexion = conexionBD();
        $consulta = $conexion -> prepare("SELECT * FROM cursos;");
        $consulta -> execute();
        $cursos = $consulta -> fetchAll();
        return $cursos;
    }

    function crearCurso($nombreCurso, $descripcionCurso){
        $conexion = conexionBD();
        $insert = $conexion -> prepare('INSERT INTO cursos (nombre_curso, descripcion_curso) VALUES (:nombre_curso, :descripcion_curso)');
        $insert -> bindParam(':nombre_curso', $nombreCurso);
        $insert -> bindParam(':descripcion_curso', $descripcionCurso);
        if ($insert -> execute()){
            return true;
        } else {
            return false;
        }
    }

    function actualizarCurso($idCurso, $nombreCurso, $descripcionCurso){
        $conexion = conexionBD();
        $update = $conexion -> prepare(
            "UPDATE cursos
            SET
                nombre_curso = :nombre_curso,
                descripcion_curso = :descripcion_curso
            WHERE
                id_curso = :id_curso
            "
        );
        $update -> bindParam(':nombre_curso', $nombreCurso);
        $update -> bindParam(':descripcion_curso', $descripcionCurso);
        $update -> bindParam(':id_curso', $idCurso);
        if ($update -> execute()){
            return true;
        } else {
            return false;
        }
    }

    function crearMateria($nombreMateria, $descripcionMateria){
        $conexion = conexionBD();
        $insert = $conexion -> prepare('INSERT INTO materias (nombre_materia, descripcion_materia) VALUES (:nombre_materia, :descripcion_materia);');
        $insert -> bindParam(':nombre_materia', $nombreMateria);
        $insert -> bindParam(':descripcion_materia', $descripcionMateria);
        if ($insert -> execute()) {
            return true;
        } else {
            return false;
        }
    }

    function editarMateria($idMateria, $nombreMateria, $descripcionMateria){
      $conexion = conexionBD();
      $editarMateria = $conexion -> prepare('
        UPDATE materias SET
          nombre_materia = :nombre_materia,
          descripcion_materia = :descripcion_materia
        WHERE
          id_materia = :id_materia
      ');
      $editarMateria -> bindParam(':nombre_materia', $nombreMateria);
      $editarMateria -> bindParam(':descripcion_materia', $descripcionMateria);
      $editarMateria -> bindParam(':id_materia', $idMateria);
      if ($editarMateria -> execute()) {
        return true;
      } else {
        return false;
      }
    }

    function consultarMateriasId($idMateria){
        $conexion = conexionBD();
        $consulta = $conexion -> prepare('SELECT * FROM materias WHERE id_materia = :id_materia;');
        $consulta -> execute([':id_materia' => $idMateria]);
        $materias = $consulta -> fetchAll();
        return $materias;
    }

    function consultarMateriasNombres($nombreMateria){
        $conexion = conexionBD();
        $consulta = $conexion -> prepare('SELECT * FROM materias WHERE nombre_materia = :nombre_materia');
        $consulta -> execute([':nombre_materia' => $nombreMateria]);
        $materias = $consulta -> fetchAll();
        return $materias;
    }

    function consultarMaterias(){
        $conexion = conexionBD();
        $consulta = $conexion -> prepare('SELECT * FROM materias;');
        $consulta -> execute();
        $materias = $consulta -> fetchAll();
        return $materias;
    }

    function asignarMateria($idMateria, $idCurso){
        $conexion = conexionBD();
        $consultaMateria = $conexion -> prepare('SELECT nombre_materia FROM materias WHERE id_materia = :id_materia;');
        $consultaMateria -> execute([':id_materia' => $idMateria]);
        $materia = $consultaMateria -> fetch();
        $consultaCurso = $conexion -> prepare('SELECT nombre_curso FROM cursos WHERE id_curso = :id_curso;');
        $consultaCurso -> execute(['id_curso' => $idCurso]);
        $curso = $consultaCurso -> fetch();
        if (!empty($materia) && !empty($curso)){
            $insert = $conexion -> prepare('INSERT INTO materias_cursos (id_materia, id_curso) VALUES (:id_materia, :id_curso);');
            $insert -> bindParam(':id_materia', $idMateria);
            $insert -> bindParam(':id_curso', $idCurso);
            if ($insert -> execute()){
                return true;
            } else {
                return false;
            }
        }
    }

    function asignarAlumno($idAlumno, $idCurso){
        $conexion = conexionBD();
        $consultaAlumno = $conexion -> prepare('SELECT nombre_alumno FROM alumnos WHERE id_alumno = :id_alumno;');
        $consultaAlumno -> execute([':id_alumno' => $idAlumno]);
        $alumno = $consultaAlumno -> fetch();
        $consultaCurso = $conexion -> prepare('SELECT nombre_curso FROM cursos WHERE id_curso = :id_curso;');
        $consultaCurso -> execute(['id_curso' => $idCurso]);
        $curso = $consultaCurso -> fetch();
        if (!empty($alumno) && !empty($curso)){
            $insert = $conexion -> prepare('INSERT INTO alumnos_cursos (id_alumno, id_curso) VALUES(:id_alumno, :id_curso);');
            $insert -> bindParam(':id_alumno', $idAlumno);
            $insert -> bindParam(':id_curso', $idCurso);
            if ($insert -> execute()){
                return true;
            } else {
                return false;
            }
        }
    }

    function consultaCursoAlumno($usuario){
        $conexion = conexionBD();
        $consultaCurso = $conexion -> prepare(
            'SELECT
                cursos.nombre_curso,
                cursos.descripcion_curso,
                materias.nombre_materia,
                materias.id_materia,
                alumnos.nombre_alumno,
                alumnos.apellido_alumno
            FROM
                cursos

                left join materias_cursos ON cursos.id_curso = materias_cursos.id_curso
                left JOIN materias ON materias_cursos.id_materia = materias.id_materia
                left join alumnos_cursos on alumnos_cursos.id_curso = cursos.id_curso
                left join alumnos on alumnos_cursos.id_alumno = alumnos.id_alumno

            where alumnos.usuario = :usuario
            ;'
        );
        $consultaCurso -> bindParam(':usuario', $usuario);
        $consultaCurso -> execute();
        $datosCurso =  $consultaCurso -> fetchAll();
        return $datosCurso;
    }

    function consultaMateriaAlumno($idMateria, $usuarioAuth){
        $conexion = conexionBD();
        $consultaMateria = $conexion -> prepare(
            'SELECT
                materias.nombre_materia,
                materias.descripcion_materia,
                cursos.nombre_curso,
                cursos.descripcion_curso,
                alumnos.nombre_alumno,
                alumnos.apellido_alumno
            FROM materias
                left join materias_cursos on materias.id_materia = materias_cursos.id_materia
                left join cursos on materias_cursos.id_curso = cursos.id_curso
                left join alumnos_cursos on cursos.id_curso = alumnos_cursos.id_curso
                left join alumnos on alumnos_cursos.id_alumno = alumnos.id_alumno
            WHERE
                materias.id_materia = :id_materia and alumnos.usuario = :usuario
            ;'
        );
        $consultaMateria -> bindParam(':idMateria', $idMateria);
        $consultaMateria -> bindParam(':usuario', $usuarioAuth);
        $consultaMateria -> execute();
        $datosMateria = $consultaMateria -> fetchAll();
        return $datosMateria;
    }

    function consultaMateriasAlumnosTodas($usuarioAuth){
        $conexion = conexionBD();
        $consultaMateria = $conexion -> prepare(
            'SELECT
                materias.nombre_materia,
                materias.descripcion_materia,
                cursos.nombre_curso,
                cursos.descripcion_curso,
                alumnos.nombre_alumno,
                alumnos.apellido_alumno
            FROM materias
                left join materias_cursos on materias.id_materia = materias_cursos.id_materia
                left join cursos on materias_cursos.id_curso = cursos.id_curso
                left join alumnos_cursos on cursos.id_curso = alumnos_cursos.id_curso
                left join alumnos on alumnos_cursos.id_alumno = alumnos.id_alumno
            WHERE
                alumnos.usuario = :usuario
            ;'
        );
        $consultaMateria -> bindParam(':usuario', $usuarioAuth);
        $consultaMateria -> execute();
        $datosMateria = $consultaMateria -> fetchAll();
        return $datosMateria;
    }

    function crearProfesor(
        $tipoIdentificacion, $numeroIdentificacion, $nombreProfesor, $apellidoProfesor,
        $direccion, $idCiudad, $telefono, $correoElectronico
    ){
        $conexion = conexionBD();
        $insert = $conexion -> prepare(
            'INSERT INTO profesores(
                tipo_identificacion, numero_identificacion, nombre_profesor, apellido_profesor,
                direccion, id_ciudad, telefono, correo_electronico
            ) VALUES (
                :tipo_identificacion, :numero_identificacion, :nombre_profesor, :apellido_profesor,
                :direccion, :id_ciudad, :telefono, :correo_electronico
            );'
        );
        $insert -> bindParam(':tipo_identificacion', $tipoIdentificacion);
        $insert -> bindParam(':numero_identificacion', $numeroIdentificacion);
        $insert -> bindParam(':nombre_profesor', $nombreProfesor);
        $insert -> bindParam(':apellido_profesor', $apellidoProfesor);
        $insert -> bindParam(':direccion', $direccion);
        $insert -> bindParam(':id_ciudad', $idCiudad);
        $insert -> bindParam(':telefono', $telefono);
        $insert -> bindParam(':correo_electronico', $correoElectronico);
        if($insert -> execute()){
            return true;
        } else {
            return false;
        }
    }

    function consultaProfesorId($idProfesor){
        $conexion = conexionBD();
        $consultaProfesor = $conexion -> prepare('SELECT * FROM profesores WHERE id_profesor = :id_profesor');
        $consultaProfesor -> execute(["id_profesor" => $idProfesor]);
        $profesor = $consultaProfesor -> fetchAll();
        return $profesor;
    }

    function consultaProfesorNombre($nombreProfesor){
        $conexion = conexionBD();
        $consultaProfesor = $conexion -> prepare("SELECT * FROM profesores WHERE nombre_profesor = :nombre_profesor");
        $consultaProfesor -> execute(["nombre_profesor" => $nombreProfesor]);
        $profesor = $consultaProfesor -> fetchAll();
        return $profesor;
    }

    function consultaUSuariorecuperacionClave($correoElectronico, $usuario){
        $conexion = conexionBD();
        $consultaAlumno = $conexion -> prepare('
            SELECT usuario FROM alumnos
            WHERE correo_electronico = :correo_electronico
        ;');
        $consultaAlumno -> execute(['correo_electronico' => $correoElectronico]);
        $datosAlumno = $consultaAlumno -> fetch();
        if ($datosAlumno['usuario'] == $usuario) {
            return true;
        } else {
            return false;
        }
    }

    function guardarCodigoSeguridad($usuario, $aleatorio){
        $conexion = conexionBD();
        $updateCodigo = $conexion -> prepare('
            UPDATE alumnos SET
                codigo_seguridad = :codigo_seguridad
            WHERE
                usuario = :usuario
        ;');
        $updateCodigo -> bindParam(':codigo_seguridad', $aleatorio);
        $updateCodigo -> bindParam(':usuario', $usuario);
        if ($updateCodigo -> execute()){
            return true;
        } else {
            return false;
        }
    }

    function actualizaClaveRecuperada($usuario, $codigo, $clave) {
        $conexion = conexionBD();
        $updateClave = $conexion -> prepare('
            UPDATE alumnos SET
                clave = :clave
            WHERE
                usuario = :usuario
                and codigo_seguridad = :codigo_seguridad
        ;');
        $updateClave -> bindParam(':clave', $clave);
        $updateClave -> bindParam(':usuario', $usuario);
        $updateClave -> bindParam(':codigo_seguridad', $codigo);
        if ($updateClave -> execute()) {
            return true;
        } else {
            return false;
        }
    }

    function eliminarAlumno($usuario) {
      $conexion = conexionBD();
      $eliminarUsuario = $conexion -> prepare('DELETE FROM alumnos WHERE usuario = :usuario');
      if ($eliminarUsuario -> execute([':usuario' => $usuario])){
        return true;
      } else {
        return false;
      }
    }

    function eliminarMateria($idMateria) {
      $conexion = conexionBD();
      $eliminarMateria = $conexion -> prepare('DELETE FROM materias WHERE id_materia = :id_materia');
      if ($eliminarMateria -> execute([':id_materia' => $idMateria])) {
        return true;
      } else {
        return false;
      }
    }
?>
