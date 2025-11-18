<?php
session_start();

// Si ya está logueado, redirigir
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header('Location: admin_productos.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login Administrador</title>
<link rel="stylesheet" href="main.css">
<style>
    body {
        background-color: #0b0014;
        font-family: system-ui;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .login-box {
        background: #160028;
        padding: 30px;
        border-radius: 14px;
        width: 320px;
        box-shadow: 0px 15px 30px rgba(0,0,0,0.4);
        text-align: center;
    }
    input {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        border: none;
        margin-bottom: 12px;
        background: #12001f;
        color: #fff;
        font-size: 15px;
    }
    .btn {
        background: #7b2cff;
        padding: 10px;
        border-radius: 999px;
        width: 100%;
        border: none;
        cursor: pointer;
        color: #fff;
        font-weight: 600;
        margin-top: 8px;
    }
    .btn:hover {
        background: #9b5bff;
    }
    .error {
        background: rgba(255, 82, 82, 0.17);
        padding: 8px;
        border-radius: 8px;
        margin-bottom: 10px;
        color: #ff7675;
        font-size: 14px;
    }
</style>
</head>
<body>
    <form class="login-box" action="validar_login.php" method="POST">
        <h2>🔐 Panel Admin</h2>
        <p>Ingrese sus credenciales</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="error">Usuario o contraseña incorrectos</div>
        <?php endif; ?>

        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>

        <button class="btn" type="submit">Ingresar</button>
    </form>
</body>
</html>
