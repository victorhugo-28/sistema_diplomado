from fastapi import APIRouter, HTTPException
from core.dto.compra_dto import ActualizarCompraDTO
from core.handlers.compra.actualizar_compra_handler import ActualizarCompraHandler
from infrastructure.data.compra_repository_impl import CompraRepositoryImpl

router = APIRouter()

@router.put("/compras/{id}")
def actualizar_compra(id: int, data: ActualizarCompraDTO):
    handler = ActualizarCompraHandler(CompraRepositoryImpl())
    out = handler.handle(id, data)
    if not out:
        raise HTTPException(status_code=404, detail="Compra no encontrada")
    return out
