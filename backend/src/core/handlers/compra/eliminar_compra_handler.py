from core.interfaces.compra_repository import CompraRepositoryInterface

class EliminarCompraHandler:
    def __init__(self, repo: CompraRepositoryInterface):
        self.repo = repo

    def handle(self, id: int):
        return self.repo.eliminar(id)
