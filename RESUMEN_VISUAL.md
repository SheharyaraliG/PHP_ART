# 🎯 RESUMEN VISUAL - PHP_ART

## 🏗️ ARQUITECTURA GENERAL

```
┌─────────────────────────────────────────────────────────┐
│                   NAVEGADOR DEL USUARIO                 │
│              (http://localhost/PHP_ART)                  │
└────────────────────────┬────────────────────────────────┘
                         │
                    USUARIO ABRE
                    PÁGINA PHP
                         │
           ┌─────────────┴─────────────┐
           │                           │
       ¿LOGADO?                    ¿NO LOGADO?
           │                           │
      SI / NO                         │
      $_SESSION          MOSTRAR LOGIN/REGISTRO
```

## 🔄 FLUJO DE ENTRADA DE UN USUARIO

```
┌──────────────────────┐
│  Usuario abre URL    │
│  index.php           │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────────────────────┐
│  header.php incluido                 │
│  - Inicia sesión (session_start)     │
│  - Verifica $_SESSION['usuario_id']  │
│  - Muestra menú personalizado        │
└──────────┬───────────────────────────┘
           │
           ▼
┌──────────────────────────────────────┐
│  config/database.php incluido        │
│  - Conecta a galeria_arte            │
│  - Crea variable $pdo                │
└──────────┬───────────────────────────┘
           │
           ▼
┌──────────────────────────────────────┐
│  Código PHP se ejecuta               │
│  - Obtiene datos de BD               │
│  - Genera HTML dinámico              │
└──────────┬───────────────────────────┘
           │
           ▼
┌──────────────────────────────────────┐
│  HTML + CSS enviados al navegador    │
│  - Usuario ve la página              │
└──────────────────────────────────────┘
```

## 📁 ESTRUCTURA SIMPLIFICADA

```
PHP_ART/
├── 🌐 index.php              ← Página principal (3 obras al azar)
├── 🔍 buscar.php             ← Búsqueda por título
├── 📄 header.php             ← Menú (en TODAS las páginas)
│
├── 📁 config/
│   └── database.php          ← Conexión a BD (en TODAS las páginas)
│
├── 📁 auth/ (Autenticación)
│   ├── login.php             ← Entrar con email/password
│   ├── registro.php          ← Crear nueva cuenta
│   └── logout.php            ← Cerrar sesión
│
├── 📁 obras/ (CRUD de obras)
│   ├── listar.php            ← Ver todas (filtrar por tipo)
│   ├── crear.php             ← Insertar nueva (admin)
│   ├── editar.php            ← Actualizar (admin)
│   └── eliminar.php          ← Borrar (admin)
│
├── 📁 favoritos/
│   ├── ver.php               ← Mis favoritos (JOIN)
│   └── accion.php            ← Añadir/quitar (INSERT/DELETE)
│
├── 📁 fotos/                 ← Imágenes de obras
│   ├── 1000085550.jpg
│   ├── 1000085553.jpg
│   └── ...
│
├── 📁 assets/
│   └── css/style.css         ← Estilos
│
└── 📄 database.sql           ← Script para crear BD
```

## 🗄️ BASE DE DATOS (BD = galeria_arte)

```
┌─────────────────────────────────────────────────────────┐
│                  BASE DE DATOS                          │
│                  galeria_arte                           │
└─────────────────────────────────────────────────────────┘

┌──────────────────┐   ┌──────────────────┐   ┌──────────────┐
│    usuarios      │   │     obras        │   │  favoritos   │
├──────────────────┤   ├──────────────────┤   ├──────────────┤
│ id (PK)          │   │ id (PK)          │   │ id (PK)      │
│ nombre           │   │ titulo           │   │ usuario_id   │
│ email (UNIQUE)   │   │ descripcion      │   │ obra_id      │
│ password (HASH)  │───→ tipo            │←──┤ created_at   │
│ rol              │   │ precio           │   │              │
│ created_at       │   │ stock            │   │ UNIQUE:      │
└──────────────────┘   │ imagen           │   │ (user_id,    │
                       │ created_at       │   │  obra_id)    │
                       │ updated_at       │   └──────────────┘
                       └──────────────────┘
```

## 🔐 CICLO DE AUTENTICACIÓN

```
                        ┌──────────────┐
                        │ Navegador    │
                        └──────┬───────┘
                               │
                    Usuario entra credenciales
                               │
                        ┌──────▼───────┐
                        │ login.php    │
                        └──────┬───────┘
                               │
              ┌────────────────┼────────────────┐
              │                │                │
        VALIDAR      ┌─────────▼────────────┐
        • Email         │  Buscar en BD    │
        • Contraseña    │  SELECT WHERE    │
              │         │  email = ?       │
              │         └─────────┬────────┘
              │                   │
        ┌─────▼──────────────────▼────────┐
        │ ¿Usuario existe?                │
        │ ¿Contraseña correcta?           │
        └─────┬──────────────────┬────────┘
              │ SI               │ NO
              │                  │
        ┌─────▼──────────┐  ┌───▼─────────────┐
        │ $_SESSION[..] │  │ Mostrar error   │
        │ - usuario_id  │  │ "Credenciales   │
        │ - nombre      │  │  incorrectas"   │
        │ - rol         │  └─────────────────┘
        │               │
        │ COOKIE        │
        │ PHPSESSID     │
        └─────┬─────────┘
              │
        ┌─────▼──────────┐
        │ header()       │
        │ Location:      │
        │ ../index.php   │
        └────────────────┘
```

## 🎨 FLUJO DE VISUALIZACIÓN DE OBRAS

```
PÁGINA: obras/listar.php
┌──────────────────────────────────────────────────────┐
│                                                      │
│  INICIO                                              │
│    │                                                 │
│    ├─→ Incluir config/database.php (conectar BD)    │
│    ├─→ Incluir header.php (mostrar menú)            │
│    │                                                 │
│    └─→ $tipo = $_GET['tipo'] (filtro opcional)      │
│                                                      │
│  CONSTRUIR CONSULTA SQL                             │
│    │                                                 │
│    ├─→ SELECT * FROM obras WHERE 1=1               │
│    │                                                 │
│    └─→ if ($tipo)                                   │
│         └─→ AND tipo = ?                            │
│                                                      │
│  EJECUTAR CONSULTA                                  │
│    │                                                 │
│    ├─→ $stmt = $pdo->prepare($sql)                  │
│    ├─→ $stmt->execute([$tipo])                      │
│    │                                                 │
│    └─→ $obras = $stmt->fetchAll()                   │
│                                                      │
│  MOSTRAR HTML                                        │
│    │                                                 │
│    ├─→ <div class="grid">                           │
│    │                                                 │
│    └─→ foreach ($obras as $obra):                   │
│         │                                            │
│         ├─→ <div class="card">                      │
│         ├─→   <img src="<?= $obra['imagen'] ?>">   │
│         ├─→   <h3><?= $obra['titulo'] ?></h3>      │
│         ├─→   <p><?= $obra['precio'] ?> €</p>      │
│         ├─→   if (usuario logado):                  │
│         │     └─→ Mostrar corazón (favorito)        │
│         └─→ </div>                                  │
│                                                      │
└──────────────────────────────────────────────────────┘
```

## ❤️ FLUJO DE FAVORITOS

```
USUARIO LOGADO HACIENDO CLIC EN CORAZÓN
┌────────────────────────────────────┐
│                                    │
│  Usuario ve obra id=5              │
│  Hizo clic en corazón gris         │
│                                    │
│  URL: favoritos/accion.php?        │
│       id=5&action=add              │
│                                    │
└────────────┬──────────────────────┘
             │
        ┌────▼────────────────────┐
        │  accion.php             │
        │  - session_start()      │
        │  - $uid = $_SESSION[..] │
        │  - $oid = $_GET['id']   │
        │  - $action = $_GET[..]  │
        └────┬──────────────────┬─┘
             │                  │
        action=add       action=remove
             │                  │
        ┌────▼─────────┐   ┌────▼──────────┐
        │ INSERT INTO  │   │ DELETE FROM   │
        │ favoritos    │   │ favoritos     │
        │ (uid, oid)   │   │ WHERE uid AND │
        │              │   │ oid           │
        └────┬─────────┘   └────┬──────────┘
             │                  │
             └────────┬─────────┘
                      │
              ┌───────▼────────┐
              │ header()       │
              │ HTTP_REFERER   │
              │ (volver atrás) │
              └────────────────┘
```

## 🔒 SEGURIDAD EN CADA PASO

```
┌─────────────────────────────────────────────────────┐
│              PIPELINE DE SEGURIDAD                  │
└─────────────────────────────────────────────────────┘

ENTRADA (De usuario)
    │
    ├─→ Validación (campos obligatorios)
    │
    ├─→ Sanitización (trim, htmlspecialchars)
    │
    ├─→ Preparación (prepared statements con ?)
    │
    ├─→ Ejecución (execute con parámetros)
    │
    └─→ Salida (htmlspecialchars al mostrar)

CONTRASEÑAS
    │
    ├─→ Entrada: "micontraseña"
    │
    ├─→ password_hash() ──→ "$2y$10$A3b5c..."
    │
    ├─→ Guardada en BD
    │
    └─→ Login: password_verify(input, hash)
```

## 📊 ROLES Y PERMISOS

```
┌──────────────────────────────────────────┐
│ USUARIO: cliente                         │
├──────────────────────────────────────────┤
│ ✓ Ver obras                              │
│ ✓ Buscar obras                           │
│ ✓ Ver favoritos                          │
│ ✓ Marcar como favorito                   │
│ ✗ Crear/editar/eliminar obras            │
│ ✗ Ver datos de otros usuarios            │
└──────────────────────────────────────────┘

┌──────────────────────────────────────────┐
│ USUARIO: artista                         │
├──────────────────────────────────────────┤
│ ✓ Ver obras                              │
│ ✓ Buscar obras                           │
│ ✓ Ver favoritos                          │
│ ✓ Marcar como favorito                   │
│ ✓ Crear obras (propias)                  │
│ ✓ Editar obras (propias)                 │
│ ✗ Crear/editar obras de otros            │
│ ✗ Eliminar obras                         │
└──────────────────────────────────────────┘

┌──────────────────────────────────────────┐
│ USUARIO: admin                           │
├──────────────────────────────────────────┤
│ ✓ Ver obras                              │
│ ✓ Buscar obras                           │
│ ✓ Ver favoritos                          │
│ ✓ Marcar como favorito                   │
│ ✓ Crear obras                            │
│ ✓ Editar obras                           │
│ ✓ Eliminar obras                         │
│ ✓ Ver datos de otros usuarios            │
│ ✓ Modificar roles                        │
└──────────────────────────────────────────┘
```

## 🚀 INICIAR LA APLICACIÓN

```
PASO 1: Base de datos
├─→ Abre phpMyAdmin (http://localhost/phpmyadmin)
├─→ Crea base de datos: CREATE DATABASE galeria_arte;
├─→ Importa database.sql
└─→ Verifica 3 tablas: usuarios, obras, favoritos

PASO 2: Verifica config
├─→ Abre config/database.php
├─→ Verifica usuario = root
├─→ Verifica contraseña = vacía
└─→ Verifica dbname = galeria_arte

PASO 3: Inicia XAMPP
├─→ Abre XAMPP Control Panel
├─→ Inicia Apache
├─→ Inicia MySQL
└─→ Espera a que estén en verde

PASO 4: Abre la app
├─→ Ve a http://localhost/PHP_ART
├─→ Debería ver página principal
├─→ Prueba entrar con: admin@test.com / Password123
└─→ ¡A disfrutar!
```

## 📈 CRECIMIENTO FUTURO

```
PHP_ART (ACTUAL)
├── Usuarios
├── Obras
├── Favoritos
└── Búsqueda

PHP_ART + CARRITO (PRÓXIMO)
├── Tabla: carrito (usuario_id, obra_id, cantidad)
├── Página: carrito/ver.php
├── Página: carrito/checkout.php
└── Lógica de cálculo de total

PHP_ART + COMPRAS (FUTURO)
├── Tabla: compras (usuario_id, total, fecha)
├── Tabla: lineas_compra (compra_id, obra_id, cantidad, precio)
├── Página: compras/mis_compras.php
├── Página: compras/factura.php
└── Lógica de pago (Stripe/PayPal)

PHP_ART + ADMIN (FUTURO)
├── Dashboard con estadísticas
├── Gráficos de ventas
├── Gestión de usuarios
├── Reportes
└── Análisis de datos
```

---

**Tip:** Guarda este documento como referencia mientras desarrollas. 📚
