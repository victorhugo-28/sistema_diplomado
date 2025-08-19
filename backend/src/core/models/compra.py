# core/models/compra.py (versión temporal sin relaciones)
from sqlalchemy import Column, Integer, String, Float, DateTime
from infrastructure.data.AppDbContext import Base
from datetime import datetime
from sqlalchemy.orm import relationship

class Compra(Base):
    __tablename__ = "ingreso"

    idingreso = Column(Integer, primary_key=True, index=True)
    ingresotipo_comprobante = Column(String(50), nullable=False)
    ingresoserie_comprobante = Column(String(10))
    ingresonumero_comprobante = Column(String(20))
    ingresofecha_hora = Column(DateTime, default=datetime.utcnow)
    ingresoimpuesto = Column(Float, default=0.0)
    ingresototal_compra = Column(Float, nullable=False)
    idproveedor = Column(Integer, nullable=False)  # Sin ForeignKey por ahora
    ingresocondicion = Column(String(10), default="1")

    # Comentamos las relaciones hasta resolver el problema
    # proveedor = relationship("Proveedor", back_populates="compras")
    detalles = relationship("DetalleIngreso", back_populates="compra", cascade="all, delete-orphan")