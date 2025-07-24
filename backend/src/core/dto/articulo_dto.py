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
    articulofecha: Optional[date]
    articulohora: Optional[time]
    id_tipo: int
    id_proveedor: int

class ArticuloDTO(CrearArticuloDTO):
    idarticulo: int

    class Config:
        orm_mode = True 
