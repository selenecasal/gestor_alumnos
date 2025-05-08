<?php
session_start();
include('conexion.php');
if(!isset($_SESSION['nombreusuario'])){
    header("Location: ../index.php");
    exit();
}else{
if($_SESSION['permiso'] === 'p'){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <div class="fondo_agregar">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dar permiso</title>
    <link rel="stylesheet" href="../Css/estilos.css?1">
</head>
<body>
    <div class="agregar_permiso">
    <form action="permiso.php" method="post" enctype="multipart/form-data">
        <h1>Agrega permiso: </h1>
        <label for="nombre">Nombre del Usuario para permitir privilegios:</label>
        <input type="text" name="nombre" placeholder="Nombre del Usuario" required>
        <br>
        <input type="submit" name="BUSCAR" value="BUSCAR">
    </form></div>
 
</body>
</html>
<?php
if(isset($_POST['BUSCAR'])){
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']) ;
    $sql= "SELECT * FROM usuario WHERE nombre = '$nombre'";
    $result = mysqli_query($conexion, $sql);
    $sql= "UPDATE usuario SET permiso = 'p' WHERE nombre = '$nombre'";
    $result = mysqli_query($conexion, $sql);
    if(mysqli_num_rows($result)> 0){
        while($row = mysqli_fetch_assoc($result)){
            $nombre = $row['nombre'];
            $apellido = $row['apellido'];
        }
        echo "<h3 class='texto'>Usuario encontrado y ya se le dieron los permisos
        a : $nombre $apellido</h3>";
    }else{
        echo "<h3 class='texto'>No se encontro el usuario</h3>";
    }
    echo "<div class='boton'>";
    echo"</div>";
}
}
}
mysqli_close($conexion);
?>