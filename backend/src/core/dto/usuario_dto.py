from pydantic import BaseModel, EmailStr


class UsuarioDTO(BaseModel):
    id: int
    nombre: str
    correo: str
class CrearUsuarioDTO(BaseModel):
    nombre: str
    correo: EmailStr
class ActualizarUsuarioDTO(BaseModel):
    nombre: str
    correo: EmailStr