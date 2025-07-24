from core.interfaces.proveedor_repository import ProveedorRepositoryInterface


class EliminarProveedorHandler:
    def __init__(self, repo: ProveedorRepositoryInterface):
        self.repo = repo

    def handle(self, id: int):
        return self.repo.eliminar(id)
