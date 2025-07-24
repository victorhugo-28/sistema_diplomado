from fastapi import APIRouter
from core.handlers.compra.eliminar_compra_handler import EliminarCompraHandler
from infrastructure.data.compra_repository_impl import CompraRepositoryImpl

router = APIRouter()

@router.delete("/compras/{id}")
def eliminar_compra(id: int):
    handler = EliminarCompraHandler(CompraRepositoryImpl())
    return {"eliminado": handler.handle(id)}
