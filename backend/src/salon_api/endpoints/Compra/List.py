from fastapi import APIRouter
from core.handlers.compra.listar_compra_handler import ListarCompraHandler
from infrastructure.data.compra_repository_impl import CompraRepositoryImpl

router = APIRouter()

@router.get("/compras")
def listar_compras():
    handler = ListarCompraHandler(CompraRepositoryImpl())
    return handler.handle()
