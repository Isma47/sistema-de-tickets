<?php 

session_start();

session_destroy();

header('location: login.php?exito=2');