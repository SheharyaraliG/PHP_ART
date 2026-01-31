# ⚡ INICIO RÁPIDO - 5 MINUTOS

## 🎯 Tu objetivo: Que PHP_ART funcione

## PASO 1: ¿XAMPP está iniciado?

```
1. Abre XAMPP Control Panel
2. Haz clic en "Start" en:
   ✓ Apache
   ✓ MySQL
3. Espera a que sean VERDE
```

**Si sale error, reinicia XAMPP.**

---

## PASO 2: ¿La base de datos existe?

```
1. Ve a http://localhost/phpmyadmin
2. En la izquierda debería ver "galeria_arte"
3. Si NO la ves:
   - Haz clic en "Nueva"
   - Nombre: galeria_arte
   - Codificación: utf8mb4_unicode_ci
   - Clic en "Crear"
```

---

## PASO 3: ¿Las tablas existen?

```
1. Haz clic en "galeria_arte"
2. Debería ver 3 tablas:
   ✓ usuarios
   ✓ obras
   ✓ favoritos

3. Si NO las ves:
   a. Ve a la pestaña "SQL"
   b. Abre el archivo database.sql (en bloc de notas)
   c. Copia TODO su contenido
   d. Pégalo en phpMyAdmin
   e. Haz clic en "Ejecutar"
```

---

## PASO 4: ¿Los usuarios existen?

```
1. Haz clic en "usuarios"
2. Debería ver filas con usuarios como:
   - admin@test.com
   - artista@test.com
   - cliente@test.com

3. Si NO hay:
   a. Ve a "SQL"
   b. Copia todo de database.sql (la parte de INSERT INTO usuarios)
   c. Pégalo y ejecuta

O simplemente ejecuta:

INSERT INTO usuarios (nombre, email, password, rol) VALUES 
('Admin', 'admin@test.com', '$2y$10$Aa123...', 'admin'),
('Artista', 'artista@test.com', '$2y$10$Bb456...', 'artista'),
('Cliente', 'cliente@test.com', '$2y$10$Cc789...', 'cliente');
```

---

## PASO 5: ¿Las imágenes están?

```
1. Abre: C:\xampp\htdocs\PHP_ART\fotos\
2. Debería ver archivos JPG como:
   - 1000085550.jpg
   - 1000085553.jpg
   - etc.

3. Si NO hay imágenes:
   a. Busca cualquier imagen JPG en tu PC
   b. Cópiala a esa carpeta
   c. Renómbrala a 1000085550.jpg (o similar)
   d. En phpMyAdmin, actualiza la ruta:
      UPDATE obras SET imagen = 'fotos/1000085550.jpg' WHERE id = 1;
```

---

## PASO 6: ¡Abre la aplicación!

```
1. Ve a: http://localhost/PHP_ART/
2. Deberías ver:
   ✓ Página principal con "Galería de Arte"
   ✓ 3 obras destacadas
   ✓ Botones de navegación
   ✓ Menú arriba
```

---

## PASO 7: ¡Prueba a entrar!

```
1. Haz clic en "Entrar" (arriba a la derecha)
2. Email: admin@test.com
3. Contraseña: Password123
4. Haz clic en "Entrar"

RESULTADO:
✓ Deberías ver tu nombre (Admin)
✓ Botón "+ Subir Obra" debería aparecer
✓ Botón "❤️ Favoritos" debería aparecer
✓ Botón "Salir" debería aparecer
```

---

## PASO 8: ¡Prueba todas las funciones!

```
✓ VER OBRAS:
  - Haz clic en "Colección"
  - Debería ver un grid con obras
  - Debería ver precios

✓ FILTRAR:
  - Haz clic en "Originales"
  - Debería filtrar obras

✓ BUSCAR:
  - Haz clic en "Buscar"
  - Escribe "mar"
  - Debería encontrar obras con "mar"

✓ FAVORITOS:
  - Haz clic en el corazón gris
  - Debería ponerse rojo
  - Haz clic en ❤️ Favoritos
  - Debería aparecer tu obra favorita

✓ SALIR:
  - Haz clic en "Salir"
  - Menú debería cambiar
  - Debería mostrar "Entrar"
```

---

## ✅ ¡Listo!

Si todo funciona, **¡felicidades!** 🎉

Tu aplicación PHP_ART está instalada y funcionando correctamente.

---

## ❌ Si algo falla:

```
PROBLEMA: Página blanca (sin errores)
SOLUCIÓN: Abre F12 (herramientas), ve a "Console"
          Busca errores rojos
          Si no hay: Reinicia XAMPP

PROBLEMA: "Connection refused"
SOLUCIÓN: ¿Está MySQL iniciado en XAMPP?
          Haz clic en Start

PROBLEMA: "Table doesn't exist"
SOLUCIÓN: Las tablas no se importaron
          Ve a phpMyAdmin → SQL
          Pega el contenido de database.sql
          Haz clic en "Ejecutar"

PROBLEMA: "Access denied"
SOLUCIÓN: Datos de conexión incorrectos
          Abre config/database.php
          Usuario: root
          Contraseña: vacía (no escribir nada)

PROBLEMA: Las imágenes no aparecen
SOLUCIÓN: Ve a C:\xampp\htdocs\PHP_ART\fotos\
          ¿Hay archivos JPG?
          Si no, copia cualquier JPG ahí
          Si sí: Recarga la página (Ctrl+F5)
```

---

## 📚 QUIERO APRENDER MÁS

Lee en este orden:

1. **README.md** - Explicación completa (10 min)
2. **RESUMEN_VISUAL.md** - Diagramas (10 min)
3. **GUIA_FLUJO.md** - Paso a paso (30 min)
4. **Lee el código comentado** (1-2 horas)
5. **GUIA_BASE_DATOS.md** - SQL (20 min)
6. **COMANDOS_SQL.md** - Para practicar

---

## 🎯 PRÓXIMO PASO

Después de que todo funcione:

```
1. Abre el archivo config/database.php en VS Code
2. Lee los comentarios línea por línea
3. Entiende cómo se conecta a la BD
4. Abre auth/login.php
5. Lee cómo funciona el login
6. Abre obras/listar.php
7. Entiende cómo se muestran las obras
```

**Tiempo:** 30-60 minutos  
**Resultado:** Entenderás cómo funciona PHP

---

## 🚀 ¡YA PUEDES EMPEZAR!

```
┌─────────────────────────────────────┐
│   ¡FELICIDADES!                     │
│                                     │
│  PHP_ART está instalado y funciona. │
│                                     │
│  Ahora aprende cómo está hecho.     │
│  Lee los archivos .md               │
│  Y entiende el código.              │
│                                     │
│  ¡Éxito en tu aprendizaje! 🎉      │
└─────────────────────────────────────┘
```

---

**Última actualización:** 2024  
**Tiempo de lectura:** 5 minutos  
**Dificultad:** Fácil
