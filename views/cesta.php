<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require 'producto.php' ?>
    <?php require '../util/conexion_tienda.php' ?>
    <title>Cesta</title>
</head>

<body>

    <!-- Aqui comprobamos si la variable usuario existe, si existe la guardo en variables, si no existe sabremos que es invitado-->
    <?php
    session_start();
    $filas_tabla = [];

    if (isset($_SESSION["usuario"]) && $_SESSION["usuario"] != 'invitado') {
        $usuario = $_SESSION["usuario"];
    } else {
        header('location: iniciarSesion.php');
    }
    ?>

    <?php
    # Sacar id de cesta a partir de nombre de usuario
    $sql = "SELECT * FROM cestas WHERE usuario = '$usuario' ";
    $resultado = $conexion->query($sql);
    $fila_cestas = $resultado->fetch_assoc();
    $id_cesta = $fila_cestas['idCestas'];

    # Sacar productos de productoscestas a partir de id de cesta
    $sql2 = "SELECT * FROM productoscestas WHERE idCesta = $id_cesta";
    $resultado2 = $conexion->query($sql2);
    while ($fila = $resultado2->fetch_assoc()) {
        $id_producto = $fila['idProducto'];
        $sql3 = "SELECT * FROM productos WHERE idProducto = $id_producto";
        $resultado3 = $conexion->query($sql3);
        $fila2 = $resultado3->fetch_assoc();

        $nombre_producto = $fila2['nombreProducto'];
        $precio = $fila2['precio'];
        $cantidad_cesta = $fila['cantidad'];
        $imagen = $fila2['imagen'];

        array_push(
            $filas_tabla,
            [$nombre_producto, $precio, $cantidad_cesta, $imagen]
        );
    }
    ?>
    <!-- Aqui hacemos una tabla y la recorremos para mostrar los productos de el usuario que sea -->

  <table class="table table-dark table-hover">
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
            $precio_total = 0;
            $numero_productos = 0;
            foreach ($filas_tabla as $fila_tabla) {
                list($nombre_producto, $precio, $cantidad_cesta, $imagen) = $fila_tabla;
                echo "<tr>
                    <td>" . $nombre_producto . "</td>
                    <td>" . $precio . "</td>
                    <td>" . $cantidad_cesta . "</td>
                    <td>" ?>
                <img width="50" height="75" src="<?php echo $imagen ?>"></td>
            <?php
                $precio_total += $precio * $cantidad_cesta;
                $numero_productos++;
            }
            ?>
            </tr>
        </tbody>
    </table>


    <!-- Esto es para finalizar pedido -->


    <form method="post" action="realizarPedido.php">
        <input type="hidden" name="precioTotal" value="<?php echo $precio_total ?>">
        <input type="hidden" name="idCesta" value="<?php echo $id_cesta ?>">
        <input type="hidden" name="numeroProductos" value="<?php echo $numero_productos ?>">
        <input type="submit" name="ENVIAR" value="Realizar el pago" class="btn btn-dark">
    </form>




</body>

</html>