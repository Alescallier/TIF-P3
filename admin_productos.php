<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<?php
// admin_productos.php
include 'conexion.php';

$mensaje = "";

// --- Manejo de formularios ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = (float)($_POST['precio'] ?? 0);
    $categoria_id = (int)($_POST['categoria_id'] ?? 0);
    $emoji = trim($_POST['emoji'] ?? '');

    if ($accion === 'crear') {
        if ($nombre && $precio > 0 && $categoria_id > 0 && $emoji) {
            $stmt = mysqli_prepare($con, "INSERT INTO productos (nombre, categoria_id, precio, emoji) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sids", $nombre, $categoria_id, $precio, $emoji);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $mensaje = "Producto creado correctamente.";
        } else {
            $mensaje = "Completa todos los campos para crear un producto.";
        }
    }

    if ($accion === 'editar') {
        if ($id > 0 && $nombre && $precio > 0 && $categoria_id > 0 && $emoji) {
            $stmt = mysqli_prepare($con, "UPDATE productos SET nombre = ?, categoria_id = ?, precio = ?, emoji = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sidsi", $nombre, $categoria_id, $precio, $emoji, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $mensaje = "Producto actualizado correctamente.";
        } else {
            $mensaje = "Completa todos los campos para editar el producto.";
        }
    }

    if ($accion === 'eliminar') {
        if ($id > 0) {
            $stmt = mysqli_prepare($con, "DELETE FROM productos WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $mensaje = "Producto eliminado.";
        } else {
            $mensaje = "ID de producto inválido para eliminar.";
        }
    }
}

// --- Obtener categorías ---
$categorias = [];
$resCats = mysqli_query($con, "SELECT id, nombre FROM categorias ORDER BY nombre ASC");
while ($row = mysqli_fetch_assoc($resCats)) {
    $categorias[] = $row;
}

// --- Obtener productos ---
$productos = mysqli_query($con, "
    SELECT p.id, p.nombre, p.precio, p.emoji, c.nombre AS categoria, c.id AS categoria_id
    FROM productos p
    JOIN categorias c ON p.categoria_id = c.id
    ORDER BY p.id DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Productos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Podés reutilizar tu main.css -->
    <link rel="stylesheet" href="main.css">

    <style>
        body {
            background-color: #0b0014;
            color: #fff;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .admin-container {
            max-width: 1100px;
            margin: 40px auto;
            background: #160028;
            border-radius: 16px;
            padding: 24px 32px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .admin-header h1 {
            font-size: 26px;
            margin: 0;
        }
        .btn {
            border: none;
            border-radius: 999px;
            padding: 10px 18px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
        }
        .btn-primary {
            background: #7b2cff;
            color: #fff;
        }
        .btn-primary:hover {
            background: #9b5bff;
        }
        .btn-danger {
            background: #e74c3c;
            color: #fff;
        }
        .btn-outline {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.15);
            color: #fff;
        }
        .tabla-productos {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .tabla-productos thead {
            background: rgba(255,255,255,0.05);
        }
        .tabla-productos th,
        .tabla-productos td {
            padding: 10px 8px;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            text-align: left;
        }
        .tabla-productos th {
            font-weight: 600;
            color: #ddd;
        }
        .emoji-box {
            font-size: 24px;
        }
        .precio-col {
            font-variant-numeric: tabular-nums;
        }
        .mensaje {
            margin-bottom: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            background: rgba(0, 184, 148, 0.12);
            color: #1dd1a1;
        }
        .mensaje-error {
            background: rgba(255, 99, 71, 0.12);
            color: #ff6b6b;
        }

        /* Modales: reutilizamos estilo similar al de tu carrito */
        .modal-admin {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            align-items: center;
            justify-content: center;
        }
        .modal-admin-activo {
            display: flex;
        }
        .modal-contenido-admin {
            background: #1c0230;
            border-radius: 16px;
            padding: 20px 24px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            animation: fadeInUp 0.2s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }
        .modal-header h2 {
            margin: 0;
            font-size: 18px;
        }
        .modal-cerrar {
            cursor: pointer;
            font-size: 22px;
            line-height: 1;
        }
        .form-grupo {
            margin-bottom: 12px;
        }
        .form-grupo label {
            display: block;
            font-size: 13px;
            margin-bottom: 4px;
            color: #ccc;
        }
        .form-grupo input,
        .form-grupo select {
            width: 100%;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.2);
            background: #12011f;
            color: #fff;
            font-size: 14px;
            outline: none;
        }
        .form-grupo input:focus,
        .form-grupo select:focus {
            border-color: #7b2cff;
        }
        .form-acciones {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 12px;
        }
        .badge-cat {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
            text-transform: uppercase;
            letter-spacing: .04em;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>🛠️ Administración de productos</h1>
            <div>
                <a href="index.php" class="btn btn-outline" style="margin-right:8px;">← Volver a la tienda</a>
                <button id="btnAbrirCrear" class="btn btn-primary">+ Nuevo producto</button>
                <a href="logout.php" class="btn btn-danger" style="margin-left:8px;">Cerrar sesión</a>
            </div>
        </div>

        <?php if ($mensaje): ?>
            <div class="mensaje">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <table class="tabla-productos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Emoji</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th class="precio-col">Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($p = mysqli_fetch_assoc($productos)): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td class="emoji-box"><?= $p['emoji'] ?></td>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td><span class="badge-cat"><?= htmlspecialchars($p['categoria']) ?></span></td>
                    <td class="precio-col">$<?= number_format($p['precio'], 0, ',', '.'); ?></td>
                    <td>
                        <button
                            class="btn btn-outline btn-editar"
                            data-id="<?= $p['id'] ?>"
                            data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>"
                            data-precio="<?= $p['precio'] ?>"
                            data-categoria_id="<?= $p['categoria_id'] ?>"
                            data-emoji="<?= htmlspecialchars($p['emoji'], ENT_QUOTES) ?>"
                        >
                            Editar
                        </button>
                        <button
                            class="btn btn-danger btn-eliminar"
                            data-id="<?= $p['id'] ?>"
                            data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>"
                        >
                            Eliminar
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal CREAR -->
    <div class="modal-admin" id="modalCrear">
        <div class="modal-contenido-admin">
            <div class="modal-header">
                <h2>Nuevo producto</h2>
                <span class="modal-cerrar" data-close="modalCrear">&times;</span>
            </div>
            <form method="post">
                <input type="hidden" name="accion" value="crear">
                <div class="form-grupo">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-grupo">
                    <label>Precio</label>
                    <input type="number" name="precio" min="1" step="1" required>
                </div>
                <div class="form-grupo">
                    <label>Categoría</label>
                    <select name="categoria_id" required>
                        <option value="">Selecciona una categoría</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-grupo">
                    <label>Emoji (por ej: 🧥, 👕, 👖)</label>
                    <input type="text" name="emoji" maxlength="4" required>
                </div>
                <div class="form-acciones">
                    <button type="button" class="btn btn-outline" data-close="modalCrear">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal EDITAR -->
    <div class="modal-admin" id="modalEditar">
        <div class="modal-contenido-admin">
            <div class="modal-header">
                <h2>Editar producto</h2>
                <span class="modal-cerrar" data-close="modalEditar">&times;</span>
            </div>
            <form method="post" id="formEditar">
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id" id="edit_id">
                <div class="form-grupo">
                    <label>Nombre</label>
                    <input type="text" name="nombre" id="edit_nombre" required>
                </div>
                <div class="form-grupo">
                    <label>Precio</label>
                    <input type="number" name="precio" id="edit_precio" min="1" step="1" required>
                </div>
                <div class="form-grupo">
                    <label>Categoría</label>
                    <select name="categoria_id" id="edit_categoria_id" required>
                        <option value="">Selecciona una categoría</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-grupo">
                    <label>Emoji</label>
                    <input type="text" name="emoji" id="edit_emoji" maxlength="4" required>
                </div>
                <div class="form-acciones">
                    <button type="button" class="btn btn-outline" data-close="modalEditar">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Formulario oculto para ELIMINAR -->
    <form method="post" id="formEliminar" style="display:none;">
        <input type="hidden" name="accion" value="eliminar">
        <input type="hidden" name="id" id="del_id">
    </form>

    <script>
        // Abrir modal Crear
        const btnAbrirCrear = document.getElementById('btnAbrirCrear');
        const modalCrear = document.getElementById('modalCrear');
        const modalEditar = document.getElementById('modalEditar');

        if (btnAbrirCrear) {
            btnAbrirCrear.addEventListener('click', () => {
                modalCrear.classList.add('modal-admin-activo');
            });
        }

        // Cerrar modales
        document.querySelectorAll('[data-close]').forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-close');
                document.getElementById(targetId).classList.remove('modal-admin-activo');
            });
        });

        // Cerrar haciendo click afuera del contenido
        document.querySelectorAll('.modal-admin').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('modal-admin-activo');
                }
            });
        });

        // Botones EDITAR
        document.querySelectorAll('.btn-editar').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const nombre = btn.dataset.nombre;
                const precio = btn.dataset.precio;
                const categoria_id = btn.dataset.categoria_id;
                const emoji = btn.dataset.emoji;

                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nombre').value = nombre;
                document.getElementById('edit_precio').value = precio;
                document.getElementById('edit_categoria_id').value = categoria_id;
                document.getElementById('edit_emoji').value = emoji;

                modalEditar.classList.add('modal-admin-activo');
            });
        });

        // Botones ELIMINAR
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const nombre = btn.dataset.nombre;

                if (confirm(`¿Seguro que querés eliminar el producto: "${nombre}"?`)) {
                    document.getElementById('del_id').value = id;
                    document.getElementById('formEliminar').submit();
                }
            });
        });
    </script>
</body>
</html>
