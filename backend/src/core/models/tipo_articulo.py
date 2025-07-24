from sqlalchemy import Column, Integer, String
from infrastructure.data.AppDbContext import Base

class TipoArticulo(Base):
    __tablename__ = "tipos_articulo"

    id = Column(Integer, primary_key=True, index=True)
    nombre = Column(String)
