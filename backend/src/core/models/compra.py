from sqlalchemy import Column, Integer, String, DateTime, Float, ForeignKey
from infrastructure.data.AppDbContext import Base

class Compra(Base):
    __tablename__ = "ingreso"

    idingreso = Column(Integer, primary_key=True, index=True)
    ingresotipo_comprobante = Column(String)
    ingresoserie_comprobante = Column(String)
    ingresonumero_comprobante = Column(String)
    ingresofecha_hora = Column(DateTime)
    ingresoimpuesto = Column(Float)
    ingresototal_compra = Column(Float)
    idproveedor = Column(Integer, ForeignKey("proveedores.id"))  # ← CORREGIDO
    ingresocondicion = Column(String)
