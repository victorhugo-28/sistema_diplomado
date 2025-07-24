from fastapi import APIRouter
from core.dto.articulo_dto import CrearArticuloDTO
from core.handlers.articulo.crear_articulo_handler import CrearArticuloHandler
from infrastructure.data.articulo_repository_impl import ArticuloRepositoryImpl

router = APIRouter()

@router.post("/articulos")
def crear_articulo(data: CrearArticuloDTO):
    handler = CrearArticuloHandler(ArticuloRepositoryImpl())
    articulo = handler.handle(data)
    return {
        "id": articulo.idarticulo,
        "nombre": articulo.articulonombre
    }
