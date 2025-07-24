from pydantic import BaseModel

class CrearClienteDTO(BaseModel):
    nombre: str
    email: str
    telefono: str
