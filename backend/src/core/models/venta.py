from sqlalchemy import Column, Integer, String, Date, Numeric
from infrastructure.data.AppDbContext import Base

class Venta(Base):
    __tablename__ = "venta"

    idventa = Column(Integer, primary_key=True, index=True)
    ventatipo_comprobante = Column(Integer, nullable=False)
    ventaserie_comprobante = Column(Integer, default=0)
    ventanum_comprobante = Column(Integer, default=0)
    ventafecha_hora = Column(Date)
    ventaimpuesto = Column(Integer, default=0)
    ventatotal_venta = Column(String(20), default="0.0")
    idcliente = Column(Integer, nullable=False)
    ventacondicion = Column(Integer, default=1)
    ventapago_cliente = Column(Numeric(12, 2), nullable=True)
    ventacambio = Column(Numeric(12, 2), nullable=True)
