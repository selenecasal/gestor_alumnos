<?php
$servidor = "localhost";
$usuario = "root";
$pasword = "";
$basedatos = "gestion_escolar";
$conexion = mysqli_connect($servidor, $usuario, $pasword, $basedatos);
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>