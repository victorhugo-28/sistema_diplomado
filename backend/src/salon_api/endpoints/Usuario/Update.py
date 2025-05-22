from fastapi import APIRouter, HTTPException
from core.dto.usuario_dto import ActualizarUsuarioDTO
from core.handlers.usuario.actualizar_usuario_handler import ActualizarUsuarioHandler
from infrastructure.data.usuario_repository_impl import UsuarioRepositoryImpl

router = APIRouter()

@router.put("/usuarios/{id}")
def actualizar_usuario(id: int, data: ActualizarUsuarioDTO):
    repo = UsuarioRepositoryImpl()
    handler = ActualizarUsuarioHandler(repo)
    try:
        return handler.handle(id, data)
    except ValueError as e:
        raise HTTPException(status_code=404, detail=str(e))

