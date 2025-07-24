from fastapi import APIRouter
from core.handlers.compra.actualizar_compra_handler import ActualizarCompraHandler
from infrastructure.data.compra_repository_impl import CompraRepositoryImpl

router = APIRouter()

@router.put("/compras/{id}")
def actualizar_compra(id: int, data: dict):
    handler = ActualizarCompraHandler(CompraRepositoryImpl())
    return handler.handle(id, data)
