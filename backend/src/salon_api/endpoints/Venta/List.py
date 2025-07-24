from fastapi import APIRouter
from core.handlers.venta.listar_venta_handler import ListarVentaHandler
from infrastructure.data.venta_repository_impl import VentaRepositoryImpl

router = APIRouter()

@router.get("/ventas")
def listar_ventas():
    handler = ListarVentaHandler(VentaRepositoryImpl())
    return handler.handle()
