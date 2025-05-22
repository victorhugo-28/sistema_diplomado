from core.interfaces.usuario_repository import UsuarioRepositoryInterface

class ListarUsuariosHandler:
    def __init__(self, repo: UsuarioRepositoryInterface):
        self.repo = repo

    def handle(self):
        return self.repo.listar_todos()
