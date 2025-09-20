# 🏪 API RESTful para Gestión de Almacenes

> **Una API completa y robusta para la gestión integral de almacenes, desarrollada con Laravel 9.x y PHP 8.x**

[![PHP Version](https://img.shields.io/badge/PHP-8.x-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-9.x-red.svg)](https://laravel.com)
[![Tests](https://img.shields.io/badge/Tests-22%20Passed-green.svg)](#testing)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

## 📋 Descripción del Proyecto

Esta API RESTful proporciona una solución completa para la gestión de almacenes, permitiendo el manejo eficiente de:

- **👥 Clientes** - Gestión completa de información de clientes
- **📦 Artículos** - Control de inventario y productos
- **👨‍💼 Empleados** - Administración de personal del almacén
- **🛒 Órdenes de Venta** - Procesamiento de pedidos y ventas

## 🚀 Características Principales

### ✨ Funcionalidades Core
- **CRUD Completo** para todas las entidades
- **Validación Robusta** con Form Requests personalizados
- **API Resources** para respuestas JSON consistentes
- **Autenticación** con Laravel Sanctum
- **Testing Completo** con 22 tests automatizados

### 🏗️ Arquitectura
- **Fat Models, Skinny Controllers** - Lógica de negocio en modelos
- **Principios SOLID** aplicados consistentemente
- **Convenciones Laravel** seguidas estrictamente
- **Código Auto-documentado** con nombres descriptivos

## 🛠️ Tecnologías Utilizadas

| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| **PHP** | 8.x | Lenguaje base |
| **Laravel** | 9.x | Framework principal |
| **MySQL** | 8.0+ | Base de datos |
| **PHPUnit** | 9.x | Testing |
| **Composer** | 2.x | Gestión de dependencias |

## 📚 Endpoints de la API

### 👥 Clientes (`/api/clients`)
```
GET    /api/clients          # Listar todos los clientes
POST   /api/clients          # Crear nuevo cliente
GET    /api/clients/{id}     # Obtener cliente específico
PUT    /api/clients/{id}     # Actualizar cliente
DELETE /api/clients/{id}     # Eliminar cliente
```

### 📦 Artículos (`/api/articles`)
```
GET    /api/articles         # Listar todos los artículos
POST   /api/articles         # Crear nuevo artículo
GET    /api/articles/{id}    # Obtener artículo específico
PUT    /api/articles/{id}    # Actualizar artículo
DELETE /api/articles/{id}    # Eliminar artículo
```

### 👨‍💼 Usuarios/Empleados (`/api/users`)
```
GET    /api/users            # Listar todos los usuarios
POST   /api/users            # Crear nuevo usuario
GET    /api/users/{id}       # Obtener usuario específico
PUT    /api/users/{id}       # Actualizar usuario
DELETE /api/users/{id}       # Eliminar usuario
```

## 🚀 Instalación y Configuración

### Prerrequisitos
- PHP 8.0 o superior
- Composer
- MySQL 8.0+
- Git

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone [URL_DEL_REPOSITORIO]
cd warehouse-api
```

2. **Instalar dependencias**
```bash
composer install
```

3. **Configurar entorno**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar base de datos**
```bash
# Editar .env con tus credenciales de BD
php artisan migrate --seed
```

5. **Ejecutar tests**
```bash
php artisan test
```

## 🧪 Testing

El proyecto incluye una suite completa de tests:

- **22 Tests Automatizados** ✅
- **Cobertura CRUD Completa** para todas las entidades
- **Tests de Validación** (422 errors)
- **Tests de Errores** (404 not found)
- **Tiempo de ejecución**: ~0.32s

### Ejecutar Tests
```bash
# Todos los tests
php artisan test

# Tests con detalles
php artisan test --verbose

# Tests específicos
php artisan test --filter=ArticleControllerTest
```

## 📁 Estructura del Proyecto

```
warehouse-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controladores API
│   │   ├── Requests/        # Validaciones
│   │   └── Resources/       # Transformadores JSON
│   └── Models/              # Modelos Eloquent
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/            # Datos de prueba
├── tests/
│   ├── Feature/            # Tests de integración
│   └── Unit/               # Tests unitarios
└── docs/                   # Documentación
```

## 🔒 Seguridad

- **Validación de entrada** en todos los endpoints
- **Sanitización de datos** automática
- **Protección CSRF** habilitada
- **Headers de seguridad** configurados
- **Autenticación por tokens** con Sanctum

## 🤝 Contribución

Este proyecto sigue las mejores prácticas de desarrollo:

1. **Código limpio y legible**
2. **Tests para nuevas funcionalidades**
3. **Documentación actualizada**
4. **Commits descriptivos**

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

---

**Desarrollado con ❤️ por el equipo de renace.tech**

> *"El código es como el humor. Cuando tienes que explicarlo, es malo."* - Cory House
