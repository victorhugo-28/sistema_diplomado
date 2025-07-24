from sqlalchemy import Column, Integer, String, ForeignKey, Date, Time
from infrastructure.data.AppDbContext import Base

class Articulo(Base):
    __tablename__ = "articulos"

    idarticulo = Column(Integer, primary_key=True, index=True)
    articulonombre = Column(String(100), nullable=False)
    articulostock = Column(Integer, default=0)
    articulodescripcion = Column(String(500), nullable=True)
    articuloimagen = Column(String(70), nullable=True)
    articulocodigogener = Column(String(30), nullable=True)
    articulocodigo = Column(String(255), nullable=True)
    articulofecha = Column(Date)
    articulohora = Column(Time)
    id_tipo = Column(Integer, ForeignKey("tipos_articulo.id"))
    id_proveedor = Column(Integer, ForeignKey("proveedores.id"))
