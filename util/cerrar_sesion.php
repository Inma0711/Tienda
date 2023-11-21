<?php
    session_start(); // Que hace esto, crea sesion, la recupera o ambas cosas. Hace ambas
    session_destroy();
    $_SESSION["usuario"] = "invitado";
    header('location: ../views/pagPrincipal.php');

?>