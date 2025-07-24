from pydantic import BaseModel
from typing import Optional

class CrearRolDTO(BaseModel):
    rolnombre: str
    rolcondicion: Optional[int] = 1
