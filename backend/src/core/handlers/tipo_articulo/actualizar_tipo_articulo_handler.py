# core/handlers/tipo_articulo/actualizar_tipo_articulo_handler.py
from core.interfaces.tipo_articulo_repository import TipoArticuloRepositoryInterface
from core.dto.tipo_articulo_dto import ActualizarTipoArticuloDTO

class ActualizarTipoArticuloHandler:
    def __init__(self, repo: TipoArticuloRepositoryInterface):
        self.repo = repo

    def handle(self, id: int, data: ActualizarTipoArticuloDTO):
        return self.repo.actualizar(id, data.nombre)
