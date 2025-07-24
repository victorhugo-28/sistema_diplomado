from core.interfaces.tipo_articulo_repository import TipoArticuloRepositoryInterface

class EliminarTipoArticuloHandler:
    def __init__(self, repo: TipoArticuloRepositoryInterface):
        self.repo = repo

    def handle(self, id: int):
        return self.repo.eliminar(id)
