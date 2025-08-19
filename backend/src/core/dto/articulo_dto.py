from pydantic import BaseModel
from typing import Optional
from datetime import date, time

class CrearArticuloDTO(BaseModel):
    articulonombre: str
    articulostock: Optional[int] = 0
    articulodescripcion: Optional[str] = None
    articuloimagen: Optional[str] = None
    articulocodigogener: Optional[str] = None
    articulocodigo: Optional[str] = None
    articulofecha: Optional[date] = None
    articulohora: Optional[time] = None
    id_tipo: int
    id_proveedor: int

class ArticuloDTO(CrearArticuloDTO):
    idarticulo: int

    class Config:
        orm_mode = True 

class ActualizarArticuloDTO(BaseModel):
    articulonombre: Optional[str] = None
    articulostock: Optional[int] = None
    articulodescripcion: Optional[str] = None
    articuloimagen: Optional[str] = None
    articulocodigogener: Optional[str] = None
    articulocodigo: Optional[str] = None
    articulofecha: Optional[date] = None
    articulohora: Optional[time] = None
    id_tipo: Optional[int] = None
    id_proveedor: Optional[int] = None
# DTO para información de precios del detalle de ingreso
class DetalleIngresoInfo(BaseModel):
    detalle_ingresoprecio_compra: Optional[str] = None
    detalle_ingresoprecio_venta: Optional[str] = None
    detalle_ingresocantidad: Optional[int] = None

# DTO para la respuesta (GET BY ID)
class ArticuloOut(BaseModel):
    idarticulo: int
    articulonombre: str
    articulostock: Optional[int] = None
    articulodescripcion: Optional[str] = None
    articuloimagen: Optional[str] = None
    articulocodigogener: Optional[str] = None
    articulocodigo: Optional[str] = None
    articulofecha: Optional[date] = None
    articulohora: Optional[time] = None
    id_tipo: int
    id_proveedor: int
    # Información de precios del último ingreso
    ultimo_precio_compra: Optional[str] = None
    ultimo_precio_venta: Optional[str] = None
    detalles_ingreso: Optional[list] = []

    class Config:
        orm_mode = True