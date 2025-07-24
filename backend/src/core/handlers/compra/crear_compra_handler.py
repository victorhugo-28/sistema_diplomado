from core.interfaces.compra_repository import CompraRepositoryInterface
from core.dto.compra_dto import CrearCompraDTO
from core.models.compra import Compra
class CrearCompraHandler:
    def __init__(self, repo: CompraRepositoryInterface):
        self.repo = repo

    def handle(self, dto: CrearCompraDTO):
        compra = Compra(**dto.dict())
        return self.repo.crear(compra)
