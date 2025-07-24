from fastapi import APIRouter
from core.handlers.articulo.listar_articulo_handler import ListarArticuloHandler
from infrastructure.data.articulo_repository_impl import ArticuloRepositoryImpl

router = APIRouter()

@router.get("/articulos")
def listar_articulos():
    handler = ListarArticuloHandler(ArticuloRepositoryImpl())
    return handler.handle()
