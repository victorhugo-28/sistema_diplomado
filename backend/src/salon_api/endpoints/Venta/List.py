# salon_api/endpoints/venta/List.py
from fastapi import APIRouter, Query
from infrastructure.data.venta_repository_impl import VentaRepositoryImpl
from core.dto.venta_dto import VentaOut

router = APIRouter()

@router.get("/ventas", response_model=list[VentaOut])
def listar_ventas(limit: int = Query(50, ge=1, le=200), offset: int = Query(0, ge=0)):
    repo = VentaRepositoryImpl()
    return repo.listar_con_detalles(limit=limit, offset=offset)
