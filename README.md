# PHP_ART - Galería de Arte en Línea

## 📋 Descripción

PHP_ART es una galería de arte simple y fácil de entender. Permite a los usuarios:
- 🎨 Ver obras de arte (originales, láminas, obras digitales)
- 🔍 Buscar obras por título o descripción
- ❤️ Marcar obras como favoritas
- 👥 Crear cuenta como cliente o artista
- 🛒 Gestionar un carrito de compras (en desarrollo)

## 🚀 Cómo Empezar

### 1. Base de Datos

Abre tu gestor de MySQL (phpMyAdmin) y crea la base de datos:

```sql
CREATE DATABASE galeria_arte;
USE galeria_arte;
```

Luego importa el archivo `database.sql` que contiene todas las tablas.

### 2. Editar Configuración (si es necesario)

En `config/database.php`, verifica que los datos de conexión sean correctos:
- Usuario: `root` (predeterminado en XAMPP)
- Contraseña: vacía (predeterminado en XAMPP)
- Host: `localhost`

### 3. Inicia XAMPP

- Abre XAMPP Control Panel
- Inicia Apache y MySQL
- Ve a http://localhost/PHP_ART

## 📁 Estructura de Carpetas

```
PHP_ART/
├── config/
│   └── database.php       ← Conexión a la base de datos
├── auth/
│   ├── login.php          ← Página para entrar
│   ├── registro.php       ← Página para crear cuenta
│   └── logout.php         ← Cerrar sesión
├── obras/
│   ├── listar.php         ← Ver todas las obras
│   ├── crear.php          ← Crear obra (solo artistas)
│   ├── editar.php         ← Editar obra
│   └── eliminar.php       ← Borrar obra
├── favoritos/
│   ├── ver.php            ← Ver mis favoritos
│   └── accion.php         ← Añadir/quitar de favoritos
├── fotos/                 ← Imágenes de obras aquí
├── header.php             ← Menú y navegación
├── index.php              ← Página principal
├── buscar.php             ← Buscador
└── database.sql           ← Archivo de base de datos
```

## 🔑 Usuarios de Prueba

Después de importar la base de datos, puedes usar estos usuarios:

| Email | Contraseña | Rol |
|-------|-----------|-----|
| cliente@test.com | Password123 | cliente |
| artista@test.com | Password123 | artista |
| admin@test.com | Password123 | admin |

## 💡 Explicación de Cada Archivo

### `config/database.php`
Conecta PHP con la base de datos MySQL usando PDO. Está comentado línea por línea para que entiendas cada parte.

### `auth/login.php`
Verifica el email y contraseña del usuario. Si son correctos, crea una sesión y guarda el ID, nombre y rol del usuario.

### `auth/registro.php`
Permite crear nuevas cuentas. Verifica que el email no exista, encripta la contraseña, y la guarda en la base de datos.

### `auth/logout.php`
Elimina la sesión del usuario y lo redirige a login.

### `obras/listar.php`
Muestra todas las obras en un grid (rejilla). Permite filtrar por tipo (original, lámina, digital). Muestra el corazón para favoritos.

### `buscar.php`
Buscador simple que busca por título o descripción de las obras.

### `favoritos/ver.php`
Muestra las obras que el usuario ha marcado como favoritas.

### `favoritos/accion.php`
Añade o quita una obra de los favoritos del usuario.

### `index.php`
Página principal con 3 obras destacadas y botones para navegar.

### `header.php`
Menú superior que aparece en todas las páginas. Contiene el logo, búsqueda y opciones de usuario.

## 🎯 Cómo el Código es Simple

Todo el código está escrito de forma simple con:
- ✅ Comentarios en cada línea explicando qué hace
- ✅ Variables con nombres claros (no `$u`, sino `$usuario_id`)
- ✅ Estructuras fáciles de leer (sin "trucos" innecesarios)
- ✅ Funciones reutilizables en `config/database.php`
- ✅ Sin librerías complicadas (solo PHP puro y HTML/CSS básico)

## 🛠️ Tecnologías Usadas

- **PHP 7.x+** - Lenguaje del servidor
- **MySQL/MariaDB** - Base de datos
- **PDO** - Para consultas seguras a la base de datos
- **HTML5** - Estructura de páginas
- **CSS3** - Estilos y diseño responsivo
- **JavaScript básico** - Interacciones simples

## 🔐 Seguridad

El código usa:
- `password_hash()` para encriptar contraseñas
- `password_verify()` para verificar contraseñas
- Consultas preparadas de PDO para evitar inyecciones SQL
- `htmlspecialchars()` para evitar XSS (scripts maliciosos)

## 📝 Cómo Añadir Nuevas Obras

1. Abre `obras/crear.php` (si eres artista o admin)
2. Rellena los datos: título, descripción, tipo, precio, stock
3. Sube una imagen
4. Haz clic en "Crear"

La obra se guardará en la base de datos y aparecerá en el catálogo.

## 🐛 Si Algo Falla

### Las imágenes no aparecen
- Verifica que las imágenes están en la carpeta `/fotos/`
- Comprueba que la ruta en la base de datos es correcta

### No puedo entrar
- Verifica que los datos de conexión en `config/database.php` son correctos
- Comprueba que MySQL está iniciado en XAMPP
- Verifica que el usuario existe en la base de datos

### La página es blanca
- Abre las herramientas de desarrollador (F12) en el navegador
- Mira si hay errores en la consola
- Comprueba el archivo error.log de PHP

## 📞 Soporte

Si tienes dudas sobre cómo funciona algo, lee los comentarios en el código. Cada línea tiene explicación.

---

**Hecho con ❤️ para aprender PHP**
