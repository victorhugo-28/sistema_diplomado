from core.dto.venta_dto import CrearVentaDTO
from core.interfaces.venta_repository import VentaRepositoryInterface
from core.models.venta import Venta

class ActualizarVentaHandler:
    def __init__(self, repo: VentaRepositoryInterface):
        self.repo = repo

    def handle(self, id: int, data: CrearVentaDTO):
        venta = Venta(idventa=id, **data.model_dump())
        return self.repo.actualizar(venta)
