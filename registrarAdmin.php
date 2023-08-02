<?php

require 'includes/db/db.php';
$db = conectarDB();

/*
echo '<pre>';
var_dump($_POST);
echo '</pre>';*/

/*echo '<pre>';
    var_dump($_SERVER);
    echo '</pre>';
*/

$errores = [];

$nombre = '';
$correo = '';
$password = '';
$numero = '';
$area = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre =  $_POST["nombre"];
    $correo =  $_POST["email"];
    $password =  $_POST["password"];
    $passwordHaseheada = password_hash($password, PASSWORD_DEFAULT);
    $numero =  $_POST["numero"];
    $area =  $_POST["area"];

    if ($area == "0") {
        $errores[] = "Ingrese un area";
    }

    if (!$nombre) {
        $errores[] = "Debes de agregar un nombre";
    }

    if (!$correo) {
        $errores[] = "Debes de agregar un correo";
    }elseif(!filter_var($correo, FILTER_VALIDATE_EMAIL)) {

    }
    if (!$password) {
        $errores[] = "Debes de agregar una contraseña";
    }

    if ($password !== $_POST['passwordConfirmar']) {
        $errores[] = "Las contraseñas no son iguales";
    }

    if (!$numero) {
        $errores[] = "Ingrese el numero proporcionado por la empresa";
    }



    if (empty($errores)) {

        if(password_verify($password, $passwordHaseheada)){
        // Preparar la consulta preparada
        $query = "INSERT INTO admin(nombre, correo, password, numero, area) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $query);
    
        // Vincular los valores de los parámetros a la sentencia preparada
        mysqli_stmt_bind_param($stmt, "sssss", $nombre, $correo, $passwordHaseheada, $numero, $area);
    
        // Ejecutar la consulta preparada
        mysqli_stmt_execute($stmt);
    
        // Obtener el conjunto de resultados (result set) si es posible (requiere mysqlnd)
        $result = mysqli_stmt_get_result($stmt);
    
        // Aquí puedes trabajar con los resultados si es necesario
        // Por ejemplo, obtener el ID autoincremental asignado a la nueva fila:
        $insertedId = mysqli_insert_id($db);
    
        // Redirigir al usuario a la página de inicio de sesión con un mensaje de éxito
        header('location: loginAdmin.php?exito=1');
        exit();
        }else{
            $errores[] = "No es la contraseña";
        }

    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickteks / Registrarse</title>
    <link rel="stylesheet" href="./about/css/app.css">
</head>

<body>
    <header class="header">
        <h1>Sistema de Tickets</h1>
    </header>

    <main>
        <?php foreach ($errores as $error) : ?>
            <p class="error"><?php echo $error ?></p>
        <?php endforeach ?>

        <form method="POST" class="form-login">


            <section>

                <label for="">Nombre:</label>
                <input type="text" name="nombre" placeholder="Ingrese su nombre y apellidos">

                <label for="">Correo electronico:</label>
                <input type="email" name="email" placeholder="Ingresa tu email">

                <label for="">Contraseña:</label>
                <input type="password" name="password" placeholder="Ingresa tu contraseña">

                <label for="">Confirme su contraseña:</label>
                <input type="password" name="passwordConfirmar" placeholder="Ingrese de nuevo su contraseña">

                <label for="">Numero telefonico:</label>
                <input type="number" name="numero" placeholder="Telefono de la empresa">

                <label for="">Área:</label>
                <select name="area" id="">
                    <option value="0" selected>--Selecciona un area--</option>
                    <option value="area1">Area 1</option>
                    <option value="area2">Area 2</option>
                    <option value="area3">Area 3</option>
                    <option value="area4">Area 4</option>
                </select>

                <div>
                    <input type="submit" value="Enviar">
                </div>
            </section>
        </form>

    </main>
</body>

</html>