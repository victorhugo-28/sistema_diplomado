from core.dto.venta_dto import CrearVentaDTO
from core.models.venta import Venta
from core.interfaces.venta_repository import VentaRepositoryInterface

class CrearVentaHandler:
    def __init__(self, repo: VentaRepositoryInterface):
        self.repo = repo

    def handle(self, data: CrearVentaDTO):
        nueva = Venta(**data.dict())
        return self.repo.crear(nueva)
