from core.interfaces.articulo_repository import ArticuloRepositoryInterface

class EliminarArticuloHandler:
    def __init__(self, repo: ArticuloRepositoryInterface):
        self.repo = repo

    def handle(self, id: int):
        return self.repo.eliminar(id)
