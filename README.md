# Sistema de Gestión de Citas

## Descripción
Este es un sistema de gestión de citas desarrollado con FastAPI y SQLite. Permite crear, consultar, actualizar y eliminar citas a través de una API RESTful.

## Características
- Creación, consulta, actualización y eliminación de citas (CRUD)
- Filtrado de citas por estado y fechas
- Consulta de citas próximas
- Actualización del estado de las citas
- Validación de datos con Pydantic
- Documentación automática con Swagger UI
- Autenticación de usuarios con JWT
- Protección de rutas con autenticación
- Registro y login de usuarios

## Estructura del Proyecto
```
backend/
├── __init__.py
├── database.py    # Configuración de la base de datos SQLite
├── models.py      # Modelos SQLAlchemy
├── schemas.py     # Esquemas Pydantic para validación
└── main.py        # Aplicación FastAPI con endpoints
```

## Requisitos
- Python 3.7+
- FastAPI
- SQLAlchemy
- Pydantic
- Uvicorn (servidor ASGI)

## Instalación

1. Instalar dependencias:
```bash
pip install -r requirements.txt
```

Esto instalará todas las dependencias necesarias:
- fastapi>=0.68.0,<0.69.0
- sqlalchemy>=1.4.0,<1.5.0
- pydantic>=1.8.0,<1.9.0
- uvicorn>=0.15.0,<0.16.0
- python-dotenv>=0.19.0,<0.20.0
- email-validator>=1.1.0,<1.2.0

2. Ejecutar el servidor:
```bash
uvicorn backend.main:app --reload
```

3. Acceder a la documentación de la API:
```
http://localhost:8000/docs
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
   - Eviar una solicnitud POST a `/auth/register` con nombre, email y contraseña
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

Para autenticarte en la documentación interactiva:
1. Haz clic en el botón "Authorize" en la parte superior derecha
2. Introduce tus credenciales o el token JWT
3. Una vez autenticado, podrás probar todos los endpoints protegidos
