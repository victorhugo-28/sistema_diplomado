from infrastructure.data.AppDbContext import engine
from core.models.usuario import Usuario, Base
from core.models.cita import Cita, Base

Base.metadata.create_all(bind=engine)
print("Base dedatos creada")
