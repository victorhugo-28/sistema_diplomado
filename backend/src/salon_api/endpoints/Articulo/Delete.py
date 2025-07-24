from fastapi import APIRouter
from core.handlers.articulo.eliminar_articulo_handler import EliminarArticuloHandler
from infrastructure.data.articulo_repository_impl import ArticuloRepositoryImpl

router = APIRouter()

@router.delete("/articulos/{id}")
def eliminar_articulo(id: int):
    handler = EliminarArticuloHandler(ArticuloRepositoryImpl())
    return {"eliminado": handler.handle(id)}
