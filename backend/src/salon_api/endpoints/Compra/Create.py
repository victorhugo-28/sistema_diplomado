from fastapi import APIRouter
from core.dto.compra_dto import CrearCompraDTO
from core.handlers.compra.crear_compra_handler import CrearCompraHandler
from infrastructure.data.compra_repository_impl import CompraRepositoryImpl

router = APIRouter()

@router.post("/compras")
def crear_compra(data: CrearCompraDTO):
    handler = CrearCompraHandler(CompraRepositoryImpl())
    compra = handler.handle(data)
    return {
        "id": compra.idingreso,
        "total": compra.ingresototal_compra
    }
