<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require '../funciones/depurar.php'; ?>
    <?php require '../funciones/conexion_tienda.php'; ?>
    <title>Document</title>
</head>

<body>

    <?php
    session_start();

    #Aqui vamos a verificar si el rol no es admin, si no lo es nos va a reedirigir a la pagina principal
    if ($_SESSION['rol'] != 'admin') {
        header('location: pagPrincipal.php');
    }
    #Comprobamos si la solicitud es de tipo post y obtenemos y depuramos los datos enviados del formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $temp_nombre = depurar($_POST["nombreProducto"]);
        $temp_precio = depurar($_POST["precioProducto"]);
        $temp_descripcion = depurar($_POST["descripcion"]);
        $temp_cantidad = depurar($_POST["cantidad"]);


        $nombre_imagen = $_FILES["imagen"]["name"];
        $tipo_imagen = $_FILES["imagen"]["type"];
        $tamano_imagen = $_FILES["imagen"]["size"];
        $ruta_temporal = $_FILES["imagen"]["tmp_name"];


        $ruta_final = "imagenes/" . $nombre_imagen;

        #Esto es para mover un archivo subido al servidor desde una ubicacion temporal a una final
        move_uploaded_file($ruta_temporal, '../' . $ruta_final);

        //Validación del nombre
        if (strlen($temp_nombre) == 0) {
            $err_nombre = "Campo obligatorio";
        } else {
            if (strlen($temp_nombre) > 40) {
                $err_nombre = "No puede tener más de 40 caracteres";
            } else {
                $patron = "/^[A-Za-z0-9 ]+$/";
                if (!preg_match($patron, $temp_nombre)) {
                    $err_nombre = "El nombre debe tener caracteres, números y espacios";
                } else {
                    $nombre = $temp_nombre;
                }
            }
        }


        //Validación de la descripción
        if (strlen($temp_descripcion) == 0) {
            $err_descripcion = "Campo obligatorio";
        } else {
            if (strlen($temp_descripcion) > 255) {
                $err_descripcion = "No puede ser superior a 255 caracteres";
            } else {
                $descripcion = $temp_descripcion;
            }
        }



        //Validación del precio
        if (strlen($temp_precio) == 0) {
            $err_precio = "Campo obligatorio";
        } else {
            if (!is_numeric($temp_precio)) {
                $err_precio = "Tiene que ser un número.";
            } else {
                $temp_precio = (float)$temp_precio;
                if ($temp_precio < 0) {
                    $err_precio = "No puede ser negativo";
                } else {
                    if ($temp_precio > 99999.99) {
                        $err_precio = "El precio no puede ser superior a 99999.99";
                    } else {
                        $precio_producto = $temp_precio;
                    }
                }
            }
        }



        //Validación de la cantidad
        if (strlen($temp_cantidad) == 0) {
            $err_cantidad = "Campo obligatorio";
        } else {
            if (filter_var($temp_cantidad, FILTER_VALIDATE_INT) === FALSE) {
                $err_cantidad = "Tiene que ser un número entero";
            } else {
                $temp_cantidad = (int)$temp_cantidad;
                if ($temp_cantidad < 0) {
                    $err_cantidad = "No puede ser negativa";
                } else {
                    if ($temp_cantidad > 99999) {
                        $err_cantidad = "No puede ser superior a 99999";
                    } else {
                        $cantidad = $temp_cantidad;
                    }
                }
            }
        }

        #Comprobamos que todas las variables esten definidas e insertamos un nuevo producto a la base de datos
        if (isset($nombre) && isset($precio_producto) && isset($descripcion) && isset($cantidad)) {
            $sql = "INSERT INTO productos
            VALUES (DEFAULT,
                    '$nombre',
                    $precio_producto,
                    '$descripcion',
                    $cantidad,
                    '$ruta_final')";
            $conexion->query($sql);
        }
    }


    ?>

    <!-- Formulario de producto, que contiene nombre, precio, descripcion, cantidad y una imagen-->

    <form class="form" action="" method="post" enctype="multipart/form-data">
        <fieldset>
            <label>Nombre Producto: </label>
            <input type="text" name="nombreProducto">
            <?php if (isset($err_nombre)) echo $err_nombre ?>
            <br><br>
            <label>Precio: </label>
            <input type="number" name="precioProducto">
            <?php if (isset($err_precio)) echo $err_precio ?>
            <br><br>
            <label>Descripcion: </label>
            <input type="text" name="descripcion">
            <?php if (isset($err_descripcion)) echo $err_descripcion ?>
            <br><br>
            <label>Cantidad: </label>
            <input type="number" name="cantidad">
            <?php if (isset($err_precio)) echo $err_precio ?>
            <div class="mb-3">
                <label class="form-label">Imagen</label>
                <input class="form-control" type="file" name="imagen">
            </div>
            <input class="btn btn-dark" type="submit" value="Añadir">
        </fieldset>
    </form>

</body>

</html>