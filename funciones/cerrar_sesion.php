<?php
    session_start(); // Que hace esto, crea sesion, la recupera o ambas cosas. Hace ambas
    session_destroy();
    header('location: ../formulario/pagPrincipal.php');

?>