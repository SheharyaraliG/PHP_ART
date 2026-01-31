# 🗺️ MAPA MENTAL - PHP_ART

## FLUJO VISUAL COMPLETO

```
                        ┌─────────────────┐
                        │  USUARIO ABRE   │
                        │ NAVEGADOR       │
                        │ http://localhost│
                        │    /PHP_ART     │
                        └────────┬────────┘
                                 │
                        ┌────────▼──────────┐
                        │  ¿LOGADO?         │
                        └────────┬──────────┘
                    YES /        │        \ NO
                      /          │         \
            ┌─────────▼──┐   ┌──▼──────────────┐
            │ $_SESSION  │   │  MOSTRAR        │
            │ existe     │   │  LOGIN/REGISTRO │
            └─────┬──────┘   └────────────────┘
                  │
        ┌─────────┴─────────┐
        │                   │
   CLICK EN    ┌────────────────┐     OTROS
   "COLECCIÓN" │ OBRAS/LISTAR   │
        │      │  .PHP          │
        │      │                │
        │      │ • Ver todas    │
        │      │ • Filtrar      │
        │      │ • Favoritos    │
        │      └────────────────┘
        │
        │      ┌────────────────┐
        └─────▶│  BUSCAR.PHP    │
               │                │
               │ • Buscar texto │
               │ • Mostrar      │
               │   resultados   │
               └────────────────┘

   CLICK EN ❤
        │
        ▼
   ┌────────────────────┐
   │ FAVORITOS/         │
   │ ACCION.PHP         │
   │                    │
   │ INSERT / DELETE    │
   │ EN BD              │
   └──────────┬─────────┘
              │
              ▼
         BD ACTUALIZA
         (SQL INSERT/DELETE)
              │
              ▼
         ❤ CAMBIA DE COLOR
```

---

## 🔐 CICLO DE AUTENTICACIÓN

```
USUARIO ENTRA CREDENCIALES
         │
         ▼
    ┌─────────────┐
    │ REGISTRO?   │
    └──┬──────┬───┘
       │      │
      SÍ     NO
       │      │
       │      ▼
       │   BUSCAR EN BD
       │   SELECT * FROM
       │   usuarios WHERE
       │   email = ?
       │      │
       │      ├─ ¿Existe?
       │      │   ¿Contraseña correcta?
       │      │
       │      ├─ SÍ → CREAR $_SESSION
       │      │        → SET COOKIE
       │      │        → REDIRIGIR HOME
       │      │
       │      └─ NO → ERROR
       │             "Credenciales incorrectas"
       │
       ▼
    ┌──────────────────┐
    │ VALIDAR ENTRADA  │
    │ • Email válido?  │
    │ • Password?      │
    │ • Nombre?        │
    └────────┬─────────┘
             │
             ├─ FALLA → ERROR
             │         "Validación"
             │
             └─ OK → BUSCAR EMAIL
                    DUPLICADO
                    │
                    ├─ SÍ → ERROR
                    │       "Email
                    │        existe"
                    │
                    └─ NO → ENCRIPTAR
                            PASSWORD
                            │
                            ▼
                        INSERT EN BD
                            │
                            ▼
                        ÉXITO → LOGIN
```

---

## 📊 ESTRUCTURA DE DATOS

```
                    galeria_arte
                    ┌──────────────┐
                    │   BASE DE    │
                    │  DATOS       │
                    └───┬──────┬───┘
                        │      │
            ┌───────────┘      └──────────┐
            │                             │
            ▼                             ▼
    ┌──────────────┐            ┌──────────────┐
    │   usuarios   │            │    obras     │
    ├──────────────┤            ├──────────────┤
    │ id (PK)      │            │ id (PK)      │
    │ nombre       │            │ titulo       │
    │ email        │            │ descripcion  │
    │ password     │            │ tipo         │
    │ rol          │            │ precio       │
    └──────┬───────┘            │ stock        │
           │                    │ imagen       │
           │                    └──────┬───────┘
           │                           │
           │        ┌──────────────────┘
           │        │
           │        │ MANY TO MANY
           │        │ (N a N)
           │        │
           └────────┼──────────────────┐
                    │                  │
                    ▼                  ▼
            ┌──────────────┐    
            │  favoritos   │    
            ├──────────────┤    
            │ id (PK)      │    
            │ usuario_id   │◀──┐FK
            │ obra_id      │◀──┐FK
            │ created_at   │   │
            └──────────────┘   │
                                │
                    ┌───────────┘
                    │
         Relaciones:
         • 1 usuario puede tener
           muchos favoritos
         • 1 obra puede estar en
           muchos favoritos
         • Cuando borro usuario
           → DELETE cascada
         • Cuando borro obra
           → DELETE cascada
```

---

## 🎯 FLUJO DE PÁGINAS

```
                    INDEX.PHP
                  (Página Principal)
                        │
        ┌───────────────┼───────────────┐
        │               │               │
        ▼               ▼               ▼
      HOME         COLECCIÓN         BUSCAR
      PAGE         (Listar)           PAGE
        │          (Filtrar)           │
        │               │              │
        │               │              │
        │     ┌─────────┼──────────┐   │
        │     │         │          │   │
        │     ▼         ▼          ▼   │
        │   TIPO:    TIPO:      TIPO:  │
        │  Original  Lámina    Digital │
        │     │         │          │   │
        │     └─────────┼──────────┘   │
        │               │              │
        │               ├─ CLICK ❤     │
        │               │              │
        │               ▼              │
        │          FAVORITOS/          │
        │          ACCION.PHP          │
        │          (INSERT/DELETE)     │
        │               │              │
        │               ▼              │
        │          FAVORITOS/          │
        │          VER.PHP             │
        │          (Mis Favoritos)     │
        │               │              │
        └───────────────┼──────────────┘
                        │
                ┌───────┴───────┐
                │               │
            AUTH/           AUTH/
            LOGIN.PHP       REGISTRO.PHP
                │               │
                └───────┬───────┘
                        │
                    AUTH/
                    LOGOUT.PHP
```

---

## 💻 FLUJO DE CÓDIGO CUANDO ENTRA EN /obras/listar.php

```
┌─────────────────────────────────────┐
│  USUARIO ABRE /obras/listar.php     │
└────────────────┬────────────────────┘
                 │
            ┌────▼────┐
            │ PHP     │
            │ RUN     │
            └────┬────┘
                 │
    ┌────────────┴────────────┐
    │                         │
 SESSION             INCLUIR DATABASE
 START()             CONNECTION
    │                         │
    │        ┌────────────────┼─────────────┐
    │        │                │             │
    │        │         ┌──────▼────┐        │
    │        │         │  $pdo     │        │
    │        │         │  CONEXIÓN │        │
    │        │         │  A BD     │        │
    │        │         └───────────┘        │
    │        │                              │
    │    ┌───▼──────────────────────┐       │
    │    │ RECOGER $_GET['tipo']    │       │
    │    │ Filtro opcional          │       │
    │    └───┬──────────────────────┘       │
    │        │                              │
    │        ▼                              │
    │    CONSTRUIR SQL                      │
    │    SELECT * FROM obras                │
    │    WHERE 1=1                          │
    │                                       │
    │    if ($tipo):                        │
    │        AND tipo = ?                   │
    │                                       │
    │    ORDER BY created_at DESC           │
    │        │                              │
    │        ▼                              │
    │    $stmt = $pdo->prepare($sql)        │
    │    $stmt->execute([$tipo])            │
    │        │                              │
    │        ▼                              │
    │    $obras = $stmt->fetchAll()         │
    │                                       │
    └───────────┬──────────────────────────┘
                │
            ┌───▼────────┐
            │  foreach   │
            │  $obras    │
            │            │
            │ MOSTRAR    │
            │ <div       │
            │ class=     │
            │ "card">    │
            │   IMAGE    │
            │   TITLE    │
            │   PRICE    │
            │   HEART    │
            │ </div>     │
            └────────────┘
```

---

## 🔄 CICLO DE VIDA DE UN FAVORITO

```
USUARIO LOGADO
      │
      ▼
   VE OBRA
   ID=5
      │
      ├─ ¿Está en favoritos?
      │
      ├─ NO (corazón gris)
      │   │
      │   ▼
      │ CLICK ❤
      │   │
      │   ▼
      │ LINK: favoritos/accion.php?id=5&action=add
      │   │
      │   ▼
      │ SESSION = 2 (usuario_id)
      │   │
      │   ▼
      │ INSERT INTO favoritos
      │ (usuario_id=2, obra_id=5)
      │   │
      │   ▼
      │ REDIRECT (back)
      │   │
      │   ▼
      │ ❤ CAMBIA A ROJO
      │
      ├─ SÍ (corazón rojo)
      │   │
      │   ▼
      │ CLICK ❤
      │   │
      │   ▼
      │ LINK: favoritos/accion.php?id=5&action=remove
      │   │
      │   ▼
      │ SESSION = 2
      │   │
      │   ▼
      │ DELETE FROM favoritos
      │ WHERE usuario_id=2 AND obra_id=5
      │   │
      │   ▼
      │ REDIRECT (back)
      │   │
      │   ▼
      │ ❤ CAMBIA A GRIS
      │
      ▼
   FIN
```

---

## 📚 DOCUMENTACIÓN MAPA

```
        BIENVENIDA.md (INICIO AQUÍ)
              │
    ┌─────────┴─────────┐
    │                   │
INICIO_RAPIDO    README.md
(5 MIN)          (10 MIN)
    │                   │
    │                   │
    ▼                   ▼
INSTALAR         ENTENDER PROYECTO
Y PROBAR              │
    │          ┌──────┴──────┐
    │          │             │
    │    GUIA_FLUJO     RESUMEN_VISUAL
    │    (paso a paso)  (diagramas)
    │          │             │
    │          └──────┬──────┘
    │                 │
    │                 ▼
    │          LEER CÓDIGO
    │          Y COMENTARIOS
    │                 │
    │     ┌───────────┼───────────┐
    │     │           │           │
    │  ¿BD?       ¿SQL?      ¿ERROR?
    │     │           │           │
    │     ▼           ▼           ▼
    │  GUIA_    COMANDOS_   SOLUCION_
    │  BASE_    SQL.md      PROBLEMAS
    │  DATOS                .md
    │     │           │           │
    │     └───────────┴───────────┘
    │                 │
    │                 ▼
    │         DOMINAS PHP_ART
    │                 │
    │                 ▼
    │         EXPANDIR Y CREAR
    │         NUEVAS FEATURES
    │
    └──────────────────┘
       LISTO PARA APRENDER
```

---

## 🔐 SEGURIDAD EN CAPAS

```
        ENTRADA DE USUARIO
              │
              ▼
    ┌─────────────────────┐
    │ VALIDACIÓN          │
    │ • Email válido?     │
    │ • No vacío?         │
    │ • Formato correcto? │
    └────────┬────────────┘
             │
             ▼
    ┌─────────────────────┐
    │ SANITIZACIÓN        │
    │ • trim()            │
    │ • htmlspecialchars()│
    │ • Remover espacios  │
    └────────┬────────────┘
             │
             ▼
    ┌─────────────────────┐
    │ PREPARACIÓN SQL     │
    │ • Prepared Statement│
    │ • Placeholders ?    │
    │ • Parámetros arrays │
    └────────┬────────────┘
             │
             ▼
    ┌─────────────────────┐
    │ ENCRIPTACIÓN        │
    │ (solo contraseña)   │
    │ • password_hash()   │
    │ • PASSWORD_DEFAULT  │
    │ • Bcrypt            │
    └────────┬────────────┘
             │
             ▼
    ┌─────────────────────┐
    │ EJECUCIÓN EN BD     │
    │ • execute()         │
    │ • Parámetros aparte │
    │ • No interpolación  │
    └────────┬────────────┘
             │
             ▼
         BD SEGURA
```

---

## 🎯 TABLA DE CONTENIDOS VISUAL

```
ARCHIVOS PHP
├── config/database.php        (Conexión)
├── auth/login.php             (Entrar)
├── auth/registro.php          (Registro)
├── auth/logout.php            (Salir)
├── obras/listar.php           (Ver obras)
├── obras/crear.php            (Crear)
├── obras/editar.php           (Editar)
├── obras/eliminar.php         (Eliminar)
├── buscar.php                 (Búsqueda)
├── favoritos/ver.php          (Mis favoritos)
├── favoritos/accion.php       (Acción)
├── index.php                  (Inicio)
└── header.php                 (Menú)

DOCUMENTACIÓN
├── BIENVENIDA.md              (Empieza aquí)
├── INICIO_RAPIDO.md           (5 pasos)
├── README.md                  (Guía general)
├── INDICE.md                  (Índice)
├── GUIA_FLUJO.md              (Flujo)
├── GUIA_BASE_DATOS.md         (Base de datos)
├── COMANDOS_SQL.md            (SQL)
├── RESUMEN_VISUAL.md          (Diagramas)
├── SOLUCION_PROBLEMAS.md      (Errores)
├── RESUMEN_FINAL.md           (Conclusión)
├── EJECUTIVO.md               (Resumen)
└── (Este archivo)             (Mapa mental)

DATOS
├── database.sql               (BD)
└── fotos/                     (Imágenes)
```

---

## 🚀 RUTA RECOMENDADA

```
NUEVOS EN PHP:
1. BIENVENIDA.md      (2 min)
2. INICIO_RAPIDO.md   (5 min)
3. README.md          (10 min)
4. INSTALAR
5. PROBAR
6. RESUMEN_VISUAL.md  (10 min)
7. GUIA_FLUJO.md      (30 min)
8. LEER CÓDIGO        (1-2 horas)
9. GUIA_BASE_DATOS.md (20 min)
10. EXPERIMENTAR

EXPERIENCIA:
1. README.md          (5 min)
2. GUIA_FLUJO.md      (20 min)
3. LEER CÓDIGO        (30 min)
4. EXPANDIR

PROBLEMAS:
1. SOLUCION_PROBLEMAS.md
2. EJECUTAR COMANDOS SQL
3. VER COMENTARIOS EN CÓDIGO
```

---

<div align="center">

## 🗺️ BRÚJULA DE NAVEGACIÓN

**¿Dónde estás? Elige tu dirección:**

[BIENVENIDA](BIENVENIDA.md) → [INICIO_RAPIDO](INICIO_RAPIDO.md) → [README](README.md) → [GUIA_FLUJO](GUIA_FLUJO.md)

</div>

---

**Este mapa mental te ayuda a visualizar toda la estructura de PHP_ART**
