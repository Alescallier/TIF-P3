<?php include 'conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Ropa</title>

    <!-- Slick CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">

    <link rel="stylesheet" href="main.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>🛍️ Amictus</h1>
            <nav>
                <button id="btnCarrito" class="btn-carrito">
                    🛒 Carrito (<span id="contadorCarrito">0</span>)
                </button>
            </nav>
        </div>
    </header>

<section class="productos-destacados">
  <h2>Productos destacados</h2>

  <div class="slider-destacados">
    <?php
      // Obtener 4 productos destacados (puedes modificar el criterio)
      $destacados = mysqli_query($con, "SELECT * FROM productos ORDER BY precio DESC LIMIT 4");

      while ($p = mysqli_fetch_assoc($destacados)) :
    ?>
      <article class="card">
          <div class="emoji"><?= $p['emoji'] ?></div>
          <h3><?= $p['nombre'] ?></h3>
          <p class="precio">$<?= number_format($p['precio'], 0, ',', '.'); ?></p>
          <button class="btn-agregar" data-id="<?= $p['id']; ?>">Agregar al Carrito</button>
      </article>
    <?php endwhile; ?>
  </div>
</section>


<main class="container">
    <section class="filtros">
        <h2>Filtrar Productos</h2>
        <div class="filtros-botones">
            <button class="btn-filtro active" data-filtro="todos">Todos</button>
            <button class="btn-filtro" data-filtro="abrigos">Abrigos</button>
            <button class="btn-filtro" data-filtro="remeras">Remeras</button>
            <button class="btn-filtro" data-filtro="pantalones">Pantalones</button>
        </div>
    </section>

    <section class="productos" id="listaProductos">
        <!-- Productos dinámicos -->
    </section>
</main>

<footer>
    <p>&copy; 2025 Mi Tienda de Ropa. Todos los derechos reservados.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="main.js"></script>
</body>
</html>
