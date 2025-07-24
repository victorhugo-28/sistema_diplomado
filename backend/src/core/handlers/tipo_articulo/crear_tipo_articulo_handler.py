from core.interfaces.tipo_articulo_repository import TipoArticuloRepositoryInterface
from core.models.tipo_articulo import TipoArticulo
from core.dto.tipo_articulo_dto import CrearTipoArticuloDTO
class CrearTipoArticuloHandler:
    def __init__(self, repo: TipoArticuloRepositoryInterface):
        self.repo = repo

    def handle(self, data: CrearTipoArticuloDTO):
        tipo = TipoArticulo(**data.dict())
        return self.repo.crear(tipo)
