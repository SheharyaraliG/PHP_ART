# 🎓 GUÍA PASO A PASO - CÓMO FUNCIONA EL CÓDIGO

## 1. EL FLUJO GENERAL DE LA APLICACIÓN

```
Usuario abre navegador
         ↓
Usuario va a http://localhost/PHP_ART
         ↓
Se ejecuta index.php
         ↓
Se incluye header.php (menú)
         ↓
Se conecta config/database.php
         ↓
Se obtienen 3 obras al azar de la BD
         ↓
Se muestran en HTML con CSS
         ↓
Usuario ve la página con imágenes y botones
```

## 2. CUANDO EL USUARIO SE REGISTRA

Paso 1: Usuario abre `auth/registro.php`
```
Abre en navegador: http://localhost/PHP_ART/auth/registro.php
```

Paso 2: Usuario rellena el formulario
```html
<input name="nombre" value="Juan García">
<input name="email" value="juan@example.com">
<input name="password" value="micontraseña123">
<input type="submit" value="Crear Cuenta">
```

Paso 3: PHP recibe los datos en `registro.php`
```php
// Si es POST (el usuario pulsó submit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibimos los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Verificamos que no exista otro usuario con ese email
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        // Email ya existe
        $error = "Email ya registrado";
    } else {
        // Email es nuevo, crear usuario
        // Encriptar contraseña
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insertar en BD
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $email, $hash]);
        
        // Redirigir a login
        header("Location: login.php");
    }
}
```

Paso 4: Datos se guardan en `usuarios` table
```
Base de Datos galeria_arte:
┌─────────────────────────────────┐
│ usuarios                        │
├─────────────────────────────────┤
│ id | nombre | email | password │
├─────────────────────────────────┤
│ 1  │ Juan   │ juan@ │ $2y$10$ │
└─────────────────────────────────┘
```

## 3. CUANDO EL USUARIO ENTRA (LOGIN)

Paso 1: Usuario abre `auth/login.php` y rellena:
```html
<input name="email" value="juan@example.com">
<input name="password" value="micontraseña123">
<button>Entrar</button>
```

Paso 2: PHP verifica en `login.php`
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // 1. Buscar usuario por email en BD
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    
    // 2. Si existe y contraseña es correcta
    if ($usuario && password_verify($password, $usuario['password'])) {
        // Crear sesión (guardar en memoria del servidor)
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol'] = $usuario['rol'];
        
        // Redirigir a página principal
        header("Location: ../index.php");
    } else {
        // Si falla, mostrar error
        $error = "Email o contraseña incorrectos";
    }
}
```

Paso 3: Sesión creada en el servidor
```
Servidor (memoria):
┌────────────────────────────────┐
│ $_SESSION (usuario 1)          │
├────────────────────────────────┤
│ usuario_id = 5                 │
│ usuario_nombre = "Juan García" │
│ usuario_rol = "artista"        │
└────────────────────────────────┘

Navegador (cookie):
Set-Cookie: PHPSESSID=abc123def456
```

Paso 4: En todas las páginas podemos acceder
```php
// En cualquier página:
echo $_SESSION['usuario_nombre']; // Muestra: Juan García
echo $_SESSION['usuario_rol'];    // Muestra: artista
```

## 4. CUANDO VE LA GALERÍA (obras/listar.php)

Paso 1: Usuario abre `obras/listar.php`

Paso 2: PHP obtiene las obras
```php
// Si hay filtro de tipo
$tipo = $_GET['tipo']; // Ejemplo: 'original'

// Construir consulta SQL dinámicamente
$sql = "SELECT * FROM obras WHERE 1=1";
if ($tipo) {
    $sql .= " AND tipo = ?"; // Añadir filtro
}

// Ejecutar
$stmt = $pdo->prepare($sql);
$stmt->execute([$tipo]);
$obras = $stmt->fetchAll();
```

Paso 3: Mostrar en HTML con CSS
```php
<div class="grid">
    <?php foreach ($obras as $obra): ?>
        <div class="card">
            <img src="<?php echo $obra['imagen']; ?>">
            <h3><?php echo $obra['titulo']; ?></h3>
            <p><?php echo $obra['precio']; ?> €</p>
        </div>
    <?php endforeach; ?>
</div>
```

Resultado: Grid de obras con imágenes y precios

## 5. CUANDO MARCA COMO FAVORITO

Paso 1: Usuario logado ve una obra
```html
<!-- En listar.php o buscar.php -->
<a href="favoritos/accion.php?id=5&action=add">
    ❤ Añadir a Favoritos
</a>
```

Paso 2: PHP en `favoritos/accion.php`
```php
// Recibir parámetros de la URL
$obra_id = $_GET['id'];      // Ejemplo: 5
$action = $_GET['action'];    // Ejemplo: 'add'
$usuario_id = $_SESSION['usuario_id']; // Desde la sesión

// Si acción es "add"
if ($action == 'add') {
    // Insertar en tabla favoritos
    $stmt = $pdo->prepare("INSERT IGNORE INTO favoritos (usuario_id, obra_id) VALUES (?, ?)");
    $stmt->execute([$usuario_id, $obra_id]);
    // Resultado: nueva fila en favoritos
}

// Si acción es "remove"
else {
    // Eliminar de favoritos
    $stmt = $pdo->prepare("DELETE FROM favoritos WHERE usuario_id = ? AND obra_id = ?");
    $stmt->execute([$usuario_id, $obra_id]);
}

// Redirigir atrás
header("Location: " . $_SERVER['HTTP_REFERER']);
```

Paso 3: Base de datos se actualiza
```
favoritos table:
┌─────────────────────────┐
│ id | usuario_id | obra_id│
├─────────────────────────┤
│ 1  │ 5          │ 3      │
│ 2  │ 5          │ 7      │  ← Nueva fila añadida
└─────────────────────────┘
```

## 6. CUANDO BUSCA UNA OBRA (buscar.php)

Paso 1: Usuario escribe en el buscador
```html
<form action="buscar.php" method="GET">
    <input name="q" placeholder="Buscar...">
</form>
```

Paso 2: Se envía a `buscar.php?q=atardecer`

Paso 3: PHP busca en la BD
```php
// Recibir el término de búsqueda
$q = $_GET['q']; // Ejemplo: "atardecer"

// Buscar en titulo O descripcion
$sql = "SELECT * FROM obras WHERE titulo LIKE ? OR descripcion LIKE ?";
$param = "%$q%"; // "%atardecer%" busca parciales

$stmt = $pdo->prepare($sql);
$stmt->execute([$param, $param]);
$obras = $stmt->fetchAll();
```

Paso 4: Mostrar resultados
```
Resultados para "atardecer" (2 encontrados):
- Atardecer en el Mar
- Atardecer Urbano
```

## 7. ESTRUCTURA DE CARPETAS Y RESPONSABILIDADES

```
PHP_ART/
│
├── config/
│   └── database.php         ← Conexión (se usa en TODAS las páginas)
│
├── auth/
│   ├── login.php            ← Verificar usuario + crear $_SESSION
│   ├── registro.php         ← Crear usuario nuevo
│   └── logout.php           ← Destruir $_SESSION
│
├── obras/
│   ├── listar.php           ← Mostrar todas (con filtros)
│   ├── crear.php            ← Insertar nueva obra (admin)
│   ├── editar.php           ← Actualizar obra (admin)
│   └── eliminar.php         ← Borrar obra (admin)
│
├── favoritos/
│   ├── ver.php              ← Mostrar mis favoritos
│   └── accion.php           ← Añadir/quitar de favoritos
│
├── header.php               ← Menú (incluido en TODAS)
├── index.php                ← Página principal
├── buscar.php               ← Buscador
└── database.sql             ← Crear base de datos
```

## 8. LA SESIÓN EN DETALLE

### Qué es una sesión?

Una sesión es como un "carnet de identidad" temporal que el servidor entrega al usuario:

```
Usuario A:           Usuario B:
┌──────────┐        ┌──────────┐
│ ID: 5    │        │ ID: 7    │
│ Nombre:  │        │ Nombre:  │
│ Juan     │        │ María    │
│ Rol:     │        │ Rol:     │
│ artista  │        │ cliente  │
└──────────┘        └──────────┘
 PHPSESSID         PHPSESSID
 abc123            xyz789
```

### Cómo funciona:

1. Usuario entra con credenciales
2. Servidor crea sesión: `$_SESSION['usuario_id'] = 5`
3. Servidor envía cookie: `Set-Cookie: PHPSESSID=abc123`
4. Navegador guarda cookie
5. Cada vez que usuario envía solicitud, envía también la cookie
6. Servidor identifica de quién es y carga sus datos

### Más importante:

- Sin sesión: cada página no sabe quién eres
- Con sesión: todas las páginas saben quién eres
- La sesión dura mientras no cierres navegador o hagas logout

## 9. EJEMPLO: FLUJO COMPLETO

```
1. Usuario abre PHP_ART
   └─ index.php se ejecuta
   └─ Se incluye header.php
   └─ Se conecta a BD
   └─ Se obtienen 3 obras al azar
   └─ Se muestra página con obras

2. Usuario hace clic en "Registrarse"
   └─ Se abre auth/registro.php
   └─ Usuario rellena formulario
   └─ Se valida y se guarda en BD
   └─ Se redirige a login.php

3. Usuario entra con email y contraseña
   └─ Se verifica en BD
   └─ Se crea $_SESSION['usuario_id']
   └─ Se redirige a index.php

4. Usuario va a "Colección"
   └─ Se abre obras/listar.php
   └─ En header.php ahora aparece su nombre
   └─ Aparece botón "Favoritos"
   └─ Se muestran obras con corazones GRISES

5. Usuario marca una obra como favorita
   └─ Corazón se pone ROJO
   └─ Se ejecuta favoritos/accion.php?id=3&action=add
   └─ Se inserta en tabla favoritos

6. Usuario va a "Favoritos"
   └─ Se abre favoritos/ver.php
   └─ Se muestran solo sus obras favoritas
   └─ Puede eliminarlas con botón

7. Usuario hace clic en "Salir"
   └─ Se abre auth/logout.php
   └─ Se ejecuta session_destroy()
   └─ Se destruye $_SESSION
   └─ Se redirige a login.php
   └─ Usuario vuelve al inicio
```

## 10. PUNTOS CLAVE A RECORDAR

### Variables importantes:
- `$_SESSION['usuario_id']` → ID del usuario logado
- `$_SESSION['usuario_nombre']` → Nombre del usuario
- `$_SESSION['usuario_rol']` → Rol (cliente, artista, admin)
- `$_GET` → Parámetros en la URL
- `$_POST` → Datos del formulario
- `$pdo` → Conexión a la base de datos

### Funciones importantes:
- `password_hash()` → Encriptar contraseña
- `password_verify()` → Verificar contraseña
- `htmlspecialchars()` → Evitar XSS
- `header()` → Redirigir a otra página
- `session_start()` → Iniciar sesión
- `session_destroy()` → Cerrar sesión

### Seguridad:
- Siempre usar `?` en consultas (consultas preparadas)
- Siempre encriptar contraseñas
- Siempre validar datos del usuario
- Siempre usar `htmlspecialchars()` al mostrar datos

---

**Ahora entiendes cómo funciona PHP_ART!** 🎉
