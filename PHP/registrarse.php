<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registrarse</title>
    <link rel="stylesheet" href="../Css/style.css?1">
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
        }// Verifica si es administrador
    }

    if ($permiso === 'p') {
        echo "<script>alert('BIENVENIDO ADMIN'); window.location = '../index.php'</script>";
        die();
    } else {
        echo "<script>alert('BIENVENIDO!'); window.location = '../index.php'</script>";
        die();
    }
} else {

    ?>
    <div class="caja_login">
        <h1>REGISTRARSE</h1>
        <form action="registrarse.php" method="POST" >
            <label for="usuario">Usuario: </label>
            <input type="text" name="usuario" placeholder="USUARIO" size="25px" style="height: 30px;" required><br>
            <label for="pass">Contraseña: </label>
            <input type="password" name="pass" placeholder="PASSWORD" size="25px" style="height: 30px;" required><br>
            <input type="submit" value="Registrarse" name="Registrarse" class="btn_enviar">
            <a href="../index.php"><h3>Volver al inicio</h3></a> 
            
            <a href="login.php"><h3>¿Ya estas registrado? inicia sesion.</h3></a>
        </form>
    </div>
    <br><br>

</div>
</body>
</html>
<?php
if(isset($_POST['Registrarse'])){
    $usuario = $_POST['usuario'];
    $password = $_POST['pass'];

    // Verificar que el usuario ya no está registrado
    $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario='$usuario'");
    $num = mysqli_num_rows($query);

    if ($num > 0) {
        echo "<script>alert('El usuario ya está registrado.'); window.location='registrarse.php';</script>";
        exit();
    }else {
        $permiso = 'n'; // Asignar permiso por defecto a 'u' (usuario normal)
        /*
        $password = password_hash($password, PASSWORD_BCRYPT); // Encriptar la contraseña
    }*/
     // Insertar usuario
        $sql_insert = "INSERT INTO usuario(permiso, usuario, pass) 
        VALUES ('$permiso','$usuario', '$password' )";
        
        $query = mysqli_query($conexion, $sql_insert);
        
        if ($query) {
            echo "<script>alert('Usuario registrado'); window.location = '../index.php';</script>";
        } else {
            echo "<div class='alert alert-danger'>Error: Hay un error en la inserción!</div>";
        }
    }
    }
}
?>