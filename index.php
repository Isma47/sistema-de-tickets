<?php
session_start();
if (!isset($_SESSION["email"]) && !isset($_SESSION["id"]) && !isset($_SESSION['numero']) && !isset($_SESSION['nombre']) && !isset($_SESSION['area'])) {
    header("Location: login.php");
    exit();
}

require './includes/db/db.php';
$db = conectarDB();

$user_id = $_SESSION['id'];
// Consulta para obtener información específica del usuario actual utilizando su ID
$queryUsuario = "SELECT * FROM usuario WHERE id = ?";
$stmUsuario = mysqli_prepare($db, $queryUsuario);
mysqli_stmt_bind_param($stmUsuario, "i", $user_id);
mysqli_stmt_execute($stmUsuario);
$resultadoUsuario = mysqli_stmt_get_result($stmUsuario);

if ($resultadoUsuario && mysqli_num_rows($resultadoUsuario) > 0) {
    $usuario = mysqli_fetch_assoc($resultadoUsuario);
} else {
    echo 'No se encontró el usuario';
}

$errores = [];

$titulo = '';
$fecha = '';
$hora = '';
$area = '';
$descripcion = '';
$estado = '';
$foto = '';


if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $nombre = $_SESSION['nombre'];
    $email = $_SESSION['email'];
    $titulo = $_POST['titulo'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $area = $_POST['area'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $foto = $_POST['foto'];

    if (!$titulo) {
        $errores[] = "Debes de agregar un titulo";
    }

    if (!$fecha) {
        $errores[] = "Debes de agrega una fecha";
    }

    if (!$hora) {
        $errores[] = "Debes de agregar una hora";
    }

    if (!$area && $area == "0") {
        $errores[] = "Debes de agregar un area";
    }

    if (!$descripcion) {
        $errores[] = "Debes de agregar una descripcion";
    }


    if (empty($errores)) {
        $query = "INSERT INTO tickets(nombre, correo, titulo, fecha, hora, area, descripcion, estado, foto, usuario_id) VALUES (?, ? ,?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "sssssssssi", $nombre, $email, $titulo, $fecha, $hora, $area, $descripcion, $estado, $foto, $user_id);

        mysqli_stmt_execute($stmt);

        header('Location: index.php?exito=1');
        exit();
    }
}


$idSesion = $_SESSION['id'];
$idSesion = filter_var($idSesion, FILTER_VALIDATE_INT);

$query = "SELECT * FROM tickets WHERE usuario_id = $idSesion ORDER BY id DESC LIMIT 3";
$mostrarTicket = mysqli_query($db, $query);


//session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de tickets</title>
    <link rel="stylesheet" href="about/css/app.css">
</head>

<body>
    <nav class="menu-perfil">
        <!-- hsiotial -->
        <a href="">Historial de repostes</a>
        <a href="cerrarsesion.php">Cerrar sesion</a>
    </nav>
    <header class="header">

        <img class="header-hamburguesa icon-header" src="src/svg/menu.svg" alt="menu hamburguesa">

        <h1>SISTEMA DE TICKETS</h1>

        <!-- Usuario, cerrar sesion y modo oscuro -->
        <nav>
            <img class="icon-header icon-header__perfil" src="src/svg/usuarioperfil.svg" alt="">
            <img class="icon-header" src="src/svg/noche.svg" alt="">
        </nav>
    </header>

    <!-- Incio dle fromulario -->
    <main class="main-form">
        <form action="" class="formulario" method="POST">
            <?php foreach ($errores as $error) : ?>
                <p class="error"><?php echo $error ?></p>
            <?php endforeach ?>

            <div class="form-fecha">
                <label for="">Titulo problema</label>
                <input type="text" name="titulo" placeholder="Titulo del problema">

                <label for="">Ingrese la fecha del inicio del problema</label>
                <input type="date" name="fecha">

                <label for="">Ingrese la hora del inicio del problema</label>
                <input type="time" name="hora">
            </div>

            <div>
                <label for="">Ingrese su departamento</label>
                <select name="area" id="">
                    <option value="0" selected>-- Su área --</option>
                    <option value="">Gerencia</option>
                    <option value="">RH</option>
                    <option value="">Ventas</option>
                    <option value="">Logística</option>
                    <option value="">Desarrollo</option>
                    <option value="">Finanzas</option>
                    <option value="">MK</option>
                    <option value="">Compras</option>
                </select>
            </div>

            <div class="form-problema">
                <label for="">Describe el problema</label>
                <textarea name="descripcion" id="" cols="30" rows="10"></textarea>

                <input type="hidden" name="estado" value="nuevo">
            </div>


            <div class="form-fotos">
                <label for="">Ingrese fotos de problema <span>*no obligatorio*</span>:</label>
                <input type="file" name="foto">
            </div>


            <input type="submit" value="Enviar">

        </form>

        <!-- Historial de reportes y respuestas -->
        <div class="main-historial">
            <!-- Respuestas -->
            
            <?php while($row = mysqli_fetch_assoc($mostrarTicket)) : ?>
            <div class="historial">
                <h2>Mis ticket:</h2>
                <div>
                    <p>Caso: <?php echo $row['id'] ?></p>
                    <p>Fecha:<?php echo $row['fecha'] ?></p>
                    <p>Respuesta de: dev</p>
                </div>

                <div>
                    <p><?php echo $row['descripcion']?></p>
                </div>
            </div>
            
        <?php endwhile ?>
    </main>

    <script src="/src/js/menu.js"></script>
</body>

</html>