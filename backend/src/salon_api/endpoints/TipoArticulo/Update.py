from fastapi import APIRouter
from core.handlers.tipo_articulo.actualizar_tipo_articulo_handler import ActualizarTipoArticuloHandler
from infrastructure.data.tipo_articulo_repository_impl import TipoArticuloRepositoryImpl

router = APIRouter()

@router.put("/tipos_articulo/{id}")
def actualizar_tipo_articulo(id: int, data: dict):
    handler = ActualizarTipoArticuloHandler(TipoArticuloRepositoryImpl())
    return handler.handle(id, data)
