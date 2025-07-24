from fastapi import APIRouter
from core.handlers.articulo.actualizar_articulo_handler import ActualizarArticuloHandler
from infrastructure.data.articulo_repository_impl import ArticuloRepositoryImpl

router = APIRouter()

@router.put("/articulos/{id}")
def actualizar_articulo(id: int, data: dict):
    handler = ActualizarArticuloHandler(ArticuloRepositoryImpl())
    return handler.handle(id, data)
