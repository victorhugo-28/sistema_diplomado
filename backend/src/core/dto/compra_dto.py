from pydantic import BaseModel
from typing import Optional, List
from datetime import datetime

class DetalleIngresoDTO(BaseModel):
    idarticulo: int
    detalle_ingresocantidad: int
    detalle_ingresoprecio_compra: str
    detalle_ingresoprecio_venta: str

class CrearCompraDTO(BaseModel):
    ingresotipo_comprobante: str
    ingresoserie_comprobante: Optional[str] = None
    ingresonumero_comprobante: Optional[str] = None
    ingresofecha_hora: Optional[datetime] = None
    ingresoimpuesto: Optional[float] = 0.0
    ingresototal_compra: float
    idproveedor: int
    ingresocondicion: Optional[str] = "1"
    detalles: List[DetalleIngresoDTO] = []



class ActualizarCompraDTO(BaseModel):
    ingresotipo_comprobante: Optional[str] = None
    ingresoserie_comprobante: Optional[str] = None
    ingresonumero_comprobante: Optional[str] = None
    ingresofecha_hora: Optional[datetime] = None
    ingresoimpuesto: Optional[float] = None
    ingresototal_compra: Optional[float] = None
    idproveedor: Optional[int] = None
    ingresocondicion: Optional[str] = None
    detalles: Optional[List[DetalleIngresoDTO]] = None

    class Config:
        json_schema_extra = {
            "example": {
                "ingresotipo_comprobante": "Factura",
                "ingresoserie_comprobante": "A001",
                "ingresonumero_comprobante": "000123",
                "ingresofecha_hora": "2025-08-07T22:04:05.961Z",
                "ingresoimpuesto": 13.0,
                "ingresototal_compra": 250.50,
                "idproveedor": 2,
                "ingresocondicion": "1",
                "detalles": [
                    {
                        "idarticulo": 1,
                        "detalle_ingresocantidad": 5,
                        "detalle_ingresoprecio_compra": "40.00",
                        "detalle_ingresoprecio_venta": "50.00"
                    },
                    {
                        "idarticulo": 3,
                        "detalle_ingresocantidad": 10,
                        "detalle_ingresoprecio_compra": "15.00",
                        "detalle_ingresoprecio_venta": "20.00"
                    }
                ]
            }
        }

# DTOs de salida (Output) - CON detalles
class ArticuloOut(BaseModel):
    idarticulo: int
    articulonombre: str
    articulocodigo: Optional[str] = None
    articulostock: Optional[int] = None

    class Config:
        orm_mode = True

class DetalleIngresoOut(BaseModel):
    iddetalle_ingreso: int
    idarticulo: int
    detalle_ingresocantidad: int
    detalle_ingresoprecio_compra: str
    detalle_ingresoprecio_venta: str
    articulo: Optional[ArticuloOut] = None

    class Config:
        orm_mode = True
        
class ActualizarCompraDTO(BaseModel):
    ingresotipo_comprobante: Optional[str] = None
    ingresoserie_comprobante: Optional[str] = None
    ingresonumero_comprobante: Optional[str] = None
    ingresofecha_hora: Optional[datetime] = None
    ingresoimpuesto: Optional[float] = None
    ingresototal_compra: Optional[float] = None
    idproveedor: Optional[int] = None
    ingresocondicion: Optional[str] = None
    # NO incluir detalles en ActualizarCompraDTO para evitar confusión

    class Config:
        json_schema_extra = {
            "example": {
                "ingresotipo_comprobante": "Factura",
                "ingresoserie_comprobante": "A001",
                "ingresonumero_comprobante": "000123",
                "ingresofecha_hora": "2025-08-07T22:04:05.961Z",
                "ingresoimpuesto": 13.0,
                "ingresototal_compra": 250.50,
                "idproveedor": 2,
                "ingresocondicion": "1"
            }
        }
# DTOs de salida (Output) - SIN detalles por ahora
class CompraOut(BaseModel):
    idingreso: int
    ingresotipo_comprobante: str
    ingresoserie_comprobante: Optional[str] = None
    ingresonumero_comprobante: Optional[str] = None
    ingresofecha_hora: Optional[datetime] = None
    ingresoimpuesto: Optional[float] = 0.0
    ingresototal_compra: float
    idproveedor: int
    ingresocondicion: Optional[str] = "1"
    detalles: List[DetalleIngresoOut] = []  # Ahora incluye detalles

    class Config:
        orm_mode = True