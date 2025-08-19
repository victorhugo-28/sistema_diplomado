# core/models/tipo_articulo.py
from sqlalchemy import Column, Integer, String
from sqlalchemy.orm import relationship
from infrastructure.data.AppDbContext import Base

class TipoArticulo(Base):
    __tablename__ = "tipos_articulo"

    id = Column(Integer, primary_key=True, index=True)
    nombre = Column(String)

    articulos = relationship("Articulo", back_populates="tipo_articulo")
