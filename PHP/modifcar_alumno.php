<?php
session_start();
include('conexion.php');
$permiso = 'n';

if (isset($_SESSION["nombreusuario"])) {
    $usuario = mysqli_real_escape_string($conexion, $_SESSION['nombreusuario']);
    $sql = "SELECT * FROM usuario WHERE usuario = '$usuario'";
    $result = mysqli_query($conexion, $sql);
    
    if ($result && $row = mysqli_fetch_array($result)) {
        if ($row['permiso'] === 'p') {
            $permiso = 'p';
        }
    }

    if ($permiso === 'p') {

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    
</body>
</html>
