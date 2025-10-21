# TIF-P3

---

##  Informe de Implementación — Carrusel de Productos Destacados (Slick Carousel)

###  Objetivo

Incorporar un **carrusel dinámico de productos destacados** en la página principal (`index.html`) utilizando el plugin **Slick Carousel (jQuery)**, logrando un desplazamiento automático de productos cada 2 segundos y manteniendo la funcionalidad de agregar productos al carrito.

---

###  Cambios Realizados

#### 1. **Instalación e integración del plugin Slick**

Se añadieron las dependencias del carrusel a `index.html` mediante **CDN**, asegurando la carga correcta de los estilos y scripts antes de `main.js`:

<!-- Slick CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">

<!-- jQuery y Slick JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<!-- Script principal -->
<script src="main.js"></script>
```

>  **Importante:** el orden de carga garantiza que Slick y jQuery estén disponibles antes de ejecutar el código personalizado.

---

#### 2. **Estructura HTML del carrusel**

Dentro del archivo `index.html`, se agregó una nueva sección llamada **Productos Destacados** con cuatro elementos representando prendas destacadas.

<section class="productos-destacados">
  <h2>Productos destacados</h2>
  <div class="slider-destacados">
    <article class="card">
      <div class="emoji">👕</div>
      <h3>Remera Oversize</h3>
      <p class="precio">$9.000</p>
      <button class="btn-agregar" data-id="9">Agregar al Carrito</button>
    </article>
    <article class="card">
      <div class="emoji">👖</div>
      <h3>Jean Clásico</h3>
      <p class="precio">$10.000</p>
      <button class="btn-agregar" data-id="10">Agregar al Carrito</button>
    </article>
    <article class="card">
      <div class="emoji">🧥</div>
      <h3>Campera de Cuero</h3>
      <p class="precio">$2.000</p>
      <button class="btn-agregar" data-id="2">Agregar al Carrito</button>
    </article>
    <article class="card">
      <div class="emoji">👖</div>
      <h3>Pantalón Cargo</h3>
      <p class="precio">$12.000</p>
      <button class="btn-agregar" data-id="12">Agregar al Carrito</button>
    </article>
  </div>
</section>
```

* Cada producto tiene un emoji representativo, nombre, precio y un botón que **usa `data-id`**, enlazado con la base de datos de productos definida en `main.js`.
* Se eliminaron los antiguos `data-sku`, que no coincidían con los IDs del sistema actual de carrito.

---

#### 3. **Inicialización del carrusel en `main.js`**

Se agregó un bloque de código al final de `main.js` para inicializar Slick una vez que el DOM y las librerías estén completamente cargadas.

```js
// ===============================
// Inicializar el carrusel (Slick)
// ===============================
document.addEventListener('DOMContentLoaded', function () {
  if (window.jQuery && typeof jQuery.fn.slick === 'function') {
    $('.slider-destacados').slick({
      slidesToShow: 2,
      slidesToScroll: 1,
      dots: true,
      arrows: true,
      infinite: true,
      autoplay: true,
      autoplaySpeed: 2000, // Cambio cada 2 segundos
      responsive: [
        { breakpoint: 900,  settings: { slidesToShow: 1 } },
        { breakpoint: 600,  settings: { slidesToShow: 1 } }
      ]
    });
  } else {
    console.warn("Slick no se cargó correctamente.");
  }
});
```

> 🔹 Este código detecta si Slick está disponible y luego activa el carrusel con desplazamiento automático cada 2 segundos.
> 🔹 El carrusel se adapta a pantallas pequeñas, mostrando solo 1 producto a la vez.

---

#### 4. **Eliminación de código duplicado**

Durante la integración se detectaron dos sistemas de carrito activos:

* Uno antiguo (basado en la clave `carrito`)
* Uno actual (basado en `carritoTienda`)

El sistema viejo fue eliminado completamente (incluyendo la función `inicializarCarrito()`) para evitar conflictos y asegurar que todo el flujo use una única clave (`carritoTienda`) en `localStorage`.

---

###  Resultado Final

* El **carrusel de productos destacados** se desplaza automáticamente cada 2 segundos.
* Los productos del carrusel pueden **agregarse correctamente al carrito**.
* El **contador de carrito** se actualiza en tiempo real en el header.
* Todo el sistema usa una única fuente de datos (`carritoTienda`) para sincronizar `index.html` y `carrito.html`.

---

###  Consideraciones Técnicas

* Se garantiza compatibilidad con pantallas móviles mediante `responsive breakpoints`.
* La animación de Slick se basa en jQuery, por lo que su carga es obligatoria antes del script.
* Si en el futuro se amplía el catálogo, basta con agregar nuevos `<article>` dentro de `.slider-destacados` con su respectivo `data-id`.

---

###  Archivos Modificados

| Archivo      | Descripción del cambio                                                             |
| ------------ | ---------------------------------------------------------------------------------- |
| `index.html` | Se agregaron dependencias de Slick y la nueva sección del carrusel.                |
| `main.js`    | Se eliminó código duplicado y se añadió la inicialización del carrusel.            |
| `main.css`   | Ya contenía los estilos base del carrusel (clases `.slider-destacados` y `.card`). |
| `carrito.js` | Se unificó la clave `carritoTienda` para compatibilidad con `main.js`.             |

---
