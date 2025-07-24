from core.interfaces.articulo_repository import ArticuloRepositoryInterface

class ActualizarArticuloHandler:
    def __init__(self, repo: ArticuloRepositoryInterface):
        self.repo = repo

    def handle(self, id: int, data: dict):
        return self.repo.actualizar(id, data)
