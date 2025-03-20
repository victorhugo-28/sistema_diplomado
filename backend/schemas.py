from pydantic import BaseModel, EmailStr, validator
from datetime import datetime
from typing import Optional

class CitaBase(BaseModel):
    nombre_cliente: str
    fecha_hora: datetime
    duracion_minutos: int = 60
    telefono: str
    email: str
    servicio: str
    notas: Optional[str] = None

class CitaCreate(CitaBase):
    pass

class CitaUpdate(BaseModel):
    nombre_cliente: Optional[str] = None
    fecha_hora: Optional[datetime] = None
    duracion_minutos: Optional[int] = None
    telefono: Optional[str] = None
    email: Optional[str] = None
    servicio: Optional[str] = None
    estado: Optional[str] = None
    notas: Optional[str] = None
    recordatorio_enviado: Optional[bool] = None

class CitaResponse(CitaBase):
    id: int
    estado: str
    recordatorio_enviado: bool
    fecha_creacion: datetime
    fecha_actualizacion: datetime

    class Config:
        orm_mode = True