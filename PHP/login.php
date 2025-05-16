<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="Css/style.css?2">
</head>
<body>
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
        echo "<script>alert('BIENVENIDO ADMIN'); window.location = '../index.php'</script>";
        die();
    } else {
        echo "<script>alert('BIENVENIDO!'); window.location = '../index.php'</script>";
        die();
    }
} else {
//si no va al login
if(isset($_POST['btn_enviar'])){
        
    if(!$conexion){
        die("No hay conexion: ".mysqli_connect_error());
    }
    $usuario= $_POST['usuario'];
    $pass = $_POST['contrasenia'];
    if($usuario== 'admin' && $pass == 'admin'){
        $_SESSION['nombreusuario']=$usuario;
        $_SESSION['permiso'] = 'p'; // Asignar permiso de administrador
        echo "<script>alert('BIENVENIDO ADMIN'); window.location = '../index.php'</script>";
        die();
    }
    $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario = '".$usuario."' AND pass = '".$pass."'");
    $nr = mysqli_num_rows($query);

    if($nr == 1){
        //si el usuario esta ingresando por primera vez se guarda su nombre en la sesion
        $_SESSION['nombreusuario']=$usuario;
        echo "<script>alert('BIENVENIDO!'); window.location = '../index.php'</script>";
            die();
        }else if ($nr == 0){
            echo "<script>alert('Usuario no registrado'); window.location = '../index.php'</script>";
        } 
}
?>
    <div class="caja_login">
        <h1>LOGIN</h1>
        <form action="login.php" method="POST">
            <label for="usuario">Usuario: </label>
            <input type="text" name="usuario" placeholder="USUARIO" required>
            <label for="contraseña">Contraseña: </label>
            <input type="password" name="contrasenia" id="" placeholder="PASSWORD" required>
            <input type="submit" value="ENVIAR" name="btn_enviar" class="btn_enviar">
            <a href="#">¿Olvidaste tu contraseña?</a>
            <a href="../index.php">Volver</a>
            <a href="cerrar_sesion.php">Cerrar sesión</a>
            <a href="registrarse.php">Registrarse</a>
        </form>    
    </div>
</body>
</html>
<?php
}