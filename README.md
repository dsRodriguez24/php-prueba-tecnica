# Pacientes - Backend (PHP)

Instrucciones básicas:

- Inicializar servidor XAMPP
- Instalar dependencias (desde la carpeta api): `composer install`
- Crear la base de datos MySQL `prueba_tecnica` (o ajustar `.env` / `config.php`).
- Ejecutar el seeder: `php database/seed.php` (crea tablas y datos de prueba).
- Iniciar servidor PHP en `public/`: `php -S localhost:8000 -t public`
- Mostrar el front en  PHP en `http://localhost/prueba-tecnica-php`


Endpoints principales (prefijo base: `/api/v1`):
- `POST /api/v1/login` {email,password} -> token JWT
- `GET /api/v1/patients` -> lista (requiere Authorization: Bearer <token>)
- `POST /api/v1/patients` -> crear paciente
- `GET /api/v1/patients/{id}` -> ver paciente
- `PUT /api/v1/patients/{id}` -> actualizar
- `DELETE /api/v1/patients/{id}` -> eliminar

Tests:
- `vendor/bin/phpunit` (instalar deps antes)
