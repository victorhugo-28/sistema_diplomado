from core.interfaces.rol_repository import RolRepositoryInterface

class ActualizarRolHandler:
    def __init__(self, repo: RolRepositoryInterface):
        self.repo = repo

    def handle(self, id: int, data: dict):
        return self.repo.actualizar(id, data)
