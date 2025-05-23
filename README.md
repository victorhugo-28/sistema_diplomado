# Sistema de Gestión de Citas

Este proyecto es una aplicación web construida con **FastAPI**, **SQLAlchemy**, **RabbitMQ** y un frontend básico en **HTML + Bootstrap**, que permite gestionar usuarios y citas médicas de forma eficiente.

## 📦 Características

- CRUD completo de **Usuarios**.
- CRUD completo de **Citas**.
- Arquitectura **DDD (Domain-Driven Design)** bien estructurada.
- Comunicación asincrónica mediante **RabbitMQ** al crear usuarios.
- Interfaz web simple con **HTML + Bootstrap** para consumir la API.
- API documentada con Swagger disponible en `/docs`.

## 🗂 Estructura del Proyecto

src/
├── contracts/ # DTOs
├── core/ # lógica de negocio (handlers, interfaces, modelos)
├── infrastructure/ # base de datos y mensajería (RabbitMQ)
├── salon_api/ # endpoints de FastAPI
├── frontend/ # vistas HTML + JS
└── test.db # base de datos SQLite

## 🚀 Cómo iniciar el backend

1. Crea el entorno virtual:

```bash
python -m venv venv
source venv/bin/activate  # para Linux/macOS
venv\Scripts\activate     # para Windows
```
2. Para instalar las dependencias:

pip install -r requirements.txt

3. Para inicializar la base de datos:

python src/salon_api/init_db.py

4. Ejecutar el Servidor

uvicorn src.salon_api.main:app --reload

5. Accede a la documentación Swagger:

http://127.0.0.1:8000/docs

🌐 Frontend
La carpeta backend/src/frontend/

Abre frontend/index.html en el navegador.

✨ Funcionalidades futuras
Autenticación de usuarios

Roles (admin, usuario)

Notificaciones por correo

Dashboard estadístico