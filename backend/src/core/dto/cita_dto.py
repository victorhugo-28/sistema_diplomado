from pydantic import BaseModel
from datetime import datetime

class CrearCitaDTO(BaseModel):
    fecha: datetime
    descripcion: str
    usuario_id: int

class ActualizarCitaDTO(BaseModel):
    fecha: datetime
    descripcion: str
    usuario_id: int
