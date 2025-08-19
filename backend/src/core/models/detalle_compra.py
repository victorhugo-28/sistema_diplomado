# core/models/detalle_ingreso.py (corregido)
from sqlalchemy import Column, Integer, String, ForeignKey
from sqlalchemy.orm import relationship
from infrastructure.data.AppDbContext import Base

class DetalleIngreso(Base):
    __tablename__ = "detalle_ingreso"

    iddetalle_ingreso = Column(Integer, primary_key=True, index=True)
    detalle_ingresocantidad = Column(Integer, nullable=False)
    detalle_ingresoprecio_compra = Column(String(15), nullable=False, default="0.00")
    detalle_ingresoprecio_venta = Column(String(15), nullable=False, default="0.00")

    idingreso = Column(Integer, ForeignKey("ingreso.idingreso"), nullable=False)
    idarticulo = Column(Integer, ForeignKey("articulos.idarticulo"), nullable=False)

    # Relaciones corregidas
    compra = relationship("Compra", back_populates="detalles")  # Suponiendo que Ingreso se llama Compra
    articulo = relationship("Articulo")
