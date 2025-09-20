# Warehouse API

API RESTful completa desarrollada con Laravel 9.x y PHP 8.x para gestión integral de almacenes. Incluye CRUD completo para clientes, artículos, usuarios, colocaciones (placements), empleados y compras (purchases).

**Desarrollado por:** Adderly Marte  
**Prueba Técnica:** Sistema de Gestión de Almacenes

## Funcionalidades Principales

✅ **CRUD Completo** para todas las entidades  
✅ **Autenticación** con Laravel Sanctum  
✅ **Validaciones robustas** con Form Requests  
✅ **Filtros avanzados** en todos los endpoints  
✅ **Paginación automática** (15 elementos por página)  
✅ **Lógica de negocio avanzada** (acumulación de compras)  
✅ **61 pruebas funcionales** con 100% de éxito  
✅ **Arquitectura limpia** siguiendo principios SOLID

## Autenticación

Laravel Sanctum con tokens. Registro, login, logout y rutas protegidas.

### Registro
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Usuario Ejemplo",
    "email": "usuario@correo.com", 
    "password": "password123",
    "password_confirmation": "password123",
    "cedula": "001-1234567-8",
    "phone_number": "809-123-4567",
    "blood_type": "O+"
  }'
```

Te devuelve:
```json
{
  "message": "Usuario registrado exitosamente",
  "user": { ... },
  "token": "1|abc123..."
}
```

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "usuario@correo.com",
    "password": "password123"
  }'
```

Respuesta:
```json
{
  "message": "Inicio de sesión exitoso",
  "user": { ... },
  "token": "2|xyz789..."
}
```

### Logout
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Obtener Usuario Actual
```bash
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Endpoints Disponibles

Todos los endpoints requieren autenticación con token Bearer (excepto register/login).

### 🔐 Autenticación
- `POST /api/register` - Registro de usuario
- `POST /api/login` - Inicio de sesión
- `POST /api/logout` - Cerrar sesión
- `GET /api/me` - Perfil del usuario autenticado

### 📦 Artículos
- `GET /api/articles` - Lista con paginación (15/página)
- `GET /api/articles?manufacturer=Sony` - Filtro por fabricante
- `GET /api/articles?description=Android` - Filtro por descripción
- `POST /api/articles` - Crear artículo
- `GET /api/articles/{id}` - Ver artículo específico
- `PUT /api/articles/{id}` - Actualizar artículo
- `DELETE /api/articles/{id}` - Eliminar artículo

### 👥 Clientes
- `GET /api/clients` - Listar clientes
- `POST /api/clients` - Crear cliente
- `GET /api/clients/{id}` - Ver cliente específico
- `PUT /api/clients/{id}` - Actualizar cliente
- `DELETE /api/clients/{id}` - Eliminar cliente

### 👤 Usuarios
- `GET /api/users` - Listar usuarios
- `POST /api/users` - Crear usuario
- `GET /api/users/{id}` - Ver usuario específico
- `PUT /api/users/{id}` - Actualizar usuario
- `DELETE /api/users/{id}` - Eliminar usuario

### 📍 Colocaciones (Placements)
- `GET /api/placements` - Lista con paginación y filtros
- `GET /api/placements?article_id=1` - Filtro por artículo
- `GET /api/placements?location=Almacén` - Filtro por ubicación
- `GET /api/placements?min_price=100&max_price=500` - Filtro por rango de precio
- `POST /api/placements` - Crear colocación
- `GET /api/placements/{id}` - Ver colocación específica
- `PUT /api/placements/{id}` - Actualizar colocación
- `DELETE /api/placements/{id}` - Eliminar colocación

### 🛒 Compras (Purchases)
- `GET /api/purchases` - Lista con paginación y filtros
- `GET /api/purchases?client_id=1` - Filtro por cliente
- `GET /api/purchases?start_date=2025-01-01&end_date=2025-01-31` - Filtro por fechas
- `GET /api/purchases?min_quantity=10` - Filtro por cantidad mínima
- `POST /api/purchases` - Crear/Acumular compra
- `GET /api/purchases/{id}` - Ver compra específica
- `PUT /api/purchases/{id}` - Actualizar compra
- `DELETE /api/purchases/{id}` - Eliminar compra

**Nota especial:** El endpoint de creación de compras incluye lógica de acumulación automática. Si ya existe una compra para el mismo cliente, artículo y colocación, se acumula la cantidad en lugar de crear un registro duplicado.

## Instalación y Configuración

### Requisitos
- PHP 8.x
- Composer
- MySQL o SQLite

### Pasos de instalación

1. **Clonar e instalar dependencias:**
```bash
git clone <repository-url>
cd warehouse-api
composer install
```

2. **Configurar entorno:**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Configurar base de datos en `.env`:**
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=warehouse_api
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

4. **Ejecutar migraciones:**
```bash
php artisan migrate --seed
```

5. **Iniciar servidor:**
```bash
php artisan serve
```

La API estará disponible en: `http://localhost:8000`

## Testing

Ejecutar todas las pruebas:
```bash
php artisan test
```

Ejecutar pruebas específicas:
```bash
php artisan test --filter=PlacementControllerTest
php artisan test --filter=PurchaseControllerTest
```

**Cobertura actual:** 61 pruebas funcionales con 100% de éxito, cubriendo:
- ✅ CRUD completo para todas las entidades
- ✅ Validaciones y manejo de errores
- ✅ Autenticación y autorización
- ✅ Filtros y paginación
- ✅ Lógica de negocio especial (acumulación de compras)

## Arquitectura y Estructura

### Organización del código
```
app/
├── Http/
│   ├── Controllers/          # Controladores API (lógica mínima)
│   ├── Requests/            # Validaciones con Form Requests
│   └── Resources/           # Transformación de respuestas JSON
├── Models/                  # Modelos Eloquent (lógica de negocio)
└── Providers/              # Service Providers

database/
├── factories/              # Factories para testing
├── migrations/             # Migraciones de base de datos
└── seeders/               # Seeders para datos iniciales

tests/
└── Feature/               # Pruebas funcionales de endpoints
```

### Principios aplicados
- **Fat Models, Skinny Controllers:** Lógica de negocio en modelos
- **SOLID Principles:** Especialmente Single Responsibility
- **Form Requests:** Validaciones centralizadas
- **API Resources:** Respuestas JSON consistentes
- **Eager Loading:** Optimización de consultas
- **Repository Pattern:** Abstracción de acceso a datos

## Stack Tecnológico

### Backend
- **Framework:** Laravel 9.x
- **Lenguaje:** PHP 8.x
- **Base de datos:** MySQL/SQLite
- **Autenticación:** Laravel Sanctum (Token-based)
- **Testing:** PHPUnit
- **Gestión de dependencias:** Composer

### Características técnicas
- ✅ **API RESTful** siguiendo estándares HTTP
- ✅ **Autenticación stateless** con tokens
- ✅ **Validaciones robustas** en cada endpoint
- ✅ **Paginación automática** (15 elementos por página)
- ✅ **Filtros dinámicos** en listados
- ✅ **Manejo de errores** consistente
- ✅ **Testing completo** con 61 pruebas

## Estado del Proyecto

### ✅ Completado
- API REST completa para 6 entidades
- Sistema de autenticación con Sanctum
- Validaciones exhaustivas
- 61 pruebas funcionales (100% éxito)
- Filtros avanzados y paginación
- Lógica de negocio especializada
- Documentación completa

### 🎯 Listo para
- Despliegue en producción
- Integración con frontend
- Escalabilidad horizontal
- Mantenimiento y extensiones

---

**Desarrollado por Adderly Marte**  
*Prueba Técnica - Sistema de Gestión de Almacenes*
