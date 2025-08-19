# core/models/articulo.py
from sqlalchemy import Column, Integer, String, Date, Time, ForeignKey
from sqlalchemy.orm import relationship
from infrastructure.data.AppDbContext import Base

class Articulo(Base):
    __tablename__ = "articulos"

    idarticulo = Column(Integer, primary_key=True, index=True)
    articulonombre = Column(String(100), nullable=False)
    articulostock = Column(Integer, default=0)
    articulodescripcion = Column(String(255))
    articuloimagen = Column(String(255))
    articulocodigogener = Column(String(50))
    articulocodigo = Column(String(50))
    articulofecha = Column(Date)
    articulohora = Column(Time)

    id_tipo = Column(Integer, ForeignKey("tipos_articulo.id"), nullable=False)

    id_proveedor = Column(Integer, ForeignKey("proveedores.id"), nullable=False)

    # Relaciones
    tipo_articulo = relationship("TipoArticulo", back_populates="articulos")
    proveedor = relationship("Proveedor", back_populates="articulos")
    detalles_ingreso = relationship("DetalleIngreso", back_populates="articulo")
    detalles_venta = relationship("DetalleVenta", back_populates="articulo")
