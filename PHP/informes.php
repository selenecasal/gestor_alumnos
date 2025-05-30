<?php
session_start();
include('conexion.php');

if (!isset($_SESSION['nombreusuario'])) {
    header("Location: registrarse.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Informes</title>
    <link rel="stylesheet" href="../Css/style.css?1" />
</head>
<body>
    <div class="caja_login">

        <h1>Informes</h1>
        <h2>Seleccione el filtro que desea:</h2>
        <form method="POST" action="informes.php">
            <select name="curso" id="curso">
                <option value="">Seleccione un curso</option>
                <option value="primero" <?= (isset($_POST['curso']) && $_POST['curso'] === 'primero') ? 'selected' : ''; ?>>Primero</option>
                <option value="segundo" <?= (isset($_POST['curso']) && $_POST['curso'] === 'segundo') ? 'selected' : ''; ?>>Segundo</option>
                <option value="tercero" <?= (isset($_POST['curso']) && $_POST['curso'] === 'tercero') ? 'selected' : ''; ?>>Tercero</option>
                <option value="cuarto" <?= (isset($_POST['curso']) && $_POST['curso'] === 'cuarto') ? 'selected' : ''; ?>>Cuarto</option>
                <option value="quinto" <?= (isset($_POST['curso']) && $_POST['curso'] === 'quinto') ? 'selected' : ''; ?>>Quinto</option>
                <option value="sexto" <?= (isset($_POST['curso']) && $_POST['curso'] === 'sexto') ? 'selected' : ''; ?>>Sexto</option>
                <option value="septimo" <?= (isset($_POST['curso']) && $_POST['curso'] === 'septimo') ? 'selected' : ''; ?>>Septimo</option>
            </select>
            <select name="alumno" id="alumno">
                <option value="">Seleccione un alumno</option>
                <?php
                $query = "SELECT * FROM alumno";
                $result = mysqli_query($conexion, $query);
                if ($result) {
                    while ($row = mysqli_fetch_array($result)) {
                        $selected = (isset($_POST['alumno']) && $_POST['alumno'] == $row['id_alumno']) ? 'selected' : '';
                        echo "<option value='" . $row['id_alumno'] . "' $selected>" . htmlspecialchars($row['nombre'] . " " . $row['apellido']) . "</option>";
                    }
                }
                ?>
            </select>
            <select name="materia" id="materia">
                <option value="">Seleccione una materia</option>
                <?php
                $query = "SELECT * FROM materia";
                $result = mysqli_query($conexion, $query);
                if ($result) {
                    while ($row = mysqli_fetch_array($result)) {
                        $selected = (isset($_POST['materia']) && $_POST['materia'] == $row['id_materia']) ? 'selected' : '';
                        echo "<option value='" . $row['id_materia'] . "' $selected>" . htmlspecialchars($row['nombre'] . " " . $row['modalidad']) . "</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" name="enviar" value="Generar Informe" />
        </form>

        <table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Materia</th>
                    <th>Promedio</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $curso = '';
            $alumno = '';
            $materia = '';
            if (isset($_POST['enviar'])) {
                $curso = isset($_POST['curso']) ? mysqli_real_escape_string($conexion, $_POST['curso']) : '';
                $alumno = isset($_POST['alumno']) ? mysqli_real_escape_string($conexion, $_POST['alumno']) : '';
                $materia = isset($_POST['materia']) ? mysqli_real_escape_string($conexion, $_POST['materia']) : '';

                // Construir la consulta con filtros aplicados
                $query = "SELECT alumno.nombre, alumno.apellido, materia.nombre AS materia, AVG(nota.nota) AS promedio 
                          FROM alumno
                          JOIN nota ON alumno.id_alumno = nota.id_alumno
                          JOIN materia ON nota.id_materia = materia.id_materia";

                $conditions = [];

                if ($curso !== '') {
                    $conditions[] = "alumno.curso = '$curso'";
                }
                if ($alumno !== '') {
                    $conditions[] = "alumno.id_alumno = '$alumno'";
                }
                if ($materia !== '') {
                    $conditions[] = "materia.id_materia = '$materia'";
                }

                if (count($conditions) > 0) {
                    $query .= " WHERE " . implode(" AND ", $conditions);
                }

                $query .= " GROUP BY alumno.id_alumno, materia.id_materia, alumno.nombre, alumno.apellido, materia.nombre";

                $result = mysqli_query($conexion, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['apellido']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['materia'] ?? '---') . "</td>";
                        echo "<td>";
                        if (is_null($row['promedio'])) {
                            echo "---";
                        } else {
                            echo number_format($row['promedio'], 2);
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No se encontraron resultados.</td></tr>";
                }
            } else {
                // Mostrar todos los registros si no se han aplicado filtros
                $query = "SELECT alumno.nombre, alumno.apellido, materia.nombre AS materia, AVG(nota.nota) AS promedio 
                          FROM alumno
                          JOIN nota ON alumno.id_alumno = nota.id_alumno
                          JOIN materia ON nota.id_materia = materia.id_materia
                          GROUP BY alumno.id_alumno, materia.id_materia, alumno.nombre, alumno.apellido, materia.nombre";

                $result = mysqli_query($conexion, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['apellido']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['materia'] ?? 'Sin materia') . "</td>";
                        echo "<td>";
                        if (is_null($row['promedio'])) {
                            echo "Sin nota";
                        } else {
                            echo number_format($row['promedio'], 2);
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No se encontraron resultados.</td></tr>";
                }
            }
            ?>
            </tbody>
        </table>
        <a href="../index.php">Volver</a>
    </div>
</body>
</html>


