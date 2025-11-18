<?php
include 'conexion.php';

$categoria = $_GET['categoria'] ?? 'todos';

if ($categoria === 'todos') {
    $sql = "SELECT p.id, p.nombre, c.nombre AS categoria, p.precio, p.emoji
            FROM productos p
            JOIN categorias c ON p.categoria_id = c.id";
} else {
    $sql = "SELECT p.id, p.nombre, c.nombre AS categoria, p.precio, p.emoji
            FROM productos p
            JOIN categorias c ON p.categoria_id = c.id
            WHERE c.nombre = '" . mysqli_real_escape_string($con, $categoria) . "'";
}

$result = mysqli_query($con, $sql);
$productos = [];

while ($row = mysqli_fetch_assoc($result)) {
    $productos[] = $row;
}

echo json_encode($productos, JSON_UNESCAPED_UNICODE);
?>
