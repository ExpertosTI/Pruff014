# Warehouse API

API REST para gestión de almacenes hecha con Laravel 9 y PHP 8.

## Qué hace

Básicamente maneja clientes, artículos y usuarios. CRUD completo para todo, con validaciones, tests y autenticación con Sanctum.

## Autenticación

La API usa Laravel Sanctum para autenticación basada en tokens.

### Registro
```bash
POST /api/register
{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "cedula": "001-1234567-8",
    "phone_number": "809-123-4567",
    "blood_type": "O+"
}
```

### Login
```bash
POST /api/login
{
    "email": "juan@example.com",
    "password": "password123"
}
```

### Logout
```bash
POST /api/logout
Authorization: Bearer {token}
```

### Perfil del usuario
```bash
GET /api/me
Authorization: Bearer {token}
```

## Endpoints

**Nota:** Todos los endpoints (excepto register y login) requieren autenticación con Bearer token.

### Clientes
- `GET /api/clients` - Lista todos
- `POST /api/clients` - Crea uno nuevo
- `GET /api/clients/{id}` - Ve uno específico
- `PUT /api/clients/{id}` - Actualiza
- `DELETE /api/clients/{id}` - Borra

### Artículos
- `GET /api/articles` - Lista todos
- `POST /api/articles` - Crea uno nuevo
- `GET /api/articles/{id}` - Ve uno específico
- `PUT /api/articles/{id}` - Actualiza
- `DELETE /api/articles/{id}` - Borra

### Usuarios
- `GET /api/users` - Lista todos
- `POST /api/users` - Crea uno nuevo
- `GET /api/users/{id}` - Ve uno específico
- `PUT /api/users/{id}` - Actualiza
- `DELETE /api/users/{id}` - Borra

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

## Tests

```bash
php artisan test
```

Ahora incluye tests de autenticación. Todos los tests cubren CRUD, validaciones, errores 404/422 y autenticación.

## Estructura

- Controllers en `app/Http/Controllers`
- Requests para validación en `app/Http/Requests`
- Resources para JSON en `app/Http/Resources`
- Models en `app/Models`
- Tests en `tests/Feature`

## Notas técnicas

- Form Requests para todas las validaciones
- API Resources para respuestas consistentes
- Fat models, skinny controllers
- Sanctum para autenticación con tokens
- Migraciones con seeders
- Todas las rutas protegidas excepto register/login

Eso es todo. Funciona con autenticación completa.
