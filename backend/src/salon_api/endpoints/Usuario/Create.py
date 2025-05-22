from fastapi import APIRouter
from core.dto.usuario_dto import CrearUsuarioDTO
from core.handlers.usuario.crear_usuario_handler import CrearUsuarioHandler
from infrastructure.data.usuario_repository_impl import UsuarioRepositoryImpl

router = APIRouter()

@router.post("/usuarios")
def crear_usuario(data: CrearUsuarioDTO):
    handler = CrearUsuarioHandler(UsuarioRepositoryImpl())
    usuario = handler.handle(data)
    return {
        "id": usuario.id,
        "nombre": usuario.nombre,
        "correo": usuario.correo
    }
