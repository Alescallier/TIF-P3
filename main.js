console.log("VERSIÓN JS CARGADA >>> V1");

let productos = [];
let carrito = [];

// === Cargar productos desde la base ===
async function cargarProductos(categoria = 'todos') {
    try {
        const response = await fetch(`obtener_productos.php?categoria=${categoria}`);
        productos = await response.json();
        renderizarProductos();
    } catch (error) {
        console.error("Error al cargar productos:", error);
    }
}

// === Renderizar productos ===
function renderizarProductos() {
    const listaProductos = document.getElementById('listaProductos');
    listaProductos.innerHTML = '';

    productos.forEach(producto => {
        const card = document.createElement('div');
        card.className = 'producto-card';
        card.innerHTML = `
            <div class="producto-imagen">${producto.emoji}</div>
            <div class="producto-info">
                <span class="producto-categoria">${producto.categoria}</span>
                <h3 class="producto-nombre">${producto.nombre}</h3>
                <p class="producto-precio">$${producto.precio}</p>
                <button class="btn-agregar" data-id="${producto.id}">Agregar al Carrito</button>
            </div>`;
        listaProductos.appendChild(card);
    });

    document.querySelectorAll('.btn-agregar').forEach(btn => {
    btn.addEventListener('click', agregarAlCarrito);
});
}

// === Carrito ===
function cargarCarrito() {
    const guardado = localStorage.getItem('carritoTienda');
    carrito = guardado ? JSON.parse(guardado) : [];
    actualizarContadorCarrito();
}

function guardarCarrito() {
    localStorage.setItem('carritoTienda', JSON.stringify(carrito));
}

function agregarAlCarrito(e) {
    const id = parseInt(e.target.dataset.id);

    // Buscar el producto dentro del array cargado desde la BD
    const prod = productos.find(p => p.id == id);

    // Si el producto no existe aún, evitamos errores
    if (!prod) {
        console.warn("⛔ Producto no encontrado aún. Espera a que cargue o revisa el ID del botón.");
        console.log("ID buscado:", id);
        console.log("Productos cargados actualmente:", productos);
        return;
    }

    // Buscar si el producto ya está en el carrito
    const item = carrito.find(i => i.id == id);

    if (item) {
        // Incrementar cantidad
        item.cantidad++;
    } else {
        // Agregar producto completo
        carrito.push({
            id: prod.id,
            nombre: prod.nombre,
            precio: Number(prod.precio),
            categoria: prod.categoria,
            emoji: prod.emoji,
            cantidad: 1
        });
    }

    // Guardar carrito actualizado
    guardarCarrito();
    actualizarContadorCarrito();

    // Animación visual del botón
    e.target.textContent = '✓ Agregado';
    e.target.style.backgroundColor = '#27ae60';
    setTimeout(() => {
        e.target.textContent = 'Agregar al Carrito';
        e.target.style.backgroundColor = '';
    }, 1000);

    console.log("🛒 Carrito actualizado:", carrito);
}




function actualizarContadorCarrito() {
    const total = carrito.reduce((sum, i) => sum + i.cantidad, 0);
    document.getElementById('contadorCarrito').textContent = total;
}

// === Filtros ===
function configurarFiltros() {
    const botones = document.querySelectorAll('.btn-filtro');
    botones.forEach(btn => {
        btn.addEventListener('click', () => {
            botones.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            cargarProductos(btn.dataset.filtro);
        });
    });
}

// === Navegación ===
function irAlCarrito() {
    window.location.href = 'carrito.php';
}

// === Inicialización ===
document.addEventListener('DOMContentLoaded', () => {
    cargarCarrito();
    cargarProductos();
    configurarFiltros();
    document.getElementById('btnCarrito').addEventListener('click', irAlCarrito);

    if (window.jQuery && typeof jQuery.fn.slick === 'function') {
        $('.slider-destacados').slick({
            slidesToShow: 2,
            dots: true,
            arrows: true,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 2000,
            responsive: [
                { breakpoint: 900, settings: { slidesToShow: 1 } },
                { breakpoint: 600, settings: { slidesToShow: 1 } }
            ]
        });
    }
});
