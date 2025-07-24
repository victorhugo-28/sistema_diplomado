from core.interfaces.venta_repository import VentaRepositoryInterface

class ListarVentaHandler:
    def __init__(self, repo: VentaRepositoryInterface):
        self.repo = repo

    def handle(self):
        return self.repo.listar_todos()
        