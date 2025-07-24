from core.interfaces.venta_repository import VentaRepositoryInterface

class EliminarVentaHandler:
    def __init__(self, repo: VentaRepositoryInterface):
        self.repo = repo

    def handle(self, id: int):
        return self.repo.eliminar(id)
