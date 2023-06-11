<?php include 'template/header.php' ?>

<?php

    session_start();

    include_once "model/conexion.php";

    $queryCandidatos =  $bd->query("select * from candidatos");
    $candidatos = $queryCandidatos->fetchAll(PDO::FETCH_OBJ);

    $queryRegion =  $bd->query("select * from regions");
    $regiones = $queryRegion->fetchAll(PDO::FETCH_OBJ);

    $queryComuna =  $bd->query("select * from communes");
    $comunas = $queryComuna->fetchAll(PDO::FETCH_OBJ);

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-9">

            <!-- inicio alerta -->
            <?php
            if(isset($_GET['error'])){
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong>
                    <?php
                        echo $_GET['error'];
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            }
            ?>

            <?php
            if(isset($_GET['success'])){
                ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Registrado!</strong>
                    <?php
                    echo $_GET['success'];
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            }
            ?>
            <!-- fin alerta -->

            <div class="card">
                <div class="card-header">
                    Formulario de votación:
                </div>
                <form class="p-4" id="formRegistro" method="POST" action="registrar.php">
                    <div class="mb-3 row">
                        <label for="inputNombreApellido" class="col-sm-3 col-form-label">Nombre y Apellido:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="inputNombreApellido" value="<?php echo isset($_SESSION['inputNombreApellido']) ? $_SESSION['inputNombreApellido'] : '';?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputAlias" class="col-sm-3 col-form-label">Alias:</label>
                        <div class="col-sm-9">
                                <input type="text" class="form-control" name="inputAlias" value="<?php echo isset($_SESSION['inputAlias']) ? $_SESSION['inputAlias'] : '';?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputRut" class="col-sm-3 col-form-label">RUT:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="inputRut" value="<?php echo isset($_SESSION['inputRut']) ? $_SESSION['inputRut'] : '';?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Email:</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" name="inputEmail" value="<?php echo isset($_SESSION['inputEmail']) ? $_SESSION['inputEmail'] : '';?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputRegion" class="col-sm-3 col-form-label">Region:</label>
                        <div class="col-sm-9">
                            <!--<input type="text" class="form-control" id="inputRegion">-->
                            <select class="form-control" name="nameRegion" id="nameRegion">
                                <option value="">Seleccione uno...</option>
                                <?php
                                foreach ($regiones as $region):
                                    $selectedValida = '';
                                    if ($_SESSION['nameRegion']) {
                                        if ($region->id == $_SESSION['nameRegion']) {
                                            $selectedValida = 'selected="selected"';
                                        } else {
                                            $selectedValida = '';
                                        }
                                    } else {
                                        $selectedValida = '';
                                    }
                                    echo '<option value="'.$region->id.'" '.$selectedValida.' >'.$region->name.'</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputComuna" class="col-sm-3 col-form-label">Comuna:</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="nameComuna" id="nameComuna">
                                <option value="">Seleccione uno...</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputCandidato" class="col-sm-3 col-form-label">Candidato:</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="nameCandidatos">
                                <option value="">Seleccione uno...</option>
                                <?php
                                    foreach ($candidatos as $candidato):
                                        $selectedValida = '';
                                        if ($_SESSION['nameCandidatos']) {
                                            if ($candidato->id == $_SESSION['nameCandidatos']) {
                                                $selectedValida = 'selected="selected"';
                                            } else {
                                                $selectedValida = '';
                                            }
                                        } else {
                                            $selectedValida = '';
                                        }
                                        echo '<option value="'.$candidato->id.'" '.$selectedValida.'>'.$candidato->nombre.'</option>';
                                    endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-3 col-form-label">Como se entero de nosotros:</label>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="checkboxWeb" name="checkboxEntero[]" value="checkboxWeb" <?php if (isset($_SESSION['checkboxEntero'])) { echo in_array("checkboxWeb", $_SESSION['checkboxEntero']) ? "checked" : ""; } ?> >
                                <label class="form-check-label" for="checkboxWeb">Web</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="checkboxTv" name="checkboxEntero[]" value="checkboxTv" <?php if (isset($_SESSION['checkboxEntero'])) { echo in_array("checkboxTv", $_SESSION['checkboxEntero']) ? "checked" : ""; } ?>>
                                <label class="form-check-label" for="checkboxTv">TV</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="checkboxRedes" name="checkboxEntero[]" value="checkboxRedes" <?php if (isset($_SESSION['checkboxEntero'])) { echo in_array("checkboxRedes", $_SESSION['checkboxEntero']) ? "checked" : ""; } ?>>
                                <label class="form-check-label" for="checkboxRedes">Redes Sociales</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="checkboxAmigos" name="checkboxEntero[]" value="checkboxAmigos" <?php if (isset($_SESSION['checkboxEntero'])) { echo in_array("checkboxAmigos", $_SESSION['checkboxEntero']) ? "checked" : ""; } ?>>
                                <label class="form-check-label" for="checkboxAmigos">Amigo</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid">
                        <input type="hidden" name="oculto" value="1">
                        <input type="submit" class="btn btn-primary" value="Registrar">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script>

        let nameRegion = <?php echo $_SESSION['nameRegion']; ?>;
        if (nameRegion) {
            let comunas = <?php echo json_encode($comunas); ?>;

            let comunasFilterById = comunas.filter(comuna => comuna.region_id == $( "#nameRegion" ).val() );

            var select = document.getElementById('nameComuna');

            $('#nameComuna')
                .find('option')
                .remove();

            for (var i = 0; i<comunasFilterById.length; i++){
                let comunaObj = comunasFilterById[i];
                var opt = document.createElement('option');
                opt.value = comunaObj.id;
                opt.innerHTML = comunaObj.name;
                select.appendChild(opt);
            }
        }

        $( "#nameRegion" ).on( "change", function() {

            let comunas = <?php echo json_encode($comunas); ?>;

            let comunasFilterById = comunas.filter(comuna => comuna.region_id == $( "#nameRegion" ).val() );

            var select = document.getElementById('nameComuna');

            $('#nameComuna')
                .find('option')
                .remove();

            for (var i = 0; i<comunasFilterById.length; i++){
                let comunaObj = comunasFilterById[i];
                var opt = document.createElement('option');
                opt.value = comunaObj.id;
                opt.innerHTML = comunaObj.name;
                select.appendChild(opt);
            }

        });


    </script>

<?php include 'template/footer.php' ?>