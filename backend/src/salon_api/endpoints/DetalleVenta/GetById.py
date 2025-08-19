# salon_api/endpoints/DetalleVenta/GetById.py
from fastapi import APIRouter, HTTPException
from infrastructure.data.detalle_venta_repository_impl import DetalleVentaRepositoryImpl
from core.dto.detalle_venta_dto import DetalleVentaOut

router = APIRouter()

@router.get("/detalles-venta/{id}", response_model=DetalleVentaOut)
def obtener_detalle_venta(id: int):
    repo = DetalleVentaRepositoryImpl()
    detalle = repo.obtener_por_id(id)
    if not detalle:
        raise HTTPException(status_code=404, detail="Detalle de venta no encontrado")
    return detalle

@router.get("/detalles-venta/venta/{venta_id}", response_model=list[DetalleVentaOut])
def obtener_detalles_por_venta(venta_id: int):
    """Obtiene todos los detalles de una venta específica"""
    repo = DetalleVentaRepositoryImpl()
    detalles = repo.obtener_por_venta_id(venta_id)
    if not detalles:
        raise HTTPException(status_code=404, detail="No se encontraron detalles para esta venta")
    return detalles