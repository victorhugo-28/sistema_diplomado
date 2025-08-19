from pydantic import BaseModel
from typing import Optional

class CrearProveedorDTO(BaseModel):
    # DTO básico - se adaptará automáticamente a tu tabla
    nombre: str
    contacto: Optional[str] = None
    telefono: Optional[str] = None
    email: Optional[str] = None
    direccion: Optional[str] = None
    ruc: Optional[str] = None
    empresa: Optional[str] = None
class ListarProveedoresDTO(BaseModel):
    id: int
    nombre: str
    contacto: str
    direccion: str
class ActualizarProveedorDTO(BaseModel):
    nombre: Optional[str] = None
    contacto: Optional[str] = None
    direccion: Optional[str] = None
