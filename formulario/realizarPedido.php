<?php

/* Verificamos si la variable esta definida si no nos dirige a iniciar sesion */
require '../funciones/conexion_tienda.php';
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: iniciarSesion.php");
}

# Aqui obtenemos el valor por parametro
$usuario = $_SESSION["usuario"];
$precioTotal = $_POST["precioTotal"];
$idCesta = $_POST["idCesta"];
$fechaActual = date('Y/m/d');
$numProduc = $_POST["numeroProductos"];

#Insertamos nuevo registro en la tabla pedidos
$sql = "INSERT INTO pedidos (usuario, precioTotal, fechaPedido)
    VALUES ('$usuario', $precioTotal, '$fechaActual')";
$conexion->query($sql);

#Hacemos una consulta para encontrar el pedido especifico del usuario
$sql1 = "SELECT idPedido FROM pedidos WHERE usuario = '$usuario'
    AND precioTotal = '$precioTotal' AND fechaPedido = '$fechaActual'";
$idPedido = $conexion->query($sql1)->fetch_assoc()["idPedido"];

#Obtenemos el id del producto y obtenemos los productos y las cantidades
$sql2 = "SELECT idProducto, cantidad FROM productoscestas WHERE idCesta = '$idCesta'";
$res = $conexion->query($sql2);

$idProductos = [];
$cantidades = [];

# Recorremos las filas obtenidas de la consulta y el id y la cantidad la almacenamos en la array
while ($fila = $res->fetch_assoc()) {
    array_push($idProductos, $fila["idProducto"]);
    array_push($cantidades, $fila["cantidad"]);
}

#Vamos a recorrer cada producto de la cesta, de aqui vamos a obtener el precio de producto
for ($i = 0; $i < $numeroProductos; $i++) {
    $linea = $i + 1;
    $sqlAux = "SELECT precio FROM productos WHERE idProducto = '$idProductos[$i]'";
    $precio = $conexion->query($sqlAux)->fetch_assoc()["precio"];
    #Insertamos una nueva linea de pedidos en la tabla pedidos
    $sql3 = "INSERT INTO lineasPedidos VALUES ('$linea', '$idProductos[$i]', '$idPedido', '$precio', '$cantidades[$i]')";
    $conexion->query($sql3);
}

#Eliminamos los productos de la cesta
$cont = 0;
while ($cont < $numProduc) {
    $sql4 = "DELETE FROM productoscestas WHERE idProducto = $idProductos[$cont]";
    $conexion->query($sql4);
    $cont++;
}
#Con esto actualiamos el precio total de la cesta a 0.0
$sql5 = "UPDATE cestas SET precioTotal = '0.0' WHERE idCestas = '$idCesta'";
$conexion->query($sql5);
header("Location: pagPrincipal.php");
