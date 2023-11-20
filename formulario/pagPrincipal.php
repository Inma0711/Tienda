<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <?php require '../funciones/conexion_tienda.php' ?>
  <?php require 'producto.php' ?>
  <link rel="stylesheet" href="../CSS/style.css">
  <title>Document</title>
</head>

<body>

<!-- Aqui comprobamos si la variable usuario existe, si existe la guardo en variables, si no existe sabremos que es invitado-->
  <?php
  session_start();
  if (isset($_SESSION["usuario"])) {
    $usuario = $_SESSION["usuario"];
    $rol = $_SESSION["rol"];
  } else {
    $_SESSION["usuario"] = "invitado";
    $usuario = $_SESSION["usuario"];
  }

  /* Si la variable rol no esta definida se le asignara el valor de cliente*/ 
  $rol ??= 'cliente';
  ?>



<!-- Verificamos si la solicitud es de tipo POST-->

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST["id_producto"];
    $usuario = $_SESSION['usuario'];                      /* Recuperamos los datos */
    $sql1 = "SELECT idCestas FROM cestas WHERE usuario = '$usuario'";  /* Hacemos unas consultas para obtener la id de cesta */
    $res = $conexion->query($sql1);

    if ($res->num_rows > 0) {   /* Si se devuelve una fila quiere decir que se encontro una cesta asociada al usuario */ 
      $filaCestas = $res->fetch_assoc();
      $id_cesta = $filaCestas["idCestas"];
    }

    $sql2 = "INSERT INTO productoscestas (idProducto, idCesta, cantidad)   /* Esto es para insertar un nuevo registro en la tabla productos cestas*/ 
        VALUES ($id_producto, $id_cesta, 1)
        ON DUPLICATE KEY UPDATE cantidad = cantidad + 1;";
    $conexion->query($sql2);

  }
  ?>

   <!--  para insertar en productos_cestas: id_producto, id_cesta, cantidad
    
            id_producto: lo tenemoos
            cantidad: la tenemos (1)
            ¿id_cesta?
            habrá que coger con la sesión el usuario actual
            y hacer una consulta a la tabla cestas para sacar el id de la cesta

            luego podremos insertar en productos_cestas
-->
  

  <!-- NAVBAR  no le he puesto funcionalidad pero lo dejo para que quede un poquito mas estetico -->
  <nav class="navbar navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Pagina Principal</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Dark offcanvas</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Dropdown
              </a>
              <ul class="dropdown-menu dropdown-menu-dark">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
              </ul>
            </li>
          </ul>
          <form class="d-flex mt-3" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-success" type="submit">Search</button>
          </form>
        </div>
      </div>
    </div>
  </nav>


  <!-- Botones para cerrar sesion, registrarse, iniciar sesion, cesta y registrar nuevos productos-->
  <div class="container">
    <h1>Pagina principal</h1>
    <h2>Bienvenid@ <?php echo $usuario ?></h2>
    <a class="btn btn-dark" href="../funciones/cerrar_sesion.php">Cerrar sesion</a>

    <!-- Añadir enlace a registro de productos solo si el usuario es admin -->
    <?php if ($rol == 'admin') : ?>
      <a class="btn btn-dark" href="productoFormu.php">Registro productos nuevos</a>
    <?php endif; ?>

    <!-- Añadir enlace a iniciar sesion solo si el usuario es invitado -->
    <?php if ($usuario == "invitado") : ?>
      <a class="btn btn-dark" href="iniciarSesion.php">Iniciar Sesion</a>
    <?php endif; ?>

    <?php if ($usuario == "invitado") : ?>
      <a class="btn btn-dark" href="registroUsuario.php">Registrarse</a>
    <?php endif; ?>

    <?php if ($usuario != 'invitado') : ?>
      <a class="btn btn-dark" href="cesta.php">Cesta</a>
    <?php endif; ?>


  </div>

<!-- Buscamos y metemos en la variable resultado todo lo de la tabla productos-->
  <div class="container">
    <h1>Lista de productos</h1>
  </div>
  <?php
  $sql = "SELECT * FROM productos";
  $resultado = $conexion->query($sql);
  $productos = [];

/* Recorremos y creamos un objeto Producto de cada fila y se añade a un array*/ 

  while ($fila = $resultado->fetch_assoc()) {
    $nuevo_producto =
      new Producto(
        $fila["idProducto"],
        $fila["nombreProducto"],
        $fila["precio"],
        $fila["descripcion"],
        $fila["cantidad"],
        $fila["imagen"]
      );
    array_push($productos, $nuevo_producto);
  }
  ?>

<!-- Creamos una tabla -->
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID Producto</th>
        <th>Nombre</th>
        <th>Precio producto</th>
        <th>Descripción</th>
        <th>Cantidad</th>
        <th>Imagen</th>
      </tr>
    </thead>
    <tbody>
      <?php
      /* Iteramos y para cada producto se genera una fila con celdas que van a contener informacion del producto */ 
      foreach ($productos as $producto) {
        echo "<tr>
                    <td>" . $producto->idProducto . "</td>
                    <td>" . $producto->nombreProducto . "</td>
                    <td>" . $producto->precio . "</td>
                    <td>" . $producto->descripcion . "</td>
                    <td>" . $producto->cantidad . "</td>
                    <td>" ?>
        <img width="50" height="75" src="<?php echo '../' . $producto->imagen ?>"></td>


        <?php
        /* Aqui compruebo que si el usuario no es invitado aparecera un boton de añadir los productos*/ 
        if (($_SESSION["usuario"]) != "invitado") { ?>
          <td>
            <form action="" method="post">
              <input type="hidden" name="id_producto" value="<?php echo $producto->idProducto ?>">
              <input class="btn btn-warning" type="submit" value="Añadir">
            </form>
          </td>
        <?php
        }
        ?>

      </tr>

      <?php "</tr>";
      }
      ?>
    </tbody>
  </table>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>