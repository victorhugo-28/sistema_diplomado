# core/handlers/venta/actualizar_venta_handler.py
from infrastructure.data.AppDbContext import SessionLocal
from infrastructure.data.venta_repository_impl import VentaRepositoryImpl
from core.dto.venta_dto import ActualizarVentaDTO

class ActualizarVentaHandler:
    def __init__(self, repo: VentaRepositoryImpl):
        self.repo = repo

    def handle(self, id: int, data: ActualizarVentaDTO):
        # Pydantic v1
        cambios = data.dict(exclude_none=True)
        # ✅ No actualizar la relación "detalles"
        cambios.pop("detalles", None)
        return self.repo.actualizar(id, cambios)
