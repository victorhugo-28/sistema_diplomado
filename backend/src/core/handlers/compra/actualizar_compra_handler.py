from core.interfaces.compra_repository import CompraRepositoryInterface
class ActualizarCompraHandler:
    def __init__(self, repo: CompraRepositoryInterface):
        self.repo = repo

    def handle(self, id: int, data: dict):
        return self.repo.actualizar(id, data)
