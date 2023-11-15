<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require '../funciones/conexion_tienda.php' ?>
    <?php require '../funciones/depurar.php' ?>
    <title>Usuario</title>
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $temp_usuario = depurar($_POST["usuario"]);
        $temp_contrasena = depurar($_POST["contrasena"]);
        $temp_edad_nacimiento = depurar($_POST["fechaNacimiento"]);

        # VALIDACIONES

        if (!strlen($temp_usuario) > 0) {
            $err_usuario = "El nombre de usuario es obligatorio";
        } else {
            $patron = "/^[a-zA-Z0-9]{4,12}$/";
            if (!preg_match($patron, $temp_usuario)) {
                $err_usuario = "El nombre debe tener entre 4 y 12 caracteres
                    y contener solamente letras o números";
            } else {
                $usuario = $temp_usuario;
            }
        }

        //  LUEGO VALIDAMOS LA CONTRASEÑA
        if (empty($_POST["contrasena"])) {
            $err_usuario = "Introduzca la contraseña";
        } else {
            //  Si el campo del nombre no está vacío, validamos que el nombre siga el formato correcto
            $p = '^.{1,255}$';
            $pattern = "$p^";

            if (!preg_match($pattern, $_POST["contrasena"])) {
                //  Si llegamos aquí, el nombre introducido no sigue el formato correcto
                $err_contrasena = "La contraseña no debe ser superior a 255";
            } else {
                //  Si llegamos aquí, el nombre introducido es correcto y tenemos que "depurarlo"
                $contrasena = $temp_contrasena;
            }
        }


        // VALIDACION EDAD
        if (strlen($temp_edad_nacimiento) == 0) {
            $err_edad= "La fecha de nacimiento es obligatoria";
        } else {
            $fecha_actual = date("Y-m-d");
            list($anio_actual) = explode("-", $fecha_actual);
            list($anio) = explode("-", $temp_edad_nacimiento);
            if (($anio_actual - $anio > 12) && ($anio_actual - $anio < 120)) {
                $edad_nacimiento = $temp_edad_nacimiento;
            } else {
                $err_edad = "La fecha de nacimiento no es válida (menor de 120 años y mayor a 12 años)";
            }
        }
    }


        // CUANDO CREAMOS USUARIO SE NOS CREA UNA CESTA

        if (isset($usuario) && isset($contrasena) && isset($edad_nacimiento)) {
            $contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);
            $sql1 = "INSERT INTO usuarios (usuario, contrasena, fechaNacimiento)
                    VALUES ('$usuario',
                            '$contrasena_cifrada',
                            '$edad_nacimiento')";
            $sql2 = "INSERT INTO Cestas (usuario, precioTotal) VALUES ('$usuario', 0)";
            $conexion->query($sql1);
            $conexion->query($sql2);
        }
    
    ?>


    <div class="container">
        <h1>Registrarse</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label">Usuario:</label>
                <input class="form-control" type="text" name="usuario">
                <?php if (isset($err_usuario)) echo $err_usuario ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña:</label>
                <input class="form-control" type="password" name="contrasena">
                <?php if (isset($err_contrasena)) echo $err_contrasena ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Edad:</label>
                <input class="form-control" type="date" name="fechaNacimiento">
                <?php if (isset($err_edad)) echo $err_edad ?>
            </div>
            <input class="btn btn-primary" type="submit" value="Registrarse">
        </form>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>