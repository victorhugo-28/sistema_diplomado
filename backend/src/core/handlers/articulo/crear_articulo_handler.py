from core.interfaces.articulo_repository import ArticuloRepositoryInterface
from core.models.articulo import Articulo
from core.dto.articulo_dto import CrearArticuloDTO
class CrearArticuloHandler:
    def __init__(self, repo: ArticuloRepositoryInterface):
        self.repo = repo

    def handle(self, dto: CrearArticuloDTO):
        articulo = Articulo(**dto.dict())
        return self.repo.crear(articulo)
