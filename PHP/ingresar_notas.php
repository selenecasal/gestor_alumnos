<?php
session_start();
include('conexion.php');
$permiso='n';
if (isset($_SESSION["nombreusuario"])) {
    
    $usuario = mysqli_real_escape_string($conexion, $_SESSION['nombreusuario']);
    $sql = "SELECT * FROM usuario WHERE usuario = '$usuario'";
    $result = mysqli_query($conexion, $sql);
    if ($row = mysqli_fetch_array($result)) {
        if($row['permiso'] === 'p'){
            $permiso= 'p';
        }
    }

    if ($permiso === 'p') {
        if(isset($_POST['enviar'])){
            $id_alumno = mysqli_real_escape_string($conexion, $_POST['alumno']);   
            $id_materia = mysqli_real_escape_string($conexion, $_POST['materia']);
            $nota = mysqli_real_escape_string($conexion, $_POST['nota']);

            // Validar que alumno exista
            $query = "SELECT * FROM alumno WHERE id_alumno = '$id_alumno'";
            $result = mysqli_query($conexion, $query);
            if ($row = mysqli_fetch_array($result)) {
                // Validar que materia exista
                $query = "SELECT * FROM materia WHERE id_materia = '$id_materia'";
                $result_mat = mysqli_query($conexion, $query);
                if ($row_mat = mysqli_fetch_array($result_mat)) {
                    $query_insert = "INSERT INTO nota (id_alumno, id_materia, nota, fecha) VALUES ('$id_alumno', '$id_materia', '$nota', NOW())";
                    if (mysqli_query($conexion, $query_insert)) {
                        echo "<script>alert('Nota ingresada correctamente');</script>";
                    } else {
                        echo "<script>alert('Error al ingresar la nota');</script>";
                    }
                } else {
                    echo "<script>alert('Materia no encontrada');</script>";
                }
            } else {
                echo "<script>alert('Alumno no encontrado');</script>";
            }
        }if(isset($_POST['eliminar'])){
            $id_nota = mysqli_real_escape_string($conexion, $_POST['id_nota']);
            $query_delete = "DELETE FROM nota WHERE id_nota = '$id_nota'";
            if (mysqli_query($conexion, $query_delete)) {
                echo "<script>alert('Nota eliminada correctamente');</script>";
            } else {
                echo "<script>alert('Error al eliminar la nota');</script>";
            }
        }

    } else {
        echo "<script>alert('NO TIENES PERMISO'); window.location = '../index.php'</script>";
        exit;
    }
} else {
    header("Location: registrarse.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ingresar Notas</title>
    <link rel="stylesheet" href="../Css/style.css?4">
</head>
<body>
    <div class="container">
        <h1 class="titulo" >Ingresar Notas</h1>
        <form action="" method="post">
            <label for="alumno">Selecciona el Alumno:</label>
            <select name="alumno" id="alumno" required>
                <?php
                $query = "SELECT * FROM alumno ORDER BY nombre, apellido";
                $result = mysqli_query($conexion, $query);
                while ($row = mysqli_fetch_array($result)) {
                    echo "<option value='" . $row['id_alumno'] . "'>" . htmlspecialchars($row['nombre'] . " " . $row['apellido']) . "</option>";
                }
                ?>
            </select>
            <label for="materia">Selecciona la materia:</label>
            <select name="materia" id="materia" required>
                <?php
                $query_mat = "SELECT * FROM materia ORDER BY nombre";
                $result_mat = mysqli_query($conexion, $query_mat);
                while ($row_mat = mysqli_fetch_array($result_mat)) {
                    echo "<option value='" . $row_mat['id_materia'] . "'>" . htmlspecialchars($row_mat['nombre']) . "</option>";
                }
                ?>
            </select>
            <br>
            <label for="nota">Ingresa nota:</label>
            <input type="number" name="nota" id="nota" placeholder="Nota" min="0" max="100" step="0.1" required>
            <br>
            <a href="../index.php">Volver</a>
            <input type="submit" name="enviar" value="Agregar nota">
        </form>

        <h2>Eliminar Nota</h2>
        <form action="ingresar_notas.php" method="post">
            <label for="id_nota">Selecciona la Nota a eliminar:</label>
            <select name="id_nota" id="id_nota" required>
                <?php
                $query = "SELECT * FROM nota INNER JOIN alumno ON nota.id_alumno = alumno.id_alumno INNER JOIN materia ON nota.id_materia = materia.id_materia";
                $result = mysqli_query($conexion, $query);
                while ($row = mysqli_fetch_array($result)) {
                    echo "<option value='" . $row['id_nota'] . "'>" . htmlspecialchars($row['nombre'] . " " . $row['apellido'] . " - " . $row['nombre_materia'] . " - " . $row['nota']) . "</option>";
                }
                ?>
            </select>
            <br>
            <input type="submit" name="eliminar" value="Eliminar nota">
        </form>

    </div>
</body>
</html>














<?php
/*
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
        }// Verifica si es administrador
    }

    if ($permiso === 'p') {
        if(isset($_POST['enviar'])){
            $nombre_alumno = mysqli_real_escape_string($conexion, $_POST['nombre']);   
            $apellido_alumno = mysqli_real_escape_string($conexion, $_POST['Apellido']);
            $id_materia = mysqli_real_escape_string($conexion, $_POST['materia']);
            $query = "SELECT * FROM alumno WHERE nombre = '$nombre_alumno' AND apellido = '$apellido_alumno'";
            $result = mysqli_query($conexion, $query);
            if ($row = mysqli_fetch_array($result)) {
                $id_alumno = $row['id_alumno'];
                $query = "SELECT * FROM materia WHERE id_materia = '$id_materia'";
                $result = mysqli_query($conexion, $query);
                if ($row = mysqli_fetch_array($result)) {
                    $id_materia = $row['id_materia'];
                    $nota = mysqli_real_escape_string($conexion, $_POST['nota']);
                    $query = "INSERT INTO nota (id_alumno, id_materia, nota, fecha) VALUES ('$id_alumno', '$id_materia', '$nota', NOW())";
                    if (mysqli_query($conexion, $query)) {
                        echo "<script>alert('Nota ingresada correctamente');</script>";
                    } else {
                        echo "<script>alert('Error al ingresar la nota');</script>";
                    }
                } else {
                    echo "<script>alert('Materia no encontrada');</script>";
                }
            } else {
                echo "<script>alert('Alumno no encontrado');</script>";
            }
        }
        echo"</div>";
    }else{
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
    <title>Document</title>
</head>
<body>
    Ingresar Notas:
    <form action="" method="post">
        
        <label for="nombre">Nombre del Alumno: </label>
        <?php
        echo "<select name='nombre' id='nombre' required>";
            $query = "SELECT * FROM alumno";
            $result = mysqli_query($conexion, $query);
            while ($row = mysqli_fetch_array($result)) {
                echo "<option value='" . $row['id_alumno'] . "'>" . $row['nombre']. "</option>";
            }
        echo "</select>";
        echo "<br>";
        echo "<select name='Apellido' id='Apellido' required>";
            $query = "SELECT * FROM alumno";
            $result = mysqli_query($conexion, $query);
            while ($row = mysqli_fetch_array($result)) {
                echo "<option value='" . $row['id_alumno'] . "'>" . $row['apellido']. "</option>";
            }
        echo "</select>";
        ?>
        
        <br>
        selecciona la materia:
        <select name="materia" id="materia" required>
            <?php
            $query = "SELECT * FROM materia";
            $result = mysqli_query($conexion, $query);
            while ($row = mysqli_fetch_array($result)) {
                echo "<option value='" . $row['id_materia'] . "'>" . $row['nombre'] . "</option>";

            }
            ?>
        ingresar nota:
        <input type="number" name="nota" placeholder="Nota" required>
        <br>
        <input type="submit" name="enviar" value="Enviar">
</body>
</html>
*/