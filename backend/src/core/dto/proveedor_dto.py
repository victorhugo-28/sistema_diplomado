from pydantic import BaseModel

class CrearProveedorDTO(BaseModel):
    nombre: str
    contacto: str
    direccion: str
class ListarProveedoresDTO(BaseModel):
    id: int
    nombre: str
    contacto: str
    direccion: str
class ActualizarProveedorDTO(BaseModel):
    nombre: str
    contacto: str
    direccion: str