from pydantic import BaseModel

class CrearTipoArticuloDTO(BaseModel):
    nombre: str

class ListarTipoArticuloDTO(BaseModel):
    id: int
    nombre: str

class ActualizarTipoArticuloDTO(BaseModel):
    nombre: str
