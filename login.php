<?php
require './includes/db/db.php';
$db = conectarDB();

$errores = [];

$usuario = '';
$password = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['email'];
    $password = $_POST['password'];

    if (!$usuario) {
        $errores[] = "Ingresa un email";
    }
    if (!$password) {
        $errores[] = "Ingresa una contraseña";
    }


    if (empty($errores)) {

        $query = "SELECT * FROM usuario WHERE correo = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $entrarPerfil = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($entrarPerfil)) {
            $usuario = mysqli_fetch_assoc($entrarPerfil);

            if (password_verify($password, $usuario['password'])) {
                session_start();
                $_SESSION['numero'] = $usuario['numero'];
                $_SESSION["email"] = $usuario["correo"];
                $_SESSION["id"] = $usuario["id"];
                $_SESSION["nombre"] = $usuario["nombre"];
                $_SESSION['area'] = $usuario["area"];
                header('location: index.php');
                exit();
            }
        } else {
            header('location: login.php?error=1');
            exit();
        }
    }
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema tickets / login</title>
    <link rel="stylesheet" href="./about/css/app.css">
</head>

<body>

</body>

</html>

<header class="header">
    <h1>Sistema de Tickets</h1>
</header>

<main>

    <?php if (isset($_GET['exito']) && $_GET['exito'] == "1") : ?>
        <p class="exito">Usuario creado con exito</p>
    <?php endif ?>

    
    <?php if (isset($_GET['exito']) && $_GET['exito'] == "2") : ?>
        <p class="exito">Sesion cerrada con exito</p>
    <?php endif ?>

    <?php if (isset($_GET['error']) && $_GET['error'] == "1") : ?>
        <p class="error">La contraseña y correo no coinciden</p>
    <?php endif ?>

    <?php foreach ($errores as $error) : ?>
        <p class="error"><?php echo $error ?></p>
    <?php endforeach ?>

    <form action="" method="POST" class="form-login">
        <section>
            <label for="">Correo electronico:</label>
            <input type="email" name="email" placeholder="Ingresa tu email">

            <label for="">Contraseña:</label>
            <input type="password" name="password" placeholder="Ingresa tu contraseña">

            <div>
                <input type="submit" value="Enviar">
            </div>
        </section>

        <a class="crear-cuenta" href="registrar.php">Crear cuenta</a>
    </form>

</main>