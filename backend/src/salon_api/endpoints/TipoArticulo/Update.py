# salon_api/endpoints/tipo_articulo/update.py
from fastapi import APIRouter, HTTPException
from core.dto.tipo_articulo_dto import ActualizarTipoArticuloDTO
from core.handlers.tipo_articulo.actualizar_tipo_articulo_handler import ActualizarTipoArticuloHandler
from infrastructure.data.tipo_articulo_repository_impl import TipoArticuloRepositoryImpl

router = APIRouter()

@router.put("/tipos_articulo/{id}")
def actualizar_tipo_articulo(id: int, data: ActualizarTipoArticuloDTO):
    handler = ActualizarTipoArticuloHandler(TipoArticuloRepositoryImpl())
    out = handler.handle(id, data)
    if not out:
        raise HTTPException(status_code=404, detail="Tipo de artículo no encontrado")
    return out
