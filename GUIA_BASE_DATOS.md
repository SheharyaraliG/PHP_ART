# 📚 GUÍA DE LA BASE DE DATOS - PHP_ART

## ¿Qué es la Base de Datos?

La base de datos es como un archivo de Excel gigante donde se guardan todos los datos:
- Usuarios (clientes, artistas, admins)
- Obras de arte (título, precio, imagen)
- Favoritos (qué obras marcó cada usuario como favorita)
- Otros datos

En PHP_ART usamos **MySQL** que es muy común y fácil de usar.

## 📋 Las Tablas de la Base de Datos

### 1. Tabla `usuarios`

Almacena información de todos los usuarios.

```
usuarios
├── id (número único)
├── nombre (nombre del usuario)
├── email (correo electrónico - debe ser único)
├── password (contraseña encriptada)
├── rol (cliente, artista o admin)
└── created_at (cuándo se creó la cuenta)
```

**Ejemplo de fila:**
```
id: 1
nombre: Juan García
email: juan@example.com
password: $2y$10$A3b5c... (encriptada)
rol: artista
created_at: 2024-01-15 10:30:00
```

### 2. Tabla `obras`

Almacena información de todas las obras de arte.

```
obras
├── id (número único)
├── titulo (nombre de la obra)
├── descripcion (texto describiendo la obra)
├── tipo (original, lamina o digital)
├── precio (cuánto cuesta en euros)
├── stock (cuántas copias quedan disponibles)
├── imagen (ruta a la imagen: fotos/nombre.jpg)
├── created_at (cuándo se subió)
└── updated_at (cuándo se editó por última vez)
```

**Ejemplo de fila:**
```
id: 1
titulo: Atardecer en el Mar
descripcion: Una bella obra de arte moderno...
tipo: original
precio: 150.00
stock: 1
imagen: fotos/1000085550.jpg
created_at: 2024-01-10
updated_at: 2024-01-10
```

### 3. Tabla `favoritos`

Guarda qué obras cada usuario marcó como favorita.

```
favoritos
├── id (número único)
├── usuario_id (ID de quién marcó como favorito)
├── obra_id (ID de la obra favorita)
└── created_at (cuándo se marcó como favorita)
```

**Ejemplo:**
- Usuario 2 marcó como favorita la obra 5
- Usuario 3 marcó como favorita la obra 5 y la obra 7

## 🔍 Cómo Funciona la Conexión desde PHP

### En `config/database.php`:

```php
$pdo = new PDO("mysql:host=localhost;dbname=galeria_arte;charset=utf8mb4", "root", "");
```

Esto significa:
- **host=localhost** → Base de datos en mi ordenador
- **dbname=galeria_arte** → Nombre de la base de datos
- **root** → Usuario (por defecto en XAMPP)
- **""** → Contraseña (vacía por defecto)

## 📊 Operaciones Básicas

### 1. OBTENER DATOS (SELECT)

```php
// Obtener una obra por su ID
$stmt = $pdo->prepare("SELECT * FROM obras WHERE id = ?");
$stmt->execute([5]);
$obra = $stmt->fetch(); // Devuelve una fila
```

```php
// Obtener todas las obras
$stmt = $pdo->query("SELECT * FROM obras");
$obras = $stmt->fetchAll(); // Devuelve todas las filas
```

### 2. AÑADIR DATOS (INSERT)

```php
// Crear un nuevo usuario
$stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
$stmt->execute(["Juan", "juan@example.com", password_hash("123456", PASSWORD_DEFAULT), "cliente"]);
```

### 3. ACTUALIZAR DATOS (UPDATE)

```php
// Cambiar el precio de una obra
$stmt = $pdo->prepare("UPDATE obras SET precio = ? WHERE id = ?");
$stmt->execute([200, 5]);
```

### 4. ELIMINAR DATOS (DELETE)

```php
// Borrar una obra
$stmt = $pdo->prepare("DELETE FROM obras WHERE id = ?");
$stmt->execute([5]);
```

## 🔐 Seguridad en la Base de Datos

### Consultas Preparadas (Importante!)

**MAL (peligroso):**
```php
$sql = "SELECT * FROM usuarios WHERE email = '" . $_GET['email'] . "'";
// Si alguien pone: ' OR '1'='1 → obtiene todos los usuarios
```

**BIEN (seguro):**
```php
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$_GET['email']]);
// El ? es un placeholder, los datos se envían separados
```

### Encriptación de Contraseñas

No guardamos contraseñas en texto plano. Usamos `password_hash()`:

```php
// Al registrar:
$hash = password_hash("micontraseña123", PASSWORD_DEFAULT);
// Guarda algo como: $2y$10$A3b5c...

// Al verificar en login:
if (password_verify("micontraseña123", $hash)) {
    echo "Contraseña correcta!";
}
```

## 📑 Script SQL Completo

Cuando importas `database.sql`, se crean todas las tablas automáticamente:

```sql
CREATE DATABASE galeria_arte;
USE galeria_arte;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'artista', 'admin') DEFAULT 'cliente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE obras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    tipo ENUM('original', 'lamina', 'digital') DEFAULT 'original',
    precio DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 1,
    imagen VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    obra_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_favorito (usuario_id, obra_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (obra_id) REFERENCES obras(id) ON DELETE CASCADE
);
```

## 💾 Hacer una Copia de Seguridad

En phpMyAdmin:
1. Haz clic en la base de datos `galeria_arte`
2. Ve a "Exportar"
3. Descarga el archivo SQL

## 🆘 Comandos Útiles en phpMyAdmin

### Ver todas las bases de datos
```sql
SHOW DATABASES;
```

### Ver todas las tablas
```sql
SHOW TABLES;
```

### Ver estructura de una tabla
```sql
DESCRIBE usuarios;
```

### Ver cuántas obras hay
```sql
SELECT COUNT(*) FROM obras;
```

### Ver los 5 últimos usuarios registrados
```sql
SELECT * FROM usuarios ORDER BY created_at DESC LIMIT 5;
```

### Ver qué favoritos tiene el usuario 1
```sql
SELECT obras.* FROM obras JOIN favoritos ON obras.id = favoritos.obra_id WHERE favoritos.usuario_id = 1;
```

## 🎯 Relaciones Entre Tablas

Las tablas están conectadas:

```
usuarios ──┐
           ├──→ favoritos ←──┐
                            └─ obras
```

- Un usuario puede tener muchos favoritos
- Una obra puede estar en los favoritos de muchos usuarios
- Cuando borramos un usuario, se borran sus favoritos automáticamente
- Cuando borramos una obra, se borran sus favoritos automáticamente

Esto se llama "Integridad Referencial" y lo hacen los `FOREIGN KEY` y `ON DELETE CASCADE`.

## 🚀 Próximos Pasos

Si quieres expandir la base de datos:

### Añadir tabla de compras
```sql
CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    total DECIMAL(10, 2),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
```

### Añadir tabla de carrito
```sql
CREATE TABLE carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    obra_id INT NOT NULL,
    cantidad INT DEFAULT 1,
    UNIQUE KEY unique_carrito (usuario_id, obra_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (obra_id) REFERENCES obras(id)
);
```

---

**Recuerda:** La base de datos es el corazón de tu aplicación. Cuida bien los datos y siempre usa consultas preparadas.
