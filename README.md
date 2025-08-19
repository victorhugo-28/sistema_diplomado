# 🏥 Sistema de Gestión de Citas con Inventario

Sistema completo de gestión de citas médicas con módulo de inventario desarrollado con **FastAPI** (backend) y **Laravel** (frontend).

## 📋 Índice

- [Características](#-características)
- [Arquitectura](#️-arquitectura)
- [Tecnologías](#-tecnologías)
- [Requisitos](#-requisitos)
- [Instalación](#-instalación)
  - [Backend (FastAPI)](#backend-fastapi)
  - [Frontend (Laravel)](#frontend-laravel)
- [Uso](#-uso)
- [API Endpoints](#-api-endpoints)
- [Testing](#-testing)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Contribución](#-contribución)

## ✨ Características

### 🏥 Gestión de Citas
- Administración de usuarios y roles
- Gestión de clientes
- Sistema de citas médicas

### 📦 Módulo de Inventario
- Gestión de artículos y tipos de artículos
- Control de proveedores
- Registro de compras y ventas
- Control de stock en tiempo real

### 🧪 Calidad de Software
- **26 pruebas unitarias** con 100% de cobertura
- Arquitectura limpia (Clean Architecture)
- Patrón Repository para acceso a datos
- Documentación automática con Swagger

## 🏗️ Arquitectura

```
Sistema de Gestión de Citas
├── Backend (FastAPI + SQLAlchemy)
│   ├── Endpoints (Controllers)
│   ├── Handlers (Business Logic)
│   ├── Repositories (Data Access)
│   └── Models (Domain Entities)
└── Frontend (Laravel + Blade)
    ├── Controllers
    ├── Views
    └── Routes
```

## 🛠 Tecnologías

### Backend
- **FastAPI** - Framework web moderno y rápido
- **SQLAlchemy** - ORM para Python
- **SQLite** - Base de datos ligera
- **Uvicorn** - Servidor ASGI
- **unittest** - Framework de testing

### Frontend
- **Laravel** - Framework PHP elegante
- **Blade** - Motor de plantillas
- **Bootstrap** - Framework CSS

## 📋 Requisitos

### Para Windows y macOS

#### Backend (FastAPI)
- Python 3.11 o superior
- pip (gestor de paquetes de Python)

#### Frontend (Laravel)
- PHP 8.1 o superior
- Composer
- Node.js y npm (para assets)

## 🚀 Instalación

### Backend (FastAPI)

#### Windows

1. **Clonar el repositorio**
```bash
git clone https://github.com/tu-usuario/GestionDeCitas.git
cd GestionDeCitas
```

2. **Crear entorno virtual**
```bash
cd backend\src
python -m venv venv
venv\Scripts\activate
```

3. **Instalar dependencias**
```bash
pip install fastapi
pip install uvicorn[standard]
pip install sqlalchemy
pip install python-multipart
```

4. **Configurar la base de datos**

⚠️ **IMPORTANTE**: Antes de inicializar, verifica la ruta de la base de datos en `src/infrastructure/data/AppDbContext.py`:

**Windows:**
```python
SQLALCHEMY_DATABASE_URL = "sqlite:///E:/diplomado/GestionDeCitas/backend/src/db/test.db"
```

**macOS/Linux:**
```python
SQLALCHEMY_DATABASE_URL = "sqlite:////Users/tu-usuario/ruta/proyecto/backend/src/db/test.db"
```

> 💡 **Tip**: En macOS/Linux, reemplaza la ruta Windows con la ruta absoluta de tu sistema.

5. **Inicializar base de datos**

⚠️ **EJECUTAR DESDE**: `backend/src/` (importante para la jerarquía de carpetas)

```bash
# Asegúrate de estar en backend/src/
cd backend/src
python -m salon_api.init_db
```

✅ **Resultado esperado:**
```
🗄️ Base de datos creada exitosamente
📁 Archivo creado en: src/db/test.db
✅ Todas las tablas inicializadas correctamente
```

6. **Ejecutar el servidor**
```bash
uvicorn salon_api.main:app --reload
```

#### macOS/Linux

1. **Clonar el repositorio**
```bash
git clone https://github.com/tu-usuario/GestionDeCitas.git
cd GestionDeCitas
```

2. **Crear entorno virtual**
```bash
cd backend/src
python3 -m venv venv
source venv/bin/activate
```

3. **Instalar dependencias**
```bash
pip install fastapi
pip install uvicorn[standard]
pip install sqlalchemy
pip install python-multipart
```

4. **Inicializar base de datos**
```bash
python init_database.py
```

5. **Ejecutar el servidor**
```bash
uvicorn salon_api.main:app --reload
```

### Frontend (Laravel)

#### Windows

1. **Navegar al directorio frontend**
```bash
cd frontend
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Copiar archivo de configuración**
```bash
copy .env.example .env
```

4. **Generar clave de aplicación**
```bash
php artisan key:generate
```

5. **Instalar dependencias de Node.js** (opcional)
```bash
npm install
npm run dev
```

6. **Ejecutar el servidor de desarrollo**
```bash
php artisan serve
```

#### macOS/Linux

1. **Navegar al directorio frontend**
```bash
cd frontend
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Copiar archivo de configuración**
```bash
cp .env.example .env
```

4. **Generar clave de aplicación**
```bash
php artisan key:generate
```

5. **Instalar dependencias de Node.js** (opcional)
```bash
npm install
npm run dev
```

6. **Ejecutar el servidor de desarrollo**
```bash
php artisan serve
```

## 🎯 Uso

### Acceso a la Aplicación

1. **Backend API**: http://127.0.0.1:8000
   - Documentación automática: http://127.0.0.1:8000/docs
   - API alternativa: http://127.0.0.1:8000/redoc

2. **Frontend Laravel**: http://127.0.0.1:8000 (por defecto)

### Configuración Inicial

1. El sistema se inicia sin autenticación por defecto
2. La base de datos SQLite se crea automáticamente en `src/db/test.db`
3. Los archivos estáticos se almacenan en `backend/src/uploads/`
4. **⚠️ Importante**: Todos los comandos del backend deben ejecutarse desde `backend/src/`

### Rutas de Base de Datos por Sistema Operativo

- **Windows**: `sqlite:///E:/diplomado/GestionDeCitas/backend/src/db/test.db`
- **macOS/Linux**: `sqlite:////Users/tu-usuario/proyecto/backend/src/db/test.db`

> 💡 **Tip**: Edita `src/infrastructure/data/AppDbContext.py` con la ruta absoluta correcta de tu sistema antes de inicializar.

## 🔗 API Endpoints

### 👥 Usuarios y Roles
- `GET/POST/PUT/DELETE /usuarios` - Gestión de usuarios
- `GET/POST/PUT/DELETE /roles` - Gestión de roles
- `GET/POST/PUT/DELETE /clientes` - Gestión de clientes

### 📦 Inventario
- `GET/POST/PUT/DELETE /tipos_articulo` - Tipos de artículos
- `GET/POST/PUT/DELETE /articulos` - Artículos
- `GET /articulos/{id}` - Obtener artículo por ID
- `GET/POST/PUT/DELETE /proveedores` - Proveedores

### 💰 Transacciones
- `GET/POST/PUT/DELETE /compras` - Gestión de compras
- `GET/POST/PUT/DELETE /ventas` - Gestión de ventas
- `GET /ventas/{id}` - Obtener venta por ID

### 📁 Archivos
- `GET /uploads/{filename}` - Archivos estáticos

## 🧪 Testing

### Ejecutar Pruebas Unitarias

#### Windows
```bash
cd backend\src
python -m salon_api.test_models
```

#### macOS/Linux
```bash
cd backend/src
python -m salon_api.test_models
```

### Resultados Esperados
```
🧪 SISTEMA DE PRUEBAS UNITARIAS - MODELOS DEL DOMINIO
✅ Pruebas ejecutadas: 26
❌ Pruebas fallidas: 0
⚠️  Errores: 0
🎯 Tasa de éxito: 100.0%
```

### Diagnóstico de Base de Datos

Si tienes problemas con la base de datos:

```bash
python diagnostico_bd.py
```

## 📁 Estructura del Proyecto

```
GestionDeCitas/
├── backend/
│   └── src/
│       ├── db/
│       │   └── test.db              # Base de datos SQLite (se crea automáticamente)
│       ├── salon_api/
│       │   ├── main.py              # App principal FastAPI
│       │   ├── init_db.py           # Script de inicialización de BD
│       │   ├── endpoints/           # Controllers REST
│       │   │   ├── Usuario/
│       │   │   ├── Cliente/
│       │   │   ├── TipoArticulo/
│       │   │   ├── Articulo/
│       │   │   ├── Proveedor/
│       │   │   ├── Compra/
│       │   │   ├── Venta/
│       │   │   └── Rol/
│       │   └── test_models.py       # Pruebas unitarias
│       ├── core/
│       │   ├── models/              # Modelos del dominio
│       │   └── handlers/            # Lógica de negocio
│       ├── infrastructure/
│       │   └── data/
│       │       ├── AppDbContext.py  # ⚠️ Configuración de BD
│       │       └── *_repository_impl.py # Repositorios
│       └── uploads/                 # Archivos estáticos
└── frontend/
    ├── app/
    │   ├── Http/Controllers/        # Controllers Laravel
    │   └── Models/                  # Modelos Eloquent
    ├── resources/
    │   └── views/                   # Vistas Blade
    ├── routes/
    │   └── web.php                  # Rutas web
    └── public/                      # Assets públicos
```

## 🔧 Solución de Problemas

### Backend

**Error: "no such table: tipos_articulo"**
```bash
# Asegúrate de estar en backend/src/
cd backend/src
python -m salon_api.init_db
```

**Error de ruta en base de datos**
1. Verifica que `src/infrastructure/data/AppDbContext.py` tenga la ruta correcta:
   - **Windows**: `sqlite:///E:/tu/ruta/backend/src/db/test.db`
   - **macOS/Linux**: `sqlite:////Users/tu-usuario/ruta/backend/src/db/test.db`
2. Crea el directorio `db/` si no existe:
   ```bash
   mkdir db  # En backend/src/
   ```

**Error en pruebas unitarias**
```bash
cd backend/src
python diagnostico_bd.py
python -m salon_api.test_models
```

**Problema de jerarquía de carpetas**
```bash
# SIEMPRE ejecutar desde backend/src/
cd backend/src
python -m salon_api.init_db
python -m salon_api.test_models
uvicorn salon_api.main:app --reload
```

### Frontend

**Error: "Class 'X' not found"**
```bash
composer dump-autoload
```

**Error de permisos en storage**
```bash
# Windows
icacls storage /grant Everyone:F /T

# macOS/Linux
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## 🚀 Despliegue

### Desarrollo
- Backend: `uvicorn salon_api.main:app --reload`
- Frontend: `php artisan serve`

### Producción
- Configurar servidor web (Apache/Nginx)
- Usar PostgreSQL/MySQL en lugar de SQLite
- Configurar variables de entorno
- Implementar autenticación JWT
- Configurar HTTPS

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-caracteristica`)
3. Commit tus cambios (`git commit -am 'Agrega nueva característica'`)
4. Push a la rama (`git push origin feature/nueva-caracteristica`)
5. Abre un Pull Request

### Estándares de Código

- **Backend**: Seguir PEP 8 para Python
- **Frontend**: Seguir PSR-12 para PHP
- **Testing**: Mantener 100% de cobertura en pruebas unitarias
- **Arquitectura**: Respetar Clean Architecture

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👨‍💻 Autor

Desarrollado como parte del diplomado en desarrollo de software.

## 📞 Soporte

Si tienes problemas o preguntas:

1. Revisa la sección de [Solución de Problemas](#-solución-de-problemas)
2. Ejecuta el diagnóstico: `python diagnostico_bd.py`
3. Verifica que todas las dependencias estén instaladas
4. Abre un issue en el repositorio

---

⭐ **¡Si este proyecto te fue útil, dale una estrella en GitHub!** ⭐