from sqlalchemy import Column, Integer, String, DateTime, ForeignKey
from sqlalchemy.orm import relationship
from infrastructure.data.AppDbContext import Base  # Usa el mismo Base

class Cita(Base):
    __tablename__ = "citas"

    id = Column(Integer, primary_key=True)
    fecha = Column(DateTime)
    descripcion = Column(String)
    usuario_id = Column(Integer, ForeignKey("usuarios.id"))

    usuario = relationship("Usuario", backref="citas")
