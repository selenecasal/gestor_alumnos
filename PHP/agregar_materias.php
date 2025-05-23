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
        }
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
        if (isset($_POST['eliminar_materia'])) {
            $id_materia = $_POST['eliminar'];
            $query = "DELETE FROM materia WHERE id_materia = '$id_materia'";
            $result = mysqli_query($conexion, $query);
            $query = "DELETE FROM notas WHERE id_materia = '$id_materia'";
            $resul = mysqli_query($conexion, $query);
            if ($result && $resul) {
                echo "Materia eliminada correctamente.";
            } else {
                echo "Error al eliminar la materia.";
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
        <input type="text" name="nombre" placeholder="Nombre de la Materia">
        <br>
        <label for="Modalidad">Modalidad: </label>
        <select name="Modalidad" id="Modalidad">
            <option value="basica">basica</option>
            <option value="tecmu">tecmu</option>
            <option value="tecpro">tecpro</option>
        </select>
        <br>
        <a href="../index.php">Volver</a>
        <input type="submit" name="enviar" value="enviar">
    </form>
    <br>
        <form action="agregar_materias.php" method="post">
            <label for="eliminar">Eliminar Materia:</label>
            <select name="eliminar" id="eliminar">
                <?php
                $query = "SELECT * FROM materia";
                $result = mysqli_query($conexion, $query);
                while ($row = mysqli_fetch_array($result)) {
                    echo "<option value='" . $row['id_materia'] . "'>" . $row['nombre'] . "</option>";
                }
                ?>
            </select>
            <input type="submit" name="eliminar_materia" value="Eliminar">
        </form>
    </div>
</body>
</html>
