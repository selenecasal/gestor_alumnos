<?php
session_start();
include('conexion.php');
if(!isset($_SESSION['nombreusuario'])){
    header("Location: registrarse.php");
    exit();
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes</title>
</head>
<body>
    <h1>Informes</h1>
    <h2>Seleccione el filtro que desea:</h2>
    <form method="POST" action="informes.php">
        <select name="curso" id="curso">
            <option value="">Seleccione un curso</option>
            <option value="primero">Primero</option>
            <option value="segundo">Segundo</option>
            <option value="tercero">Tercero</option>
            <option value="cuarto">Cuarto</option>
            <option value="quinto">Quinto</option>
            <option value="sexto">Sexto</option>
            <option value="septimo">Septimo</option>
        </select>
        <select name="alumno" id="alumno">
            <option value="alumno">Seleccione un alumno</option>
            <?php
            $query = "SELECT * FROM alumno";
            $result = mysqli_query($conexion, $query);
            while ($row = mysqli_fetch_array($result)) {
                echo "<option value='" . $row['id_alumno'] . "'>" . $row['nombre'] . " " . $row['apellido'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="enviar" value="Generar Informe">
    </form>
    
    <table>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Materia</th>
            <th>Promedio</th>
        </tr>
        <?php
        if(isset($_POST["enviar"])){
            $curso = mysqli_real_escape_string($conexion, $_POST['curso']);
            $alumno = mysqli_real_escape_string($conexion, $_POST['alumno']);
            
            
            $query = "SELECT alumno.nombre, alumno.apellido, materia.nombre AS materia, AVG(nota.nota) AS promedio 
                      FROM nota 
                      JOIN alumno ON nota.id_alumno = alumno.id_alumno 
                      JOIN materia ON nota.id_materia = materia.id_materia";
            
            $conditions = [];
            if (!empty($curso)) {
                $conditions[] = "alumno.curso = '$curso'";
            }
            if (!empty($alumno)) {
                $conditions[] = "alumno.id_alumno = '$alumno'";
            }
            if (count($conditions) > 0) {
                $query .= " WHERE " . implode(" AND ", $conditions);
            }
            $query .= " GROUP BY alumno.id_alumno";
            
            $result = mysqli_query($conexion, $query);
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row['nombre']."</td>";
                echo "<td>".$row['apellido']."</td>";
                echo "<td>".$row['materia']."</td>";
                echo "<td>".$row['promedio']."</td>";
                echo "</tr>";
            }
        } else {
            
            $query = "SELECT alumno.nombre, alumno.apellido, materia.nombre AS materia, AVG(nota.nota) AS promedio 
                      FROM nota 
                      JOIN alumno ON nota.id_alumno = alumno.id_alumno 
                      JOIN materia ON nota.id_materia = materia.id_materia 
                      GROUP BY alumno.id_alumno";
            $result = mysqli_query($conexion, $query);
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row['nombre']."</td>";
                echo "<td>".$row['apellido']."</td>";
                echo "<td>".$row['materia']."</td>";
                echo "<td>".$row['promedio']."</td>";
                echo "</tr>";
            }
        }
        ?>
        <a href="../index.php">Volver</a>
    </table>
</body>
</html>
<?php
}
?>
