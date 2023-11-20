<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require 'producto.php' ?>
    <?php require '../funciones/conexion_tienda.php' ?>
    <title>Cesta</title>
</head>

<body>

    <?php
    session_start();
    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    } else {
        $_SESSION["usuario"] = "invitado";
        $usuario = $_SESSION["usuario"];
    }
    $rol ??= 'cliente';
    ?>

    <?php
    # Sacar id de cesta a partir de nombre de usuario
    $sql = "SELECT * FROM cestas WHERE usuario = '$usuario' ";
    $resultado = $conexion->query($sql);
    $fila = $resultado->fetch_assoc();
    $id_cesta = $fila['idCestas'];

    # Sacar productos de productoscestas a partir de id de cesta
    $sql2 = "SELECT * FROM productoscestas WHERE idCesta = '$id_cesta';";
    $resultado2 = $conexion->query($sql2);
    $filas_tabla = [];
    while ($fila = $resultado2->fetch_assoc()) {
        $id_producto = $fila['idProducto'];
        $sql3 = "SELECT * FROM productos WHERE idProducto = $id_producto";
        $resultado3 = $conexion->query($sql3);
        $fila2 = $resultado3->fetch_assoc();

        $nombreProducto = $fila2['nombreProducto'];
        $precio = $fila2['precio'];
        $cantidad_en_cesta = $fila['cantidad'];
        $imagen = $fila2['imagen'];
       
        array_push($filas_tabla,
            [$nombreProducto, $precio, $cantidad_en_cesta, $imagen]);
    }
    ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre de producto</th>
                <th>Precio unidad producto</th>
                <th>Cantidad en la cesta</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($filas_tabla as $fila_tabla) {
                list($nombreProducto, $precio, $cantidad_en_cesta, $imagen) = $fila_tabla;
                echo "<tr>
                    <td>" . $nombreProducto . "</td>
                    <td>" . $precio . "</td>
                    <td>" . $cantidad_en_cesta . "</td>
                    <td>" ?>
                <img width="50" height="75" src="<?php echo '../' . $imagen ?>"></td>
            <?php
            }
            ?>
            </tr>
        </tbody>
    </table>


</body>

</html>