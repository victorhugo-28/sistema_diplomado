# salon_api/endpoints/compra/Create.py
from fastapi import APIRouter
from core.dto.compra_dto import CrearCompraDTO, CompraOut
from core.handlers.compra.crear_compra_handler import CrearCompraHandler

router = APIRouter()

@router.post("/compras", response_model=CompraOut)
def crear_compra(data: CrearCompraDTO):
    handler = CrearCompraHandler()
    compra = handler.handle(data)
    return compra
