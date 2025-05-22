from fastapi import APIRouter, Depends
from core.handlers.usuario.listar_usuarios_handler import ListarUsuariosHandler
from infrastructure.data.usuario_repository_impl import UsuarioRepositoryImpl

router = APIRouter()

@router.get("/usuarios")
def listar_usuarios():
    repo = UsuarioRepositoryImpl()
    handler = ListarUsuariosHandler(repo)
    usuarios = handler.handle()
    return usuarios
