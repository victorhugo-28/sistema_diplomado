from pydantic import BaseModel
from typing import Optional
from datetime import date

class CrearVentaDTO(BaseModel):
    ventatipo_comprobante: int
    ventaserie_comprobante: Optional[int] = 0
    ventanum_comprobante: Optional[int] = 0
    ventafecha_hora: Optional[date]
    ventaimpuesto: Optional[int] = 0
    ventatotal_venta: str
    idcliente: int
    ventacondicion: Optional[int] = 1
    ventapago_cliente: Optional[float] = 0.0
    ventacambio: Optional[float] = 0.0
