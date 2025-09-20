# Warehouse API

Una API REST que hice para manejar almacenes. Está hecha con Laravel 9 y PHP 8.

## Qué hace esto

Maneja clientes, artículos y usuarios. Tiene CRUD para todo, validaciones y autenticación. Nada del otro mundo pero funciona bien.

## Autenticación

Implementé **Laravel Sanctum** para la autenticación con tokens. Tiene registro, login, logout y las rutas están protegidas.

### Registro
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

Te devuelve algo así:
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

Y te da:
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

### Ver tu perfil
```bash
curl -X GET http://127.0.0.1:8000/api/me \
  -H "Authorization: Bearer {token}"
```

## Los endpoints

**Importante:** Todos necesitan el token (excepto register y login).

### Clientes
- `GET /api/clients` - Los lista todos
- `POST /api/clients` - Crea uno nuevo  
- `GET /api/clients/{id}` - Ve uno específico
- `PUT /api/clients/{id}` - Lo actualiza
- `DELETE /api/clients/{id}` - Lo borra

### Artículos  
- `GET /api/articles` - Los lista todos
- `POST /api/articles` - Crea uno nuevo
- `GET /api/articles/{id}` - Ve uno específico
- `PUT /api/articles/{id}` - Lo actualiza
- `DELETE /api/articles/{id}` - Lo borra

### Usuarios
- `GET /api/users` - Los lista todos
- `POST /api/users` - Crea uno nuevo
- `GET /api/users/{id}` - Ve uno específico  
- `PUT /api/users/{id}` - Lo actualiza
- `DELETE /api/users/{id}` - Lo borra

## Cómo instalarlo

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

Tiene tests para todo. CRUD, validaciones, errores y autenticación. 32 tests en total y todos pasan.

## Estructura del proyecto

- Controllers en `app/Http/Controllers`
- Requests para validar en `app/Http/Requests`  
- Resources para el JSON en `app/Http/Resources`
- Models en `app/Models`
- Tests en `tests/Feature`

## Cosas técnicas

- Laravel Sanctum para autenticación
- Form Requests para validar todo
- API Resources para que las respuestas sean consistentes
- Fat models, skinny controllers (como debe ser)
- Migraciones con seeders
- Rutas protegidas (excepto register/login)
- 32 tests que pasan todos
- Middleware configurado bien
- Los tokens se invalidan cuando haces logout

## Estado actual

✅ API REST completa - Todos los endpoints funcionan  
✅ Autenticación con Sanctum - Registro, login, logout  
✅ Validaciones - Form Requests en todos lados  
✅ Tests - 32/32 pasando  
✅ Seguridad - Rutas protegidas, tokens seguros  
✅ Documentación - Este README  

Ya está lista para usar.
