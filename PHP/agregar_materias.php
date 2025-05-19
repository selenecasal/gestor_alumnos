<?php
session_start();
include('conexion.php');
$permiso='n';
if (isset($_SESSION["nombreusuario"])) {
    $usuario = mysqli_real_escape_string($conexion, $_SESSION['nombreusuario']);
    $sql = "SELECT * FROM usuario WHERE usuario = '$usuario'";
    $result = mysqli_query($conexion, $sql);
    if ($row = mysqli_fetch_array($result)) {
        if($permiso = ($row['permiso'] === 'p')){
            $permiso= 'p';
        } // Verifica si es administrador
    }

    if ($permiso === 'p') {
        if (isset($_POST['enviar'])) {
            $nombre = $_POST['nombre'];
            $modalidad = $_POST['Modalidad'];

            $query = "INSERT INTO materia (nombre, modalidad) VALUES ('$nombre', '$modalidad')";
            $result = mysqli_query($conexion, $query);

            if ($result) {
                echo "Materia agregada correctamente.";
            } else {
                echo "Error al agregar la materia.";
            }
        }
    } else {
        echo "<script>alert('NO TIENES PERMISO'); window.location = '../index.php'</script>";
        
    }
} else {
    header("Location: registrarse.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Materias</title>
    <link rel="stylesheet" href="../Css/style.css?5">
</head>
<body>
    <div class="caja_login">
    <h1>Agregar Materias:</h1>
    
    <form action="agregar_materias.php" method="post">
        <label for="nombre">Nombre de la Materia:</label>
        <input type="text" name="nombre" placeholder="Nombre de la Materia" required>
        <br>
        <label for="Modalidad">Modalidad: </label>
        <select name="Modalidad" id="Modalidad" required>
            <option value="basica">basica</option>
            <option value="tecmu">tecmu</option>
            <option value="tecpro">tecpro</option>
        </select>
        <br>
        <a href="../index.php">Volver</a>
        <input type="submit" name="enviar" value="enviar">
    </div>
</body>
</html>
