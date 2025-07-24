from fastapi import APIRouter
from core.dto.venta_dto import CrearVentaDTO
from core.handlers.venta.crear_venta_handler import CrearVentaHandler
from infrastructure.data.venta_repository_impl import VentaRepositoryImpl

router = APIRouter()

@router.post("/ventas")
def crear_venta(data: CrearVentaDTO):
    handler = CrearVentaHandler(VentaRepositoryImpl())
    venta = handler.handle(data)
    return venta
    