from fastapi import APIRouter
from core.dto.venta_dto import CrearVentaDTO
from core.handlers.venta.actualizar_venta_handler import ActualizarVentaHandler
from infrastructure.data.venta_repository_impl import VentaRepositoryImpl

router = APIRouter()

@router.put("/ventas/{id}")
def actualizar_venta(id: int, data: CrearVentaDTO):
    handler = ActualizarVentaHandler(VentaRepositoryImpl())
    return handler.handle(id, data)
