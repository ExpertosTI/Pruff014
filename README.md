# Warehouse API

API REST para gestión de almacenes hecha con Laravel 9 y PHP 8.

## Qué hace

Básicamente maneja clientes, artículos y usuarios. CRUD completo para todo, con validaciones y tests.

## Endpoints

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

22 tests, todos pasan. Cubren CRUD, validaciones y errores 404/422.

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
- Sanctum para auth (aunque no lo uso en los tests)
- Migraciones con seeders

Eso es todo. Funciona.
