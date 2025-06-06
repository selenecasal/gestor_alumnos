<?php
session_start();
echo "<div class='caja'>";
include("PHP/conexion.php");

$isAdmin = false;
$ingreso = 'no';


if (isset($_SESSION['nombreusuario'])) {
    $usuario = mysqli_real_escape_string($conexion, $_SESSION['nombreusuario']);
    $sql = "SELECT * FROM usuario WHERE usuario = '$usuario'";
    $result = mysqli_query($conexion, $sql);
    $ingreso= 'si';  
    if ($row = mysqli_fetch_array($result)) {
        $isAdmin = ($row['permiso'] === 'p'); 
    } else {
        echo "<script>alert('Usuario no encontrado.'); window.location.href='PHP/login.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <title>Bienvenido - Página de Inicio</title>
    <link rel="stylesheet" href="Css/style.css">
</head>
<body>
    <h1>Bienvenido a Gestion Escolar</h1>
    <p class="subtitle">Seleccione una opción para continuar</p>
    <div class="button-container">
        <a href="PHP/informes.php" rel="noopener" class="ingre button-link">Informes</a>
        <a href="PHP/agregar_alumno.php" rel="noopener" class="ingre adm button-link">Agregar Alumno</a>
        <a href="PHP/ingresar_notas.php" rel="noopener" class="ingre adm button-link">Ingresar Notas</a>
        <a href="PHP/permiso.php" rel="noopener" class="ingre adm button-link">Agregar Permiso</a>
        <a href="PHP/agregar_materias.php" rel="noopener" class="ingre adm button-link">Agregar Materias</a>
        <a href="PHP/login.php" rel="noopener" class="ing button-link">Ingresar</a>
        <a href="PHP/registrarse.php" rel="noopener" class="ing button-link">Registrarse</a>
        <a href="PHP/cerrar_session.php" rel="noopener" class="ingre button-link">Cerrar Sesión</a>
    </div>

    <script>
        // Ocultar opciones para usuarios no administradores
        const isAdmin = <?php echo json_encode($isAdmin); ?>;
        if (!isAdmin) {
            const admElements = document.querySelectorAll('.adm');
            admElements.forEach(function(element) {
                element.style.display = 'none';
            });
        }
        // Ocultar opciones para usuarios logueados
        const ingreso = <?php echo json_encode($ingreso); ?>;
        if (ingreso === 'si') {
            const ingElements = document.querySelectorAll('.ing');
            ingElements.forEach(function(element) {
                element.style.display = 'none';
            });
        }else{
            //ocultar cerrar sesion x gente no logueada
            const ingreElements = document.querySelectorAll('.ingre');
            ingreElements.forEach(function(element) {
                element.style.display = 'none';
            });
        }
    </script>
</body>
</html>