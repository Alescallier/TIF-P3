// Base de datos de productos
const productos = [
    // Abrigos
    { id: 1, nombre: "Abrigo de Invierno", categoria: "abrigos", precio: 1000, emoji: "🧥" },
    { id: 2, nombre: "Campera de Cuero", categoria: "abrigos", precio: 2000, emoji: "🧥" },
    { id: 3, nombre: "Abrigo Elegante", categoria: "abrigos", precio: 3000, emoji: "🧥" },
    { id: 4, nombre: "Impermeable", categoria: "abrigos", precio: 4000, emoji: "🧥" },
    
    // Remeras
    { id: 5, nombre: "Remera Básica Blanca", categoria: "remeras", precio: 5000, emoji: "👕" },
    { id: 6, nombre: "Remera Deportiva", categoria: "remeras", precio: 6000, emoji: "👕" },
    { id: 7, nombre: "Remera Estampada", categoria: "remeras", precio: 7000, emoji: "👕" },
    { id: 8, nombre: "Remera de Algodón", categoria: "remeras", precio: 8000, emoji: "👕" },
    { id: 9, nombre: "Remera Oversize", categoria: "remeras", precio: 9000, emoji: "👕" },
    
    // Pantalones
    { id: 10, nombre: "Jean Clásico", categoria: "pantalones", precio: 10000, emoji: "👖" },
    { id: 11, nombre: "Pantalón de Vestir", categoria: "pantalones", precio: 11000, emoji: "👖" },
    { id: 12, nombre: "Pantalón Cargo", categoria: "pantalones", precio: 12000, emoji: "👖" },
    { id: 13, nombre: "Jean Skinny", categoria: "pantalones", precio: 13000, emoji: "👖" },
    { id: 14, nombre: "Pantalón Deportivo", categoria: "pantalones", precio: 14000, emoji: "👖" }
];

let carrito = [];

// Cargar carrito desde localStorage
function cargarCarrito() {
    try {
        const carritoGuardado = localStorage.getItem('carritoTienda');
        if (carritoGuardado) {
            carrito = JSON.parse(carritoGuardado);
        }
    } catch (error) {
        console.error('Error al cargar carrito:', error);
        carrito = [];
    }
    actualizarContadorCarrito();
}

// Guardar carrito en localStorage
function guardarCarrito() {
    try {
        localStorage.setItem('carritoTienda', JSON.stringify(carrito));
    } catch (error) {
        console.error('Error al guardar carrito:', error);
    }
}

// Renderizar productos
function renderizarProductos(filtro = 'todos') {
    const listaProductos = document.getElementById('listaProductos');
    listaProductos.innerHTML = '';
    
    const productosFiltrados = filtro === 'todos' 
        ? productos 
        : productos.filter(p => p.categoria === filtro);
    
    productosFiltrados.forEach(producto => {
        const productoCard = document.createElement('div');
        productoCard.className = 'producto-card';
        productoCard.innerHTML = `
            <div class="producto-imagen">${producto.emoji}</div>
            <div class="producto-info">
                <span class="producto-categoria">${producto.categoria}</span>
                <h3 class="producto-nombre">${producto.nombre}</h3>
                <p class="producto-precio">$${producto.precio.toLocaleString('es-AR')}</p>
                <button class="btn-agregar" data-id="${producto.id}">
                    Agregar al Carrito
                </button>
            </div>
        `;
        listaProductos.appendChild(productoCard);
    });
    
    // Agregar event listeners a los botones
    document.querySelectorAll('.btn-agregar').forEach(btn => {
        btn.addEventListener('click', agregarAlCarrito);
    });
}

// Agregar producto al carrito
function agregarAlCarrito(e) {
    const productId = parseInt(e.target.dataset.id);
    const producto = productos.find(p => p.id === productId);
    
    const itemExistente = carrito.find(item => item.id === productId);
    
    if (itemExistente) {
        itemExistente.cantidad++;
    } else {
        carrito.push({
            id: producto.id,
            nombre: producto.nombre,
            precio: producto.precio,
            categoria: producto.categoria,
            emoji: producto.emoji,
            cantidad: 1
        });
    }
    
    guardarCarrito();
    actualizarContadorCarrito();
    
    // Feedback visual
    e.target.textContent = '✓ Agregado';
    e.target.style.backgroundColor = '#27ae60';
    setTimeout(() => {
        e.target.textContent = 'Agregar al Carrito';
        e.target.style.backgroundColor = '';
    }, 1000);
}

// Actualizar contador del carrito
function actualizarContadorCarrito() {
    const contador = document.getElementById('contadorCarrito');
    const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
    contador.textContent = totalItems;
}

// Configurar filtros
function configurarFiltros() {
    const botonesFiltro = document.querySelectorAll('.btn-filtro');
    
    botonesFiltro.forEach(btn => {
        btn.addEventListener('click', () => {
            botonesFiltro.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const filtro = btn.dataset.filtro;
            renderizarProductos(filtro);
        });
    });
}

// Ir al carrito
function irAlCarrito() {
    window.location.href = 'carrito.html';
}

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
    cargarCarrito();
    renderizarProductos();
    configurarFiltros();
    
    const btnCarrito = document.getElementById('btnCarrito');
    if (btnCarrito) {
        btnCarrito.addEventListener('click', irAlCarrito);
    }
});

// ===============================
// Inicializar el carrusel (Slick)
// ===============================
document.addEventListener('DOMContentLoaded', function () {
  // Verifica que jQuery y Slick estén cargados
  if (window.jQuery && typeof jQuery.fn.slick === 'function') {
    $('.slider-destacados').slick({
      slidesToShow: 2,         // Muestra 2 productos a la vez
      slidesToScroll: 1,
      dots: true,              // Muestra los puntitos de navegación
      arrows: true,            // Muestra las flechas
      infinite: true,
      autoplay: true,          // Avanza automáticamente
      autoplaySpeed: 2000,     // Cada 2 segundos
      responsive: [
        { breakpoint: 900,  settings: { slidesToShow: 1 } },
        { breakpoint: 600,  settings: { slidesToShow: 1 } }
      ]
    });
  } else {
    console.warn("Slick no se cargó correctamente.");
  }
});
