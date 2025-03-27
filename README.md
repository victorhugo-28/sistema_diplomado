# Sistema de Gestión de Citas

Sistema web para la gestión de citas médicas desarrollado con FastAPI (Backend) y React (Frontend).

## 📋 Tabla de Contenidos
1. [Características](#características)
2. [Requisitos Previos](#requisitos-previos)
3. [Instalación y Despliegue](#instalación-y-despliegue)
4. [Estructura del Proyecto](#estructura-del-proyecto)
5. [API Endpoints](#api-endpoints)
6. [Modelo de Datos](#modelo-de-datos)
7. [Tecnologías Utilizadas](#tecnologías-utilizadas)

## ✨ Características
- Sistema de autenticación JWT
- Gestión completa de citas (CRUD)
- Dashboard con resumen de citas
- Interfaz responsiva con Material UI
- API RESTful
- Base de datos SQLite
- Validación de formularios
- Manejo de estados de carga y errores

## 🔧 Requisitos Previos

### Backend
- Python 3.8 o superior
- pip (gestor de paquetes de Python)

### Frontend
- Node.js (versión 14 o superior)
- npm (incluido con Node.js)

## 🚀 Instalación y Despliegue

### 1. Clonar el Repositorio
```bash
git clone <url-del-repositorio>
cd GestionDeCitas
```

### 2. Configurar el Backend
```bash
# 1. Navegar al directorio del backend
cd backend

# 2. Crear y activar entorno virtual
python -m venv venv
# En Windows:
venv\Scripts\activate
# En macOS/Linux:
source venv/bin/activate

# 3. Instalar dependencias
pip install -r requirements.txt

# 4. Iniciar el servidor
uvicorn main:app --reload
```
El backend estará disponible en `http://localhost:8000`

### 3. Configurar el Frontend
```bash
# 1. Navegar al directorio del frontend
cd frontend

# 2. Instalar dependencias
npm install

# 3. Crear archivo .env
echo "REACT_APP_API_URL=http://localhost:8000" > .env

# 4. Iniciar el servidor de desarrollo
npm start
```
El frontend estará disponible en `http://localhost:3000`

## 📁 Estructura del Proyecto

```
backend/
├── __init__.py
├── database.py    # Configuración de la base de datos SQLite
├── models.py      # Modelos SQLAlchemy
├── schemas.py     # Esquemas Pydantic para validación
└── main.py        # Aplicación FastAPI con endpoints
```

## Endpoints de la API

### Autenticación

- `POST /auth/register` - Registrar un nuevo usuario
- `POST /auth/login` - Iniciar sesión y obtener token JWT
- `GET /auth/me` - Obtener información del usuario autenticado

### Citas

- `POST /citas/` - Crear una nueva cita
- `GET /citas/` - Listar todas las citas (con filtros opcionales)
- `GET /citas/{cita_id}` - Obtener una cita por su ID
- `PUT /citas/{cita_id}` - Actualizar una cita existente
- `DELETE /citas/{cita_id}` - Eliminar una cita
- `GET /citas/proximas/{dias}` - Obtener citas programadas para los próximos días
- `PUT /citas/{cita_id}/estado` - Actualizar el estado de una cita

## Modelo de Datos

Cada cita contiene la siguiente información:

- `id`: Identificador único de la cita
- `nombre_cliente`: Nombre del cliente
- `fecha_hora`: Fecha y hora de la cita
- `duracion_minutos`: Duración de la cita en minutos (por defecto 60)
- `telefono`: Número de teléfono del cliente
- `email`: Correo electrónico del cliente
- `servicio`: Tipo de servicio solicitado
- `estado`: Estado de la cita (pendiente, confirmada, cancelada, completada)
- `notas`: Notas adicionales sobre la cita
- `recordatorio_enviado`: Indica si se ha enviado un recordatorio
- `fecha_creacion`: Fecha y hora de creación del registro
- `fecha_actualizacion`: Fecha y hora de la última actualización

## Sistema de Autenticación

El sistema utiliza autenticación basada en JWT (JSON Web Tokens) para proteger los endpoints de la API.

### Flujo de Autenticación

1. **Registro de Usuario**:
   - Enviar una solicitud POST a `/auth/register` con nombre, email y contraseña
   - El sistema crea el usuario y devuelve sus datos (sin la contraseña)

2. **Inicio de Sesión**:
   - Enviar una solicitud POST a `/auth/login` con email y contraseña
   - El sistema valida las credenciales y devuelve un token JWT

3. **Acceso a Rutas Protegidas**:
   - Incluir el token JWT en el encabezado de autorización: `Authorization: Bearer {token}`
   - El sistema valida el token y permite el acceso si es válido

### Ejemplos de Uso

## Acceder a la documentación

La forma más sencilla de explorar la API es a través de la documentación interactiva de Swagger UI:

```
http://localhost:8000/docs
```

## Tecnologías Utilizadas

- React
- TypeScript
- Material UI
- React Router
- Formik
- Yup
- Axios
- date-fns

## Estructura del Proyecto

```
src/
  ├── components/     # Componentes reutilizables
  ├── contexts/      # Contextos de React
  ├── pages/         # Páginas de la aplicación
  ├── services/      # Servicios para comunicación con el backend
  ├── types/         # Definiciones de tipos TypeScript
  ├── App.tsx        # Componente principal
  └── index.tsx      # Punto de entrada
```
