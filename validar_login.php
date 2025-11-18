<?php
session_start();

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

if ($usuario === 'admin' && $password === 'admin') {
    $_SESSION['admin'] = true;
    header('Location: admin_productos.php');
    exit;
} else {
    header('Location: login.php?error=1');
    exit;
}
