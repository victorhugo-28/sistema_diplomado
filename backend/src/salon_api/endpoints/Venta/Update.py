from fastapi import APIRouter, HTTPException
from core.dto.venta_dto import ActualizarVentaDTO
from core.handlers.venta.actualizar_venta_handler import ActualizarVentaHandler
from infrastructure.data.venta_repository_impl import VentaRepositoryImpl

router = APIRouter()

@router.put("/ventas/{id}")
def actualizar_venta(id: int, data: ActualizarVentaDTO):
    handler = ActualizarVentaHandler(VentaRepositoryImpl())
    out = handler.handle(id, data)
    if not out:
        raise HTTPException(status_code=404, detail="Venta no encontrada")
    return out
