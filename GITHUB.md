# 📤 CÓMO SUBIR PHP_ART A GITHUB

## PASO 1: Preparar el Repositorio Localmente

```bash
cd C:\xampp\htdocs\PHP_ART

# Inicializar repositorio git
git init

# Agregar todos los archivos
git add .

# Hacer commit inicial
git commit -m "Initial commit: PHP_ART galería de arte"
```

---

## PASO 2: Crear Repositorio en GitHub

1. Ve a https://github.com/new
2. **Repository name**: `php-art` (o el nombre que prefieras)
3. **Description**: "Galería de arte simple en PHP para aprender"
4. **Public** (para que otros vean)
5. No selecciones "Initialize this repository with"
6. Haz clic en **"Create repository"**

---

## PASO 3: Conectar y Subir

```bash
# Agregar el repositorio remoto
git remote add origin https://github.com/TU_USUARIO/php-art.git

# Cambiar rama principal (si es necesario)
git branch -M main

# Subir archivos
git push -u origin main
```

**Nota:** Reemplaza `TU_USUARIO` con tu nombre de usuario de GitHub

---

## ⚠️ PROBLEMA: La Base de Datos NO se sube a Git

**Git solo sube código, NO datos de la base de datos.**

Tienes 3 opciones:

---

## OPCIÓN 1: Usar database.sql (RECOMENDADO) ✅

**Ya tienes `database.sql` en tu proyecto.**

Es el archivo que crea la estructura de la BD con datos de prueba.

```bash
# Está en C:\xampp\htdocs\PHP_ART\database.sql
# Git automáticamente lo sube
git add database.sql
git commit -m "Add database structure with test data"
git push
```

**Instrucciones para otros usuarios:**
1. Descargan el proyecto
2. Abren phpMyAdmin
3. Crean BD: `CREATE DATABASE galeria_arte;`
4. Van a SQL
5. Copian y pegan todo de `database.sql`
6. Hacen clic en "Ejecutar"
7. ¡Listo!

---

## OPCIÓN 2: Exportar desde phpMyAdmin

Si quieres actualizar el `database.sql` con más datos:

```
1. Abre phpMyAdmin
2. Selecciona BD: galeria_arte
3. Arriba: "Exportar"
4. Formato: SQL
5. Haz clic en "Ejecutar"
6. Se descarga el archivo
7. Reemplaza database.sql en tu proyecto
```

---

## OPCIÓN 3: Crear Script de Setup

Crea un archivo `setup_database.php`:

```php
<?php
// setup_database.php - Script para crear BD automáticamente

$host = 'localhost';
$user = 'root';
$pass = '';

// Conexión sin BD especificada
$pdo = new PDO("mysql:host=$host", $user, $pass);

// Leer el archivo SQL
$sql = file_get_contents('database.sql');

// Ejecutar
try {
    $pdo->exec($sql);
    echo "✅ Base de datos creada correctamente";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
```

Luego otros usuarios abren `localhost/php-art/setup_database.php` y ¡listo!

---

## 📋 RESUMEN: Qué se sube a GitHub

```
✅ SUBE (código)
├── config/
├── auth/
├── obras/
├── favoritos/
├── assets/
├── fotos/ (imágenes)
├── *.php (todos los archivos)
├── *.md (documentación)
├── database.sql (estructura BD)
└── .gitignore

❌ NO SUBE (datos en tiempo real)
├── MySQL en vivo
├── Sesiones activas
├── Variables de entorno sensibles
└── Configuración local
```

---

## 🚀 FLUJO COMPLETO

```
1. USUARIO DESCARGA TU PROYECTO
   ↓
2. SIGUE: README.md → INICIO_RAPIDO.md
   ↓
3. IMPORTA database.sql EN phpMyAdmin
   ↓
4. ¡A USAR!
```

---

## 📝 README para GitHub

Actualiza tu `README.md` con esto (al inicio):

```markdown
# PHP_ART - Galería de Arte

[![GitHub](https://img.shields.io/badge/GitHub-php--art-blue)](https://github.com/TU_USUARIO/php-art)

Una galería de arte simple y educativa hecha con PHP puro.

## 🚀 Inicio Rápido

1. Descarga o clona este repositorio
2. Instala XAMPP
3. Copia la carpeta a `C:\xampp\htdocs\PHP_ART\`
4. Importa `database.sql` en phpMyAdmin
5. Ve a `http://localhost/PHP_ART/`

## 📋 Requisitos

- PHP 7.4+
- MySQL 5.7+
- XAMPP (recomendado)

## 📚 Documentación

- [INICIO_RAPIDO.md](INICIO_RAPIDO.md) - 5 pasos para instalar
- [README.md](README.md) - Guía completa
- [GUIA_FLUJO.md](GUIA_FLUJO.md) - Cómo funciona

## 📊 Características

- ✅ Autenticación segura
- ✅ CRUD de obras
- ✅ Sistema de favoritos
- ✅ Búsqueda avanzada
- ✅ Roles y permisos
- ✅ Código comentado línea por línea

## 👤 Usuarios de Prueba

| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@test.com | Password123 | admin |
| artista@test.com | Password123 | artista |
| cliente@test.com | Password123 | cliente |

## 📄 Licencia

Libre de usar y modificar con propósitos educativos.

---

Hecho para aprender PHP ❤️
```

---

## 🔧 Comandos Git Útiles

```bash
# Ver estado
git status

# Ver commits
git log

# Descargar cambios del servidor
git pull

# Actualizar después de cambios locales
git add .
git commit -m "Descripción de cambios"
git push

# Ver repositorio remoto
git remote -v

# Clonar el proyecto (otros usuarios)
git clone https://github.com/TU_USUARIO/php-art.git
```

---

## 🎯 RESUMEN

**Lo que sube:**
- ✅ TODO el código PHP
- ✅ database.sql (estructura + datos de prueba)
- ✅ Documentación
- ✅ Imágenes

**Instrucciones para otros:**
1. Clonan el repo
2. Importan `database.sql`
3. Usan la aplicación

**La BD en vivo no se sube**, pero el `database.sql` permite recrearla fácilmente.

---

## ❓ PREGUNTAS FRECUENTES

**P: ¿Se sube la contraseña de la BD?**
R: No, porque está en el `database.sql` es solo estructura. Las credenciales (root/vacío) son las de XAMPP.

**P: ¿Se pueden cargar más obras después?**
R: Sí, pero no se sincronizarán en GitHub (a menos que actualices `database.sql`).

**P: ¿Cómo otros usuarios crean sus propias obras?**
R: Usan la aplicación normalmente. Cada instalación tiene su propia BD.

---

**¡Listo para subir a GitHub!** 🚀
