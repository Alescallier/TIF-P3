# 🛍️ Amictus – Tienda de Ropa Online

**E-commerce dinámico desarrollado en PHP, MySQL y JavaScript.**

Amictus es un proyecto completo de e-commerce que incluye catálogo dinámico desde base de datos, carrito persistente, panel administrativo con CRUD, login de administrador, y simulación de checkout. Está diseñado para escalar hacia API de pagos reales, integración de servicios de envío y despliegue en hosting.

---

## 🚀 Características principales

### 🧑‍💻 **Frontend**

* Renderizado dinámico de productos desde MySQL.
* Carrito de compras persistente con **localStorage**.
* Cálculo automático de subtotal, envío y total.
* Modales animados para checkout y confirmación.
* Interfaz responsive y estilizada.

### 🗄️ **Backend**

* PHP con conexión modular a MySQL.
* CRUD completo de productos (crear, listar, editar, eliminar).
* Login de administrador con `$_SESSION`.
* Protección de rutas del panel admin.
* Generación dinámica de vistas con PHP.

### 🧷 **Base de datos**

* Tablas normalizadas:

  * `categorias`
  * `productos`
* Llaves primarias y foráneas.
* Codificación UTF-8 para compatibilidad con emojis como imágenes.

---

## 🧱 Estructura del proyecto

```
/amictus
│── index.php
│── carrito.php
│── login.php
│── validar_login.php
│── logout.php
│── admin_productos.php
│── obtener_productos.php
│── conexion.php
│── main.js
│── carrito.js
│── main.css
│── /img (opcional)
└── /sql (backups opcionales)
```

---

## 🛠️ Tecnologías utilizadas

### **Frontend**

* HTML5
* CSS3
* JavaScript (ES6)
* LocalStorage
* Modales y renderizado dinámico

### **Backend**

* PHP 8+
* Sesiones
* Validación
* CRUD con MySQLi

### **Base de datos**

* MySQL / MariaDB
* Tablas relacionales
* UTF-8 para emojis

### **Entorno**

* XAMPP / Apache
* phpMyAdmin

---

## 🏗️ Instalación y uso

### 1️⃣ Clonar el repositorio

```sh
git clone https://github.com/usuario/amictus.git
```

### 2️⃣ Importar la base de datos

* Abrir **phpMyAdmin**
* Crear una BD llamada `amictus`
* Importar el archivo SQL correspondiente (si lo incluís)

### 3️⃣ Configurar conexión en `conexion.php`

```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "amictus";
```

### 4️⃣ Ejecutar proyecto desde navegador

```
http://localhost/amictus/
```

### 5️⃣ Acceder al panel admin

```
http://localhost/amictus/login.php
```

**Credenciales por defecto:**

* Usuario: `admin`
* Contraseña: `admin`

---

## 📦 Funcionalidades del CRUD admin

* Crear productos
* Editar productos
* Eliminar productos
* Gestión de categorías
* Campos compatibles con emojis
* Interfaz moderna con modales

---

## 📋 Roadmap / Próximas funciones

### 🔒 Seguridad

* Hash de contraseñas (`password_hash`)
* Gestor de usuarios y roles
* Tokens CSRF

### 🛒 E-commerce real

* Sistema de usuarios/clientes
* Órdenes almacenadas en la BD
* Carrito sincronizado por usuario

### 💳 Integraciones

* MercadoPago API
* PayPal / Stripe
* APIs de envío (Andreani, OCA, Correo Argentino)

### 🌐 Deploy

* Hosting Apache/Nginx
* Configuración HTTPS
* Base de datos remota

---

---

## 📄 Licencia

Este proyecto puede utilizarse con fines educativos o personales.
Para uso comercial, contacta al autor.

---

## 👤 Autor

**Alejandro Escallier**
Estudiante de Ingeniería en Sistemas
Desarrollador Backend / Fullstack en progreso

---
