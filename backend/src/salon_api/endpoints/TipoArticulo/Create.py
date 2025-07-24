from fastapi import APIRouter
from core.dto.tipo_articulo_dto import CrearTipoArticuloDTO
from core.handlers.tipo_articulo.crear_tipo_articulo_handler import CrearTipoArticuloHandler
from infrastructure.data.tipo_articulo_repository_impl import TipoArticuloRepositoryImpl

router = APIRouter()

@router.post("/tipos_articulo")
def crear_tipo_articulo(data: CrearTipoArticuloDTO):
    handler = CrearTipoArticuloHandler(TipoArticuloRepositoryImpl())
    tipo = handler.handle(data)
    return {
        "id": tipo.id,
        "nombre": tipo.nombre
    }
