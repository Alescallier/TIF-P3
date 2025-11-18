<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "tienda_ropa";

$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

// MUY IMPORTANTE: utf8mb4 para emojis
mysqli_set_charset($con, "utf8mb4");
?>
