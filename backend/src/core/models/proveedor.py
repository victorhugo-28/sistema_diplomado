from sqlalchemy.orm import relationship
from infrastructure.data.AppDbContext import Base
from sqlalchemy import Column, Integer, String

class Proveedor(Base):
    __tablename__ = "proveedores"

    id = Column(Integer, primary_key=True, index=True)
    nombre = Column(String)
    contacto = Column(String)
    direccion = Column(String)

    articulos = relationship("Articulo")
