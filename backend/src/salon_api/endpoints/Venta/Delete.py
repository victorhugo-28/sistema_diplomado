from fastapi import APIRouter
from core.handlers.venta.eliminar_venta_handler import EliminarVentaHandler
from infrastructure.data.venta_repository_impl import VentaRepositoryImpl

router = APIRouter()

@router.delete("/ventas/{id}")
def eliminar_venta(id: int):
    handler = EliminarVentaHandler(VentaRepositoryImpl())
    return handler.handle(id)
