# src/contracts/usuario_dto.py
from pydantic import BaseModel

class UsuarioDTO(BaseModel):
    id: int
    nombre: str
    correo: str
