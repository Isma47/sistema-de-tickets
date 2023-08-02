<?php

function conectarDB(): mysqli {

    $db = mysqli_connect('localhost', 'root', 'root', 'ticket');

    if (!$db) {
        echo 'no conecto';
    }elseif($db === false){
        die('Error al conectar a la base de datos: ' . mysqli_connect_error());
    }
    return $db;
}
