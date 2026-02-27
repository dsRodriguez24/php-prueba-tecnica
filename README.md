# Prueba Técnica - Backend PHP (API REST para Pacientes)

Este repositorio contiene la parte no visual (backend) de la prueba técnica: una API REST en PHP que gestiona pacientes, catálogos y autenticación por JWT. El código usa PDO para acceder a MySQL, guarda imágenes en la carpeta pública y tiene migraciones/seeders incluidos.

## Estructura relevante

- `api/` — código fuente de la API (entrada en `api/public/index.php`).
- `api/public/` — carpeta pública (document root) — aquí se sirven las rutas públicas y la carpeta `uploads`.
- `api/src/` — controladores, helpers y capa de acceso a datos (PDO).
- `database/` — `migrations.sql` y `seed.php` para crear y poblar la base de datos.
- `frontend/` — (opcional) interfaz del cliente (si existe en el proyecto).

## Requisitos

- PHP 8+ (CLI) con extensiones PDO y PDO_MySQL.
- MySQL/MariaDB.
- Composer (para dependencias `firebase/php-jwt`, `phpunit` en dev).

## Instalación y preparación (Windows, usando XAMPP)

1. Coloca la carpeta del proyecto en `C:\xampp\htdocs` (por ejemplo `C:\xampp\htdocs\prueba-tecnica-php`).

2. Instala dependencias para la API (desde la raíz del proyecto):

```powershell
cd C:\xampp\htdocs\prueba-tecnica-php\api
composer install
```

3. Crear la base de datos y ejecutar migraciones:

```powershell
# desde PowerShell (ajusta usuario/contraseña según tu instalación)
mysql -u root -p < ..\database\migrations.sql
```

4. Ejecutar el seeder PHP para insertar datos de ejemplo:

```powershell
cd ..\database
php seed.php
```

5. Crear la carpeta de uploads públicas y dar permisos de escritura (si no existe):

```powershell
mkdir ..\api\public\uploads
# En Windows con XAMPP normalmente no es necesario cambiar permisos, pero asegúrate de que el proceso web pueda escribir.
```

## Ejecutar la API

Puedes ejecutar la API con el servidor embebido de PHP (útil para desarrollo). Debes iniciar el servidor desde la carpeta `api` y apuntar el document root a `public`:

```powershell
# Desde C:\xampp\htdocs\prueba-tecnica-php\api
php -S localhost:8000 -t public
```

Después de esto la API estará accesible en:

- `http://localhost:8000/api/v1/...` para los endpoints de la API

Si prefieres usar Apache (XAMPP), copia la carpeta `prueba-tecnica-php` dentro de `C:\xampp\htdocs` y accede via `http://localhost/prueba-tecnica-php/api/public/` o configura un VirtualHost que apunte a `C:\xampp\htdocs\prueba-tecnica-php\api\public`.

## Frontend

La carpeta `frontend/` (si existe) contiene la parte visual. Para usarla:

- Si es una aplicación estática: abre la carpeta `frontend` en tu editor o navegador.
- Si tiene su propio servidor de desarrollo (por ejemplo `npm`/`yarn`), entra en `frontend` e instala/ejecuta:

```powershell
cd C:\xampp\htdocs\prueba-tecnica-php\frontend
npm install
npm run dev
```

Luego apunta el frontend a la URL de la API (`http://localhost:8000/api/v1`).

## Notas rápidas

- Configuración de la base de datos y JWT: edita `api/config.php` según tus credenciales.
- Archivos subidos por la API se guardan en `api/public/uploads` y se sirven públicamente en `/uploads/<archivo>`.
- Para ejecutar pruebas unitarias (si están disponibles):

