from core.interfaces.usuario_repository import UsuarioRepositoryInterface


class EliminarUsuarioHandler:
    def __init__(self, repo: UsuarioRepositoryInterface):
        self.repo = repo

    def handle(self, id: int):
        eliminado = self.repo.eliminar(id)
        if not eliminado:
            raise ValueError("Usuario no encontrado")
        return {"mensaje": "Usuario eliminado correctamente"}
