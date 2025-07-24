from fastapi import APIRouter
from core.handlers.tipo_articulo.eliminar_tipo_articulo_handler import EliminarTipoArticuloHandler
from infrastructure.data.tipo_articulo_repository_impl import TipoArticuloRepositoryImpl

router = APIRouter()

@router.delete("/tipos_articulo/{id}")
def eliminar_tipo_articulo(id: int):
    handler = EliminarTipoArticuloHandler(TipoArticuloRepositoryImpl())
    return {"eliminado": handler.handle(id)}
