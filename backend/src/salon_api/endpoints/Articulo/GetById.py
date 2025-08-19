# salon_api/endpoints/Articulo/GetById.py
from fastapi import APIRouter, HTTPException
from infrastructure.data.articulo_repository_impl import ArticuloRepositoryImpl
from core.dto.articulo_dto import ArticuloOut

router = APIRouter()

@router.get("/articulos/{id}", response_model=ArticuloOut)
def obtener_articulo(id: int):
    repo = ArticuloRepositoryImpl()
    articulo = repo.obtener_por_id_con_precios(id)
    if not articulo:
        raise HTTPException(status_code=404, detail="Artículo no encontrado")
    return articulo