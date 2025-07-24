from pydantic import BaseModel
from typing import Optional
from datetime import datetime

class CrearCompraDTO(BaseModel):
    ingresotipo_comprobante: str
    ingresoserie_comprobante: Optional[str] = None
    ingresonumero_comprobante: Optional[str] = None
    ingresofecha_hora: Optional[datetime] = None
    ingresoimpuesto: Optional[float] = 0.0
    ingresototal_compra: float
    idproveedor: int
    ingresocondicion: Optional[str] = "1"
