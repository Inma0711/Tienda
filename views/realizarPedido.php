<?php

$id_productos = [];
$cantidad = [];

    /* Verificamos si la variable esta definida si no nos dirige a iniciar sesion */ 
    require '../util/conexion_tienda.php';
    session_start();
    if(!isset($_SESSION["usuario"])) {
        header("Location: iniciarSesion.php");
    }

    # Aqui obtenemos el valor por parametro
    $usuario = $_SESSION["usuario"];
    $precio_total = $_POST["precioTotal"];
    $id_cesta = $_POST["idCesta"];
    $fecha_actual = date('Y/m/d');
    $num_produc = $_POST["numeroProductos"];

    #Insertamos nuevo registro en la tabla pedidos
    $sql = "INSERT INTO pedidos (usuario, precioTotal, fechaPedido)
    VALUES ('$usuario', $precio_total, '$fecha_actual')";
    $conexion -> query($sql);

   
    #Obtenemos el id del producto y obtenemos los productos y las cantidades
    $sql1 = "SELECT idProducto, cantidad FROM productoscestas WHERE idCesta = '$id_cesta'";
    $res = $conexion -> query($sql1);

     #Hacemos una consulta para encontrar el pedido especifico del usuario
     $sql2 = "SELECT idPedido FROM pedidos WHERE usuario = '$usuario'
     AND precioTotal = '$precio_total' AND fechaPedido = '$fecha_actual'";
     $id_pedido = $conexion -> query($sql2) -> fetch_assoc()["idPedido"];
 
    # Recorremos las filas obtenidas de la consulta y el id y la cantidad la almacenamos en la array
    while($fila = $res -> fetch_assoc()) {
        array_push($id_productos, $fila["idProducto"]);
        array_push($cantidad, $fila["cantidad"]);
    }

    #Vamos a recorrer cada producto de la cesta, de aqui vamos a obtener el precio de producto
    for($i = 0; $i < $num_produc; $i++) {
        $linea = $i + 1;
        $sqlAux = "SELECT precio FROM productos WHERE idProducto = '$id_productos[$i]'";
        $precio = $conexion -> query($sqlAux) -> fetch_assoc()["precio"];
        #Insertamos una nueva linea de pedidos en la tabla pedidos
        $sql3 = "INSERT INTO lineasPedidos VALUES ('$linea', '$id_productos[$i]', '$id_pedido', '$precio', '$cantidad[$i]')";
        $conexion -> query($sql3);
    }

    #Eliminamos los productos de la cesta
    $cont = 0;
    while($cont < $num_produc) {
        $sql4 = "DELETE FROM productoscestas WHERE idProducto = $id_productos[$cont]";
        $conexion -> query($sql4);
        $cont++;
    }
#Con esto actualiamos el precio total de la cesta 
    $sql5 = "UPDATE cestas SET precioTotal = '0.0' WHERE idCestas = '$id_cesta'";
    $conexion -> query($sql5);
    header("Location: pagPrincipal.php");
?>