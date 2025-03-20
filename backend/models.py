from sqlalchemy import Column, Integer, String, DateTime, Boolean
from .database import Base
from datetime import datetime

class Cita(Base):
    __tablename__ = "citas"

    id = Column(Integer, primary_key=True, index=True)
    nombre_cliente = Column(String, index=True)
    fecha_hora = Column(DateTime, default=datetime.now)
    duracion_minutos = Column(Integer, default=60)
    telefono = Column(String)
    email = Column(String)
    servicio = Column(String)
    estado = Column(String, default="pendiente")  # pendiente, confirmada, cancelada, completada
    notas = Column(String, nullable=True)
    recordatorio_enviado = Column(Boolean, default=False)
    fecha_creacion = Column(DateTime, default=datetime.now)
    fecha_actualizacion = Column(DateTime, default=datetime.now, onupdate=datetime.now)