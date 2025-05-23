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
            $permiso = 'p';
        }
    }

    if ($permiso === 'p') {
        if(isset($_POST['enviar'])){
            $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']) ;
            $apellido = mysqli_real_escape_string($conexion, $_POST['Apellido']) ;
            $dni = mysqli_real_escape_string($conexion, $_POST['DNI']) ;
            $curso = mysqli_real_escape_string($conexion, $_POST['Curso']) ;
            $division = mysqli_real_escape_string($conexion, $_POST['Division']) ;
            $modalidad = mysqli_real_escape_string($conexion, $_POST['Modalidad']) ;
            $fecha_nacimiento = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']) ;
            $fecha_nacimiento = date('Y-m-d', strtotime($fecha_nacimiento)); // Formato de fecha YYYY-MM-DD
            $sql= "INSERT INTO alumno(nombre, apellido, dni, curso, division, modalidad, fecha_nacimiento) VALUES ('$nombre', '$apellido','$dni','$curso' , '$division', '$modalidad', '$fecha_nacimiento')";
            $result = mysqli_query($conexion, $sql);
            if($result){
                echo "<h3 class='texto'>Alumno agregado con exito </h3>";
            }else{
                echo "<h3 class='texto'> error al agregar alumno </h3>" . mysqli_error($conexion);
            }
        }
        echo"</div>";
        
        }else{
            echo "<script>alert('NO TIENES PERMISO'); window.location = '../index.php'</script>";
        
        }
        }
        mysqli_close($conexion);
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <div class="fondo_agregar">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar alumno</title>
    <link rel="stylesheet" href="../Css/style.css?1">
</head>
<body>
    <div class="caja_login">
    <form action="agregar_alumno.php" method="post" enctype="multipart/form-data">
        <h1>Agrega alumno: </h1>
        <label for="nombre">Nombre del Alumno:</label>
        <input type="text" name="nombre" placeholder="Nombre del Alumno" required>
        <br>
        <label for="Apellido">Apellido del Alumno:</label>
        <input type="text" name="Apellido" placeholder="Apellido del Alumno" required>
        <br>
        <label for="DNI">DNI del Alumno:</label>
        <input type="text" name="DNI" placeholder="DNI del Alumno" required>
        <br>
        <label for="Curso">Curso: </label>
        <select name="Curso" id="Curso" required>
            <option value="primero">Primero</option>
            <option value="segundo">segundo</option>
            <option value="tercero">tercero</option>
            <option value="cuarto">cuarto</option>
            <option value="quinto">quinto</option>
            <option value="sexto">sexto</option>
            <option value="septimo">septimo</option>
        </select>
        <br>
        <label for="Division">Division: </label>
        <select name="Division" id="Division" required>
            <option value="primera">Primera</option>
            <option value="segunda">segunda</option>
        </select>
        <br>
        <label for="Modalidad">Modalidad: </label>
        <select name="Modalidad" id="Modalidad" required>
            <option value="basica">basica</option>
            <option value="tecmu">tecmu</option>
            <option value="tecpro">tecpro</option>
        </select>
        <br>
        <label for="fecha_nacimiento">Fecha de Nacimiento: </label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required>
        <br>
        <input type="submit" name="enviar" value="enviar">
</form>
        <br>
        <a href="../index.php">VOLVER</a>
        </div>
</body>
</html>