<?php

session_start();
if (!isset($_SESSION["email"]) && !isset($_SESSION["id"]) && !isset($_SESSION['numero']) && !isset($_SESSION['nombre']) && !isset($_SESSION['area'])) {
    header("Location: login.php");
    exit();
}

/*echo '<pre>';
var_dump($_SESSION);
echo '</pre>';*/

require '../includes/db/db.php';
$db = conectarDB();

//tickets recientes
$queryTicketNuevo = "SELECT * FROM tickets WHERE estado = 'nuevo' ORDER BY id DESC LIMIT 5";
$TicketNuevo = mysqli_query($db, $queryTicketNuevo);


//tickets pendientes
$queryTicketPendiente = "SELECT * FROM tickets WHERE estado = 'Pendiente' ORDER BY id DESC LIMIT 5";
$TicketPendiente = mysqli_query($db, $queryTicketPendiente);

$queryTicketCerrado = "SELECT * FROM tickets WHERE estado = 'Cerrado' ORDER BY id DESC LIMIT 5";
$TicketCerrado = mysqli_query($db, $queryTicketCerrado);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../about/css/app.css">
</head>

<body>
    <nav class="menu-perfil">
        <!-- hsiotial -->
        <a href="../admin/index.php">General</a>
        <a href="../admin/estado/abiertos.php">Tickets</a>
        <a href="../admin/estado/cerrado.php">Tickets cerrados</a>
        <a href="../admin/estado/pendientes.php">Tickets pendientes</a>
        <a href="../cerrarsesion.php">Cerrar sesion</a>
    </nav>
    <header class="header">

        <img class="header-hamburguesa icon-header" src="/src/svg/menu.svg" alt="menu hamburguesa">

        <h1>SISTEMA DE TICKETS</h1>

        <!-- Usuario, cerrar sesion y modo oscuro -->
        <nav>
            <img class="icon-header icon-header__perfil" src="/src/svg/usuarioperfil.svg" alt="">
            <img class="icon-header" src="/src/svg/noche.svg" alt="">
        </nav>
    </header>

    <main class="contenedor main-generales">
        <h2>Reporte general</h2>

        <!-- Gneral -->
        <div class="reporte-general">
            <div>
                <h3>Tickets</h3>
                <div class="reporte">
                    <?php while($row = mysqli_fetch_assoc($TicketNuevo)):?>
                        <a><?php echo $row['id']?> - <?php echo $row['descripcion']?> <?php echo $row['fecha']?></a>
                    <?php endwhile?>
                </div>

                <a href="./estado/abiertos.php" class="enviar-a">Ir</a>
            </div>
            <div>
                <h3>Tickets cerrados</h3>
                <div class="reporte">
                <?php while($row = mysqli_fetch_assoc($TicketPendiente)):?>
                        <a><?php echo $row['id']?> - <?php echo $row['descripcion']?> <?php echo $row['fecha']?></a>
                    <?php endwhile?>
                </div>

                <a href="./estado/cerrado.php" class="enviar-a">Ir</a>
            </div>
            <div>
                <h3>Tickets pendientes</h3>
                <div class="reporte">
                <div class="reporte">
                <?php while($row = mysqli_fetch_assoc($TicketCerrado)):?>
                        <a><?php echo $row['id']?> - <?php echo $row['descripcion']?> <?php echo $row['fecha']?></a>
                    <?php endwhile?>
                </div>
                </div>

                <a href="./estado/pendientes.php" class="enviar-a">Ir</a>
            </div>
    </main>

    <script src="/src/js/menu.js"></script>
</body>

</html>