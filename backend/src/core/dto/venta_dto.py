# core/dto/venta_dto.py (actualizado)
from pydantic import BaseModel
from typing import Optional, List
from datetime import datetime

class DetalleVentaDTO(BaseModel):
    idarticulo: int
    detalle_ventacantidad: int
    detalle_ventaprecio_venta: str
    detalle_ventadescuento: Optional[str] = "0.0"

class CrearVentaDTO(BaseModel):
    ventatipo_comprobante: int
    ventaserie_comprobante: Optional[str] = "0" 
    ventanum_comprobante: Optional[int] = 0
    ventafecha_hora: Optional[datetime]
    ventaimpuesto: Optional[int] = 0
    ventatotal_venta: str
    idcliente: int
    ventacondicion: Optional[int] = 1
    ventapago_cliente: Optional[float] = 0.0
    ventacambio: Optional[float] = 0.0
    detalles: List[DetalleVentaDTO] 

class ActualizarVentaDTO(BaseModel):
    ventatipo_comprobante: Optional[int] = None
    ventaserie_comprobante: Optional[str] = None
    ventanum_comprobante: Optional[int] = None
    ventafecha_hora: Optional[datetime] = None
    ventaimpuesto: Optional[float] = None
    ventatotal_venta: Optional[float] = None
    idcliente: Optional[int] = None
    ventacondicion: Optional[int] = None
    ventapago_cliente: Optional[float] = None
    ventacambio: Optional[float] = None
    detalles: Optional[List[DetalleVentaDTO]] = None

class ArticuloOut(BaseModel):
    idarticulo: int
    articulonombre: str
    articulocodigo: Optional[str] = None
    articulostock: Optional[int] = None

class DetalleVentaOut(BaseModel):
    iddetalle_venta: int
    idarticulo: int
    detalle_ventacantidad: int
    detalle_ventaprecio_venta: str
    detalle_ventadescuento: Optional[str] = "0.0"
    articulo: Optional[ArticuloOut] = None

class VentaOut(BaseModel):
    idventa: int
    ventatipo_comprobante: int
    ventaserie_comprobante: str  # Cambiado a string
    ventanum_comprobante: int
    ventafecha_hora: Optional[datetime] = None  # Cambiado a datetime
    ventaimpuesto: int
    ventatotal_venta: str
    idcliente: int
    ventacondicion: int
    ventapago_cliente: Optional[float] = None
    ventacambio: Optional[float] = None
    detalles: List[DetalleVentaOut] = []
    
    # Removido orm_mode ya que usamos diccionarios