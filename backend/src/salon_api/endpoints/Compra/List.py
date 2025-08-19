# salon_api/endpoints/compra/List.py
from fastapi import APIRouter, Query
from infrastructure.data.compra_repository_impl import CompraRepositoryImpl
from core.dto.compra_dto import CompraOut

router = APIRouter()

@router.get("/compras", response_model=list[CompraOut])
def listar_compras(limit: int = Query(50, ge=1, le=200), offset: int = Query(0, ge=0)):
    repo = CompraRepositoryImpl()
    return repo.listar_con_detalles(limit=limit, offset=offset)