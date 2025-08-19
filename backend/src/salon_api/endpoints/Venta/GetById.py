# salon_api/endpoints/venta/GetById.py
from fastapi import APIRouter, HTTPException
from infrastructure.data.venta_repository_impl import VentaRepositoryImpl
from core.dto.venta_dto import VentaOut

router = APIRouter()

@router.get("/ventas/{id}", response_model=VentaOut)
def obtener_venta(id: int):
    repo = VentaRepositoryImpl()
    venta = repo.obtener_por_id_con_detalles(id)
    if not venta:
        raise HTTPException(404, "Venta no encontrada")
    return venta
