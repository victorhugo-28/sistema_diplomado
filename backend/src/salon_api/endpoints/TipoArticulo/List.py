# salon_api/endpoints/TipoArticulo/List.py (corregido)
from fastapi import APIRouter
from infrastructure.data.tipo_articulo_repository_impl import TipoArticuloRepositoryImpl
from typing import List
from core.dto.tipo_articulo_dto import ListarTipoArticuloDTO

router = APIRouter()

@router.get("/tipos_articulo", response_model=List[ListarTipoArticuloDTO])
def listar_tipos_articulo():
    # Usar el repositorio directamente en lugar del handler problemático
    repo = TipoArticuloRepositoryImpl()
    tipos = repo.listar_todos()
    
    # El repositorio ya devuelve el formato correcto: [{"id": 1, "nombre": "..."}]
    return tipos