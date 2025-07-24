from fastapi import APIRouter
from core.handlers.tipo_articulo.listar_tipo_articulo_handler import ListarTipoArticuloHandler
from infrastructure.data.tipo_articulo_repository_impl import TipoArticuloRepositoryImpl

router = APIRouter()

@router.get("/tipos_articulo")
def listar_tipos_articulo():
    handler = ListarTipoArticuloHandler(TipoArticuloRepositoryImpl())
    return handler.handle()
