from sqlalchemy import Column, Integer, String, SmallInteger
from infrastructure.data.AppDbContext import Base

class Rol(Base):
    __tablename__ = "rol"

    idrol = Column(Integer, primary_key=True, index=True)
    rolnombre = Column(String(100), nullable=False)
    rolcondicion = Column(SmallInteger, default=1, nullable=False)
