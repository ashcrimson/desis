<?php

    include_once 'model/conexion.php';

    //iniciamos la sesion
    session_start();
    //guardamos los valores que fueron enviados por el formulario en variables de sesion
    $_SESSION['inputNombreApellido'] = $_POST['inputNombreApellido'];
    $_SESSION['inputAlias'] = $_POST['inputAlias'];
    $_SESSION['inputRut'] = $_POST['inputRut'];
    $_SESSION['inputEmail'] = $_POST['inputEmail'];
    $_SESSION['nameRegion'] = $_POST['nameRegion'];
    $_SESSION['nameComuna'] = $_POST['nameComuna'];
    $_SESSION['nameCandidatos'] = $_POST['nameCandidatos'];
    $_SESSION['checkboxEntero'] = $_POST['checkboxEntero'];

    if(empty($_POST["inputNombreApellido"])) {
        header('Location: index.php?error=El nombre no puede ir vacio');
        exit();
    }

    if(empty($_POST["inputAlias"])) {
        header('Location: index.php?error=El alias no puede ir vacio');
        exit();
    }

    if(!preg_match('/^([a-zA-Z0-9.]){5,}$/', $_POST["inputAlias"])) {
        header('Location: index.php?error=El alias no cumple con los requisitos');
        exit();
    }

    $rut = $_POST['inputRut'];
    // Verifica que no esté vacio y que el string sea de tamaño mayor a 3 carácteres(1-9)
    if ((empty($rut)) || strlen($rut) < 3) {
        header('Location: index.php?error=RUT vacío o con menos de 3 caracteres');
        exit();
    }

    // Quitar los últimos 2 valores (el guión y el dígito verificador) y luego verificar que sólo sea
    // numérico
    $parteNumerica = str_replace(substr($rut, -2, 2), '', $rut);
    if (!preg_match("/^[0-9]*$/", $parteNumerica)) {
        header('Location: index.php?error=La parte numérica del RUT sólo debe contener números');
        exit();
    }

    $guionYVerificador = substr($rut, -2, 2);
    // Verifica que el guion y dígito verificador tengan un largo de 2.
    if (strlen($guionYVerificador) != 2) {
        header('Location: index.php?error=Error en el largo del dígito verificador');
        exit();
    }

    // obliga a que el dígito verificador tenga la forma -[0-9] o -[kK]
    if (!preg_match('/(^[-]{1}+[0-9kK]).{0}$/', $guionYVerificador)) {
        header('Location: index.php?error=El dígito verificador no cuenta con el patrón requerido');
        exit();
    }

    // Valida que sólo sean números, excepto el último dígito que pueda ser k
    if (!preg_match("/^[0-9.]+[-]?+[0-9kK]{1}/", $rut)) {
        header('Location: index.php?error=Error al digitar el RUT');
        exit();
    }

    $rutV = preg_replace('/[\.\-]/i', '', $rut);
    $dv = substr($rutV, -1);
    $numero = substr($rutV, 0, strlen($rutV) - 1);
    $i = 2;
    $suma = 0;
    foreach (array_reverse(str_split($numero)) as $v) {
        if ($i == 8) {
            $i = 2;
        }
        $suma += $v * $i;
        ++$i;
    }
    $dvr = 11 - ($suma % 11);
    if ($dvr == 11) {
        $dvr = 0;
    }
    if ($dvr == 10) {
        $dvr = 'K';
    }
    if ($dvr != strtoupper($dv)) {
        header('Location: index.php?error=El RUT ingresado no es válido');
        exit();
    }

    if (!preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $_POST['inputEmail'])) {
        header('Location: index.php?error=El email no cumple con los requisitos');
        exit();
    }

    if(empty($_POST["nameRegion"])) {
        header('Location: index.php?error=La region no puede ir vacio');
        exit();
    }

    if(empty($_POST["nameComuna"])) {
        header('Location: index.php?error=La comuna no puede ir vacio');
        exit();
    }

    if(empty($_POST["nameCandidatos"])) {
        header('Location: index.php?error=El candidato no puede ir vacio');
        exit();
    }

    if (empty($_POST['checkboxEntero'])) {
        header('Location: index.php?error=No ha marcado ningun Como se entero');
        exit();
    }
    if (count($_POST['checkboxEntero']) < 2) {
        header('Location: index.php?error=Como se entero debe elegir al menos dos');
        exit();
    }

    $queryConsultaVotacionRut = $bd->query("select * from votaciones where rut = '".$rut."'");
    $consultaVotacionRut = $queryConsultaVotacionRut->fetch(PDO::FETCH_OBJ);
    if (!empty($consultaVotacionRut)) {
        header('Location: index.php?error=Ya se realizo la votacion con este RUT');
        exit();
    }

    $nombreApellido = $_POST['inputNombreApellido'];
    $alias = $_POST["inputAlias"];
    $rut = $_POST["inputRut"];
    $email = $_POST["inputEmail"];
    $region = $_POST["nameRegion"];
    $comuna = $_POST["nameComuna"];
    $candidato = $_POST["nameCandidatos"];
    $web = in_array("checkboxWeb", $_POST['checkboxEntero']) ? 1 : 0;
    $tv = in_array("checkboxTv", $_POST['checkboxEntero']) ? 1 : 0;
    $redes = in_array("checkboxRedes", $_POST['checkboxEntero']) ? 1 : 0;
    $amigos = in_array("checkboxAmigos", $_POST['checkboxEntero']) ? 1 : 0;

    $sentencia = $bd->prepare("INSERT INTO votaciones(nombre_apellido,alias,rut,email,region_id,commun_id,candidato_id,web,tv,redes_sociales,amigo) VALUES (?,?,?,?,?,?,?,?,?,?,?);");
    $resultado = $sentencia->execute([$nombreApellido,$alias,$rut,$email,$region,$comuna,$candidato,$web,$tv,$redes,$amigos]);

    if ($resultado === TRUE) {
        header('Location: index.php?success=Su voto fue registrado');
        session_destroy();
    } else {
        header('Location: index.php?error='.$resultado);
        exit();
    }
    
?>