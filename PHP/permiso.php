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
    <title>Dar permiso</title>
    <link rel="stylesheet" href="../Css/style.css?1">
</head>
<body>
    <div class="fondo_agregar">
        <div class="caja_login">
            <form action="permiso.php" method="post" enctype="multipart/form-data">
                <h1>Agrega permiso: </h1>
                <label for="nombre">Nombre del Usuario para permitir privilegios:</label>
                <select name="nombre" id="nombre" required>
                    <?php
                    $query = "SELECT * FROM usuario";
                    $result = mysqli_query($conexion, $query);
                    if ($result) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . htmlspecialchars($row['usuario']) . "'>" . htmlspecialchars($row['usuario']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>Error al cargar usuarios</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="BUSCAR" value="AGREGAR PERMISO">
                <br>
                <h1>Quitar permiso: </h1>
                <label for="quitar_permiso">Quitar permiso:</label>
                <select name="quitar_permiso" id="quitar_permiso">
                    <?php
                    $query = "SELECT * FROM usuario";
                    $result = mysqli_query($conexion, $query);
                    if ($result) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . htmlspecialchars($row['usuario']) . "'>" . htmlspecialchars($row['usuario']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>Error al cargar usuarios</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="QUITAR" value="QUITAR PERMISO">
                <br>
                <a href="../index.php">Volver</a>
            </form>
        </div>
    </div>

<?php
    if (isset($_POST['BUSCAR'])) {
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $sql = "SELECT * FROM usuario WHERE usuario = '$nombre'";
        $result = mysqli_query($conexion, $sql);
        
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                
                $sql = "UPDATE usuario SET permiso = 'p' WHERE usuario = '$nombre'";
                if (mysqli_query($conexion, $sql)) {
                    echo "<h3 class='texto'>Usuario encontrado y ya se le dieron los permisos a: $nombre</h3>";
                } else {
                    echo "<h3 class='texto'>Error al actualizar los permisos: " . mysqli_error($conexion) . "</h3>";
                }
            } else {
                echo "<h3 class='texto'>No se encontró el usuario</h3>";
            }
        } else {
            echo "<h3 class='texto'>Error al buscar el usuario: " . mysqli_error($conexion) . "</h3>";
        }
    }
    else if(isset($_POST["QUITAR"])){
        $nombre = mysqli_real_escape_string($conexion, $_POST['quitar_permiso']);
        $sql = "SELECT * FROM usuario WHERE usuario = '$nombre'";
        $result = mysqli_query($conexion, $sql);
        
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                // Update the user's permission
                $sql = "UPDATE usuario SET permiso = 'n' WHERE usuario = '$nombre'";
                if (mysqli_query($conexion, $sql)) {
                    echo "<h3 class='texto'>Usuario encontrado y se le quitaron los permisos a: $nombre</h3>";
                } else {
                    echo "<h3 class='texto'>Error al actualizar los permisos: " . mysqli_error($conexion) . "</h3>";
                }
            } else {
                echo "<h3 class='texto'>No se encontró el usuario</h3>";
            }
        } else {
            echo "<h3 class='texto'>Error al buscar el usuario: " . mysqli_error($conexion) . "</h3>";
        }
    }
}
}
mysqli_close($conexion);
?>