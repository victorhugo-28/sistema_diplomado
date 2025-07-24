from core.interfaces.rol_repository import RolRepositoryInterface
class EliminarRolHandler:
    def __init__(self, repo: RolRepositoryInterface):
        self.repo = repo

    def handle(self, id: int):
        return self.repo.eliminar(id)
