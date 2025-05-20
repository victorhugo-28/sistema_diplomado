# src/core/handlers/listar_usuarios_handler.py
from core.interfaces.usuario_repository import UsuarioRepository

def listar_usuarios_usecase(repo: UsuarioRepository):
    return repo.listar_usuarios()
