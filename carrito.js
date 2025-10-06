// Carrito y constantes
let carrito = [];
const COSTO_ENVIO = 500;

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
    renderizarCarrito();
}

// Guardar carrito en localStorage
function guardarCarrito() {
    try {
        localStorage.setItem('carritoTienda', JSON.stringify(carrito));
    } catch (error) {
        console.error('Error al guardar carrito:', error);
    }
}

// Renderizar carrito
function renderizarCarrito() {
    const carritoVacio = document.getElementById('carritoVacio');
    const carritoContenido = document.getElementById('carritoContenido');
    const carritoItems = document.getElementById('carritoItems');
    
    if (!carrito || carrito.length === 0) {
        carritoVacio.style.display = 'block';
        carritoContenido.style.display = 'none';
        return;
    }
    
    carritoVacio.style.display = 'none';
    carritoContenido.style.display = 'grid';
    
    carritoItems.innerHTML = '';
    
    carrito.forEach(item => {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'carrito-item';
        itemDiv.innerHTML = `
            <div class="item-imagen">${item.emoji}</div>
            <div class="item-info">
                <h3 class="item-nombre">${item.nombre}</h3>
                <p class="item-precio">$${item.precio.toLocaleString('es-AR')}</p>
                <div class="item-controles">
                    <div class="item-cantidad">
                        <button class="btn-cantidad" data-id="${item.id}" data-accion="restar">-</button>
                        <span>${item.cantidad}</span>
                        <button class="btn-cantidad" data-id="${item.id}" data-accion="sumar">+</button>
                    </div>
                    <button class="btn-eliminar" data-id="${item.id}">Eliminar</button>
                </div>
            </div>
            <div class="item-total">
                <strong>$${(item.precio * item.cantidad).toLocaleString('es-AR')}</strong>
            </div>
        `;
        carritoItems.appendChild(itemDiv);
    });
    
    // Agregar event listeners
    document.querySelectorAll('.btn-cantidad').forEach(btn => {
        btn.addEventListener('click', cambiarCantidad);
    });
    
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', eliminarItem);
    });
    
    actualizarTotales();
}

// Cambiar cantidad
function cambiarCantidad(e) {
    const id = parseInt(e.target.dataset.id);
    const accion = e.target.dataset.accion;
    const item = carrito.find(i => i.id === id);
    
    if (accion === 'sumar') {
        item.cantidad++;
    } else if (accion === 'restar' && item.cantidad > 1) {
        item.cantidad--;
    }
    
    guardarCarrito();
    renderizarCarrito();
}

// Eliminar item
function eliminarItem(e) {
    const id = parseInt(e.target.dataset.id);
    carrito = carrito.filter(item => item.id !== id);
    guardarCarrito();
    renderizarCarrito();
}

// Actualizar totales
function actualizarTotales() {
    const subtotal = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    const total = subtotal + COSTO_ENVIO;
    
    document.getElementById('subtotal').textContent = `$${subtotal.toLocaleString('es-AR')}`;
    document.getElementById('envio').textContent = `$${COSTO_ENVIO.toLocaleString('es-AR')}`;
    document.getElementById('total').textContent = `$${total.toLocaleString('es-AR')}`;
}

// Volver a la tienda
function volverTienda() {
    window.location.href = 'index.html';
}

// Modal checkout
const modalCheckout = document.getElementById('modalCheckout');
const modalConfirmacion = document.getElementById('modalConfirmacion');
const btnFinalizarCompra = document.getElementById('btnFinalizarCompra');
const btnCancelar = document.getElementById('btnCancelar');
const cerrarModal = document.querySelector('.cerrar');
const formCheckout = document.getElementById('formCheckout');
const formaPagoSelect = document.getElementById('formaPago');

// Abrir modal checkout
if (btnFinalizarCompra) {
    btnFinalizarCompra.addEventListener('click', () => {
        modalCheckout.style.display = 'block';
    });
}

// Cerrar modal
if (btnCancelar) {
    btnCancelar.addEventListener('click', () => {
        modalCheckout.style.display = 'none';
    });
}

if (cerrarModal) {
    cerrarModal.addEventListener('click', () => {
        modalCheckout.style.display = 'none';
    });
}

// Cerrar modal al hacer click fuera
window.addEventListener('click', (e) => {
    if (e.target === modalCheckout) {
        modalCheckout.style.display = 'none';
    }
    if (e.target === modalConfirmacion) {
        modalConfirmacion.style.display = 'none';
        volverTienda();
    }
});

// Cambiar campos según forma de pago
if (formaPagoSelect) {
    formaPagoSelect.addEventListener('change', (e) => {
        const datosTarjeta = document.getElementById('datosTarjeta');
        const datosTransferencia = document.getElementById('datosTransferencia');
        const datosEfectivo = document.getElementById('datosEfectivo');
        
        // Ocultar todos
        datosTarjeta.style.display = 'none';
        datosTransferencia.style.display = 'none';
        datosEfectivo.style.display = 'none';
        
        // Limpiar requerimientos
        document.querySelectorAll('.datos-pago input').forEach(input => {
            input.removeAttribute('required');
        });
        
        // Mostrar el correspondiente
        if (e.target.value === 'tarjeta') {
            datosTarjeta.style.display = 'block';
            document.querySelectorAll('#datosTarjeta input').forEach(input => {
                input.setAttribute('required', 'required');
            });
        } else if (e.target.value === 'transferencia') {
            datosTransferencia.style.display = 'block';
            document.getElementById('comprobanteTransferencia').setAttribute('required', 'required');
        } else if (e.target.value === 'efectivo') {
            datosEfectivo.style.display = 'block';
        }
    });
}

// Formatear número de tarjeta
const numeroTarjetaInput = document.getElementById('numeroTarjeta');
if (numeroTarjetaInput) {
    numeroTarjetaInput.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/\D/g, '');
    });
}

// Formatear vencimiento
const vencimientoInput = document.getElementById('vencimiento');
if (vencimientoInput) {
    vencimientoInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value;
    });
}

// Validar DNI
const dniInput = document.getElementById('dni');
if (dniInput) {
    dniInput.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/\D/g, '');
    });
}

// Enviar formulario
if (formCheckout) {
    formCheckout.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Generar número de orden
        const numeroOrden = Math.floor(Math.random() * 100000);
        document.getElementById('numeroOrden').textContent = numeroOrden;
        
        // Recopilar datos del formulario
        const formData = new FormData(formCheckout);
        const datosCompra = {
            numeroOrden: numeroOrden,
            cliente: {
                nombre: formData.get('nombre'),
                apellido: formData.get('apellido'),
                dni: formData.get('dni'),
                email: formData.get('email'),
                telefono: formData.get('telefono')
            },
            formaPago: formData.get('formaPago'),
            productos: carrito,
            subtotal: carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0),
            envio: COSTO_ENVIO,
            total: carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0) + COSTO_ENVIO,
            fecha: new Date().toLocaleString('es-AR')
        };
        
        // Agregar datos de pago según el método
        if (datosCompra.formaPago === 'tarjeta') {
            datosCompra.datosPago = {
                numeroTarjeta: '****' + formData.get('numeroTarjeta').slice(-4),
                vencimiento: formData.get('vencimiento'),
                titular: formData.get('nombreTitular')
            };
        } else if (datosCompra.formaPago === 'transferencia') {
            datosCompra.datosPago = {
                comprobante: formData.get('comprobanteTransferencia')
            };
        }
        
        // Simular procesamiento
        console.log('Compra realizada:', datosCompra);
        
        // Vaciar carrito
        carrito = [];
        localStorage.removeItem('carritoTienda');
        
        // Cerrar modal checkout
        modalCheckout.style.display = 'none';
        
        // Mostrar modal de confirmación
        modalConfirmacion.style.display = 'block';
        
        // Resetear formulario
        formCheckout.reset();
    });
}

// Botón volver a tienda desde confirmación
const btnVolverTienda = document.getElementById('btnVolverTienda');
if (btnVolverTienda) {
    btnVolverTienda.addEventListener('click', () => {
        modalConfirmacion.style.display = 'none';
        volverTienda();
    });
}

// Botones volver
const btnVolver = document.getElementById('btnVolver');
const btnVolverVacio = document.getElementById('btnVolverVacio');

if (btnVolver) {
    btnVolver.addEventListener('click', volverTienda);
}

if (btnVolverVacio) {
    btnVolverVacio.addEventListener('click', volverTienda);
}

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
    cargarCarrito();
});