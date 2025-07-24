from core.interfaces.tipo_articulo_repository import TipoArticuloRepositoryInterface

class ActualizarTipoArticuloHandler:
    def __init__(self, repo: TipoArticuloRepositoryInterface):
        self.repo = repo

    def handle(self, id: int, data: dict):
        return self.repo.actualizar(id, data.get("nombre"))
