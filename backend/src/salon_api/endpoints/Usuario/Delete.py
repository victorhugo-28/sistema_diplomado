from fastapi import APIRouter, HTTPException
from core.handlers.usuario.eliminar_usuario_handler import EliminarUsuarioHandler
from infrastructure.data.usuario_repository_impl import UsuarioRepositoryImpl

router = APIRouter()

@router.delete("/usuarios/{id}")
def eliminar_usuario(id: int):
    repo = UsuarioRepositoryImpl()
    handler = EliminarUsuarioHandler(repo)
    try:
        return handler.handle(id)
    except ValueError as e:
        raise HTTPException(status_code=404, detail=str(e))
