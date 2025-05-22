from core.interfaces.cita_repository import CitaRepositoryInterface
from core.models.cita import Cita
from infrastructure.data.AppDbContext import SessionLocal

class CitaRepositoryImpl(CitaRepositoryInterface):
    def listar_todas(self):
        session = SessionLocal()
        try:
            return session.query(Cita).all()
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        session = SessionLocal()
        try:
            return session.query(Cita).filter_by(id=id).first()
        finally:
            session.close()

    def crear(self, cita: Cita):
        session = SessionLocal()
        try:
            session.add(cita)
            session.commit()
            session.refresh(cita)
            return cita
        finally:
            session.close()

    def actualizar(self, cita: Cita):
        session = SessionLocal()
        try:
            cita = session.merge(cita)
            session.commit()
            return cita
        finally:
            session.close()

    def eliminar(self, id: int):
        session = SessionLocal()
        try:
            cita = session.query(Cita).filter_by(id=id).first()
            if not cita:
                return False
            session.delete(cita)
            session.commit()
            return True
        finally:
            session.close()
