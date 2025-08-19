# core/models/detalle_venta.py
from sqlalchemy import Column, Integer, String, ForeignKey
from infrastructure.data.AppDbContext import Base
from sqlalchemy.orm import relationship

class DetalleVenta(Base):
    __tablename__ = "detalle_venta"

    iddetalle_venta = Column(Integer, primary_key=True, index=True)
    detalle_ventacantidad = Column(Integer, nullable=False)
    detalle_ventaprecio_venta = Column(String(20), default="0.0")
    detalle_ventadescuento = Column(String(20), default="0.0")

    idventa = Column(Integer, ForeignKey("venta.idventa"), nullable=False)
    idarticulo = Column(Integer, ForeignKey("articulos.idarticulo"), nullable=False)

    venta = relationship("Venta", back_populates="detalles")
    articulo = relationship("Articulo")
    