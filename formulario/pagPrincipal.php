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
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST["id_producto"];
    echo "<br><br><p>La El producto seleccionado es $id_producto</p>";

    $usuario = $_SESSION['usuario'];
    $sql1 = "SELECT idCestas FROM cestas WHERE usuario = '$usuario'";
    $res = $conexion->query($sql1);

    if ($res->num_rows > 0) {
      $filaCestas = $res->fetch_assoc();
      $id_cesta = $filaCestas["idCestas"];
    }

    $sql2 = "INSERT INTO productoscestas (idProducto, idCesta, cantidad) 
        VALUES ($id_producto, $id_cesta, 1)";
    $conexion->query($sql2);

    // para insertar en productos_cestas: id_producto, id_cesta, cantidad
    /*
            id_producto: lo tenemoos
            cantidad: la tenemos (1)
            ¿id_cesta?
            habrá que coger con la sesión el usuario actual
            y hacer una consulta a la tabla cestas para sacar el id de la cesta

            luego podremos insertar en productos_cestas
        */
  }
  ?>

  <!-- NAVAR -->
  <nav class="navbar navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Offcanvas dark navbar</a>
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



  <div class="container1">
    <!-- CAROUSEL
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="../img/img1.png" class="d-block w-100" alt="">
    </div>
    <div class="carousel-item">
      <img src="../img/gal1.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="../img/gal2.jpg" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
 -->
  </div>
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

  </div>
  <div class="container">
    <h1>Lista de productos</h1>
  </div>
  <?php
  $sql = "SELECT * FROM productos";
  $resultado = $conexion->query($sql);
  $productos = [];

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



        <?php
        /*
          if($usuario != "invitado"){
            "<td></td>"
          }
          */
        ?>


      <?php "</tr>";
      }
      ?>
    </tbody>
  </table>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>