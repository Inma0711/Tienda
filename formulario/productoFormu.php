<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $temp_producto = depurar($_POST["nombreProducto"]);
    $temp_precio = depurar($_POST["precio"]);
    $temp_descripcion = depurar($_POST["descripcion"]);
    $temp_cantidad = depurar($_POST["cantidad"]);
    #Si los apellidos tienen espacios en blanco de mas por el medio
    #Los eliminamos con preg_replace
  }
?>

    <fieldset>
        <legend>PRODUCTOS</legend>
            <form action="" method="post">
                <br>
                <label for="nombreProducto">Producto</label>
                <input type="string" name="nombreProducto">
                <br><br>
                <label for="precio">Precio</label>
                <input type="number" name="precio">
                <br><br>
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion">
                <br><br>
                <label for="cantidad">Cantidad</label>
                <input type="number" name="cantidad">
                <br>
            </form>
    </fieldset>
</body>
</html>