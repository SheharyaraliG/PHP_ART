# 🔧 COMANDOS SQL ÚTILES - REFERENCIA RÁPIDA

## Acceder a phpMyAdmin

1. Abre http://localhost/phpmyadmin
2. Entra con usuario `root` (sin contraseña)
3. Selecciona la base de datos `galeria_arte`
4. Haz clic en "SQL" para ejecutar comandos

## 📊 INFORMACIÓN SOBRE LAS TABLAS

### Ver todas las bases de datos
```sql
SHOW DATABASES;
```

### Ver todas las tablas de la BD actual
```sql
SHOW TABLES;
```

### Ver estructura de una tabla
```sql
DESCRIBE usuarios;
-- O más detallado:
SHOW CREATE TABLE usuarios;
```

## 👥 CONSULTAS SOBRE USUARIOS

### Ver todos los usuarios
```sql
SELECT * FROM usuarios;
```

### Ver solo algunos campos
```sql
SELECT id, nombre, email, rol FROM usuarios;
```

### Contar cuántos usuarios hay
```sql
SELECT COUNT(*) as total FROM usuarios;
```

### Ver usuarios por rol
```sql
SELECT * FROM usuarios WHERE rol = 'artista';
```

### Buscar un usuario por email
```sql
SELECT * FROM usuarios WHERE email = 'juan@example.com';
```

### Ver últimos 5 usuarios registrados
```sql
SELECT * FROM usuarios ORDER BY created_at DESC LIMIT 5;
```

### Usuarios registrados en 2024
```sql
SELECT * FROM usuarios WHERE YEAR(created_at) = 2024;
```

### Cambiar el rol de un usuario
```sql
UPDATE usuarios SET rol = 'admin' WHERE email = 'juan@example.com';
```

## 🎨 CONSULTAS SOBRE OBRAS

### Ver todas las obras
```sql
SELECT * FROM obras;
```

### Ver solo obras disponibles (stock > 0)
```sql
SELECT * FROM obras WHERE stock > 0;
```

### Ver obras por tipo
```sql
SELECT * FROM obras WHERE tipo = 'original';
SELECT * FROM obras WHERE tipo = 'lamina';
SELECT * FROM obras WHERE tipo = 'digital';
```

### Ver obras ordenadas por precio
```sql
-- Precio menor a mayor
SELECT * FROM obras ORDER BY precio ASC;

-- Precio mayor a menor
SELECT * FROM obras ORDER BY precio DESC;
```

### Ver obras entre 100 y 500 euros
```sql
SELECT * FROM obras WHERE precio >= 100 AND precio <= 500;
```

### Buscar obras por nombre
```sql
SELECT * FROM obras WHERE titulo LIKE '%mar%';
-- Encuentra: "Mar azul", "Atardecer en el mar", etc.
```

### Contar cuántas obras hay por tipo
```sql
SELECT tipo, COUNT(*) as cantidad FROM obras GROUP BY tipo;
```

Resultado:
```
tipo      | cantidad
----------|----------
original  | 2
lamina    | 1
digital   | 2
```

### Ver precio promedio de las obras
```sql
SELECT AVG(precio) as precio_promedio FROM obras;
```

### Ver la obra más cara
```sql
SELECT * FROM obras ORDER BY precio DESC LIMIT 1;
```

### Ver la obra más barata
```sql
SELECT * FROM obras ORDER BY precio ASC LIMIT 1;
```

### Cambiar el precio de una obra
```sql
UPDATE obras SET precio = 250 WHERE id = 5;
```

### Cambiar el stock
```sql
UPDATE obras SET stock = stock - 1 WHERE id = 5;
-- Disminuye el stock en 1
```

### Borrar una obra
```sql
DELETE FROM obras WHERE id = 5;
```

## ❤️ CONSULTAS SOBRE FAVORITOS

### Ver todos los favoritos
```sql
SELECT * FROM favoritos;
```

### Ver cuántos favoritos tiene cada usuario
```sql
SELECT usuario_id, COUNT(*) as total FROM favoritos GROUP BY usuario_id;
```

Resultado:
```
usuario_id | total
-----------|-------
1          | 3
2          | 5
3          | 2
```

### Ver los favoritos del usuario 5
```sql
SELECT * FROM favoritos WHERE usuario_id = 5;
```

### Ver las obras favoritas del usuario 5 (con información completa)
```sql
SELECT obras.id, obras.titulo, obras.precio 
FROM obras 
JOIN favoritos ON obras.id = favoritos.obra_id 
WHERE favoritos.usuario_id = 5;
```

### Ver cuántos usuarios tienen como favorita la obra 3
```sql
SELECT COUNT(*) as total FROM favoritos WHERE obra_id = 3;
```

### Ver qué obra es la más favorita
```sql
SELECT obra_id, COUNT(*) as total 
FROM favoritos 
GROUP BY obra_id 
ORDER BY total DESC 
LIMIT 1;
```

### Añadir un favorito
```sql
INSERT INTO favoritos (usuario_id, obra_id) VALUES (5, 3);
```

### Borrar un favorito
```sql
DELETE FROM favoritos WHERE usuario_id = 5 AND obra_id = 3;
```

### Ver quién marcó como favorita la obra 7
```sql
SELECT usuarios.nombre, usuarios.email 
FROM usuarios 
JOIN favoritos ON usuarios.id = favoritos.usuario_id 
WHERE favoritos.obra_id = 7;
```

## 🔄 CONSULTAS COMBINADAS (JOIN)

### Ver todas las obras con quién las subió
```sql
SELECT obras.titulo, usuarios.nombre 
FROM obras 
JOIN usuarios ON obras.usuario_id = usuarios.id;
-- Si está disponible el campo usuario_id en obras
```

### Ver todos los favoritos de un usuario con detalles
```sql
SELECT usuarios.nombre, obras.titulo, obras.precio
FROM favoritos
JOIN usuarios ON favoritos.usuario_id = usuarios.id
JOIN obras ON favoritos.obra_id = obras.id
WHERE usuarios.id = 5;
```

Resultado:
```
nombre | titulo              | precio
-------|---------------------|--------
Juan   | Atardecer en el Mar | 150.00
Juan   | Flores Silvestres   | 120.00
Juan   | Amor Infinito       | 200.00
```

## 🗑️ MANTENIMIENTO

### Ver cuántos registros hay en cada tabla
```sql
SELECT 'usuarios' as tabla, COUNT(*) as cantidad FROM usuarios
UNION
SELECT 'obras', COUNT(*) FROM obras
UNION
SELECT 'favoritos', COUNT(*) FROM favoritos;
```

Resultado:
```
tabla      | cantidad
-----------|----------
usuarios   | 7
obras      | 5
favoritos  | 12
```

### Ver el tamaño de la base de datos
```sql
SELECT 
  SUM(data_length + index_length) / 1024 / 1024 as size_mb
FROM information_schema.tables 
WHERE table_schema = 'galeria_arte';
```

### Ver últimas modificaciones
```sql
SELECT * FROM obras ORDER BY updated_at DESC LIMIT 5;
```

### Hacer una copia de seguridad
En phpMyAdmin:
1. Haz clic en `galeria_arte`
2. Ve a "Exportar"
3. Descarga el archivo SQL

## ⚠️ OPERACIONES PELIGROSAS (¡cuidado!)

### Borrar TODOS los favoritos
```sql
DELETE FROM favoritos;
-- ⚠️ Borra TODO, no se puede deshacer
```

### Borrar TODOS los usuarios
```sql
DELETE FROM usuarios;
-- ⚠️ Borra TODO, no se puede deshacer
```

### Vaciar una tabla (borrar todo)
```sql
TRUNCATE TABLE favoritos;
-- ⚠️ Borra TODO, es más rápido que DELETE
```

## 📝 ESTADÍSTICAS ÚTILES

### Reporte de ventas (si tuvieras tabla de compras)
```sql
-- Cuántas compras tiene cada usuario
SELECT usuario_id, COUNT(*) as compras FROM compras GROUP BY usuario_id;

-- Dinero total gastado por cada usuario
SELECT usuario_id, SUM(total) as gastado FROM compras GROUP BY usuario_id;

-- Producto más vendido
SELECT obra_id, COUNT(*) as vendidas FROM carrito GROUP BY obra_id ORDER BY vendidas DESC;
```

## 🔍 BÚSQUEDAS AVANZADAS

### Buscar obras con precio y stock
```sql
SELECT * FROM obras 
WHERE tipo = 'original' 
AND precio > 100 
AND stock > 0
ORDER BY precio DESC;
```

### Ver favoritos actualizados en los últimos 7 días
```sql
SELECT * FROM favoritos 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY);
```

### Ver usuarios que se registraron hoy
```sql
SELECT * FROM usuarios 
WHERE DATE(created_at) = CURDATE();
```

### Contar favoritos por obra
```sql
SELECT 
  obras.titulo, 
  COUNT(favoritos.id) as favoritos
FROM obras 
LEFT JOIN favoritos ON obras.id = favoritos.obra_id
GROUP BY obras.id
ORDER BY favoritos DESC;
```

Resultado:
```
titulo              | favoritos
--------------------|----------
Atardecer en el Mar | 5
Flores Silvestres   | 3
Amor Infinito       | 2
Abstracciones       | 1
```

## 📚 FUNCIONES ÚTILES

### Contar
```sql
SELECT COUNT(*) FROM obras;           -- Total
SELECT COUNT(DISTINCT usuario_id) FROM favoritos;  -- Únicos
```

### Suma
```sql
SELECT SUM(stock) FROM obras;          -- Stock total
SELECT SUM(precio) FROM obras;         -- Valor total
```

### Promedio
```sql
SELECT AVG(precio) FROM obras;         -- Precio promedio
```

### Máximo y mínimo
```sql
SELECT MAX(precio) FROM obras;         -- Precio mayor
SELECT MIN(precio) FROM obras;         -- Precio menor
```

### Agrupar
```sql
SELECT tipo, COUNT(*) FROM obras GROUP BY tipo;
```

## 🎯 COMBO: CONSULTAS REALES DESDE PHP

```php
// 1. Obtener obras destacadas (las más favoritas)
$sql = "SELECT o.* FROM obras o 
        LEFT JOIN favoritos f ON o.id = f.obra_id 
        GROUP BY o.id 
        ORDER BY COUNT(f.id) DESC 
        LIMIT 3";

// 2. Obtener obras filtradas y ordenadas
$sql = "SELECT * FROM obras 
        WHERE tipo = ? AND stock > 0 
        ORDER BY precio " . ($_GET['sort'] == 'low' ? 'ASC' : 'DESC');

// 3. Obtener favoritos con detalles
$sql = "SELECT o.*, (SELECT COUNT(*) FROM favoritos WHERE obra_id = o.id) as favoritos 
        FROM obras o 
        WHERE o.id IN (SELECT obra_id FROM favoritos WHERE usuario_id = ?)";

// 4. Buscar con múltiples criterios
$sql = "SELECT * FROM obras 
        WHERE (titulo LIKE ? OR descripcion LIKE ?) 
        AND tipo IN ('original', 'lamina') 
        AND precio BETWEEN 100 AND 500";
```

---

**Tip:** Copia y pega estos comandos en phpMyAdmin para probar. ¡Aprender haciendo es mejor! 🚀
