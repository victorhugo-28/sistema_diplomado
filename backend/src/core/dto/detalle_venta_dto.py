# core/dto/detalle_venta_dto.py
from pydantic import BaseModel
from typing import Optional

# DTO para crear un detalle de venta
class CrearDetalleVentaDTO(BaseModel):
    idarticulo: int
    idventa: int
    detalle_ventacantidad: int
    detalle_ventaprecio_venta: str
    detalle_ventadescuento: Optional[str] = "0.0"

# DTO para actualizar un detalle de venta
class ActualizarDetalleVentaDTO(BaseModel):
    idarticulo: Optional[int] = None
    idventa: Optional[int] = None
    detalle_ventacantidad: Optional[int] = None
    detalle_ventaprecio_venta: Optional[str] = None
    detalle_ventadescuento: Optional[str] = None

# DTO para la respuesta de artículo (simplificado)
class ArticuloOut(BaseModel):
    idarticulo: int
    articulonombre: str
    articulocodigo: Optional[str] = None
    articulostock: Optional[int] = None

    class Config:
        orm_mode = True

# DTO para la respuesta completa de detalle de venta
class DetalleVentaOut(BaseModel):
    iddetalle_venta: int
    idarticulo: int
    idventa: int
    detalle_ventacantidad: int
    detalle_ventaprecio_venta: str
    detalle_ventadescuento: Optional[str] = "0.0"
    articulo: Optional[ArticuloOut] = None

    class Config:
        orm_mode = True