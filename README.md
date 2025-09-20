# Warehouse API

API REST para gestión de almacenes hecha con Laravel 9 y PHP 8.

## Qué hace

Básicamente maneja clientes, artículos y usuarios. CRUD completo para todo, con validaciones, tests y autenticación con Sanctum.

## Autenticación

La API usa **Laravel Sanctum** para autenticación basada en tokens. Implementación completa con registro, login, logout y protección de rutas.

### Registro de Usuario
```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "cedula": "001-1234567-8",
    "phone_number": "809-123-4567",
    "blood_type": "O+"
  }'
```

**Respuesta:**
```json
{
  "message": "Usuario registrado exitosamente",
  "user": { ... },
  "token": "1|abc123..."
}
```

### Login
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "juan@example.com",
    "password": "password123"
  }'
```

**Respuesta:**
```json
{
  "message": "Inicio de sesión exitoso",
  "user": { ... },
  "token": "2|xyz789..."
}
```

### Logout
```bash
curl -X POST http://127.0.0.1:8000/api/logout \
  -H "Authorization: Bearer {token}"
```

### Perfil del Usuario
```bash
curl -X GET http://127.0.0.1:8000/api/me \
  -H "Authorization: Bearer {token}"
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

- **Laravel Sanctum** implementado para autenticación con tokens
- Form Requests para todas las validaciones
- API Resources para respuestas consistentes
- Fat models, skinny controllers
- Migraciones con seeders
- Todas las rutas protegidas excepto register/login
- **32 tests pasando** incluyendo autenticación, CRUD, validaciones y errores
- Middleware de autenticación configurado correctamente
- Tokens seguros con invalidación en logout

## Estado del Proyecto

✅ **API REST Completa** - Todos los endpoints CRUD implementados  
✅ **Autenticación Sanctum** - Registro, login, logout funcionando  
✅ **Validaciones** - Form Requests en todos los endpoints  
✅ **Tests Completos** - 32/32 tests pasando  
✅ **Seguridad** - Rutas protegidas, tokens seguros  
✅ **Documentación** - README actualizado con ejemplos  

La API está **lista para producción** y cumple con todos los requisitos técnicos.
