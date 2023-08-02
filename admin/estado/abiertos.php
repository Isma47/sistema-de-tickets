<?php
session_start();
require '../../includes/db/db.php';
$db = conectarDB();

//tickets recientes
$queryTicketNuevo = "SELECT * FROM tickets WHERE estado = 'nuevo'";
$TicketNuevo = mysqli_query($db, $queryTicketNuevo);


echo '<pre>';
var_dump($_SESSION);
echo '</pre>';

echo '<pre>';
var_dump($_POST);
echo '</pre>';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validar y obtener los datos necesarios para actualizar el registro
    $idTicket = $_POST['id'];
    $estado = $_POST['estado'];

    // Preparar la consulta SQL para actualizar el estado del ticket
    $query = "UPDATE tickets SET estado = ? WHERE id = ?";
    $stmt = mysqli_prepare($db, $query);

    // Asignar los valores a los marcadores de posición (?) utilizando mysqli_stmt_bind_param
    mysqli_stmt_bind_param($stmt, "si", $estado, $idTicket);

    // Ejecutar la consulta preparada
    if (mysqli_stmt_execute($stmt)) {
        header('location: ../index.php');
    }
    // Cerrar la sentencia
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../about/css/app.css">
</head>

<body>
    <nav class="menu-perfil">
        <!-- hsiotial -->
        <a href="/admin/index.php">General</a>
        <a href="/admin/estado/abiertos.php">Tickets</a>
        <a href="/admin/estado/cerrado.php">Tickets cerrados</a>
        <a href="/admin/estado/pendientes.php">Tickets pendientes</a>
        <a href="../../cerrarsesion.php">Cerrar sesion</a>
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

    <main>
        <h2 class="title-ticket">NUEVOS TICKETS</h2>

        <div class="contenedor grid-ticket">

            <?php while ($row = mysqli_fetch_assoc($TicketNuevo)) : ?>
                <div class="tickets">
                    <div class="ticket">
                        <p><span>id: </span><?php echo $row['id'] ?></p>
                        <p><span>Nombre: </span> </p>
                        <p><span>Fecha: </span><?php echo $row['fecha'] ?></p>
                        <p><span>Hora: </span> <?php echo $row['hora'] ?></p>
                        <p><span>Area: </span><?php echo $row['area'] ?></p>
                        <div class="descripcion">
                            <p><span>Descripcion: </span><?php echo $row['descripcion'] ?></p>
                        </div>
                        <p><span>Foto: </span> <?php echo $row['foto'] ?></p>

                        <form method="POST" >
                            <input type="hidden" name="estado" value="Pendiente">
                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">

                            <input type="submit" value="Marcar">
                        </form>
                
                    </div>
                </div>
            <?php endwhile ?>
        </div>
    </main>


    <script src="/src/js/menu.js"></script>
</body>

</html>