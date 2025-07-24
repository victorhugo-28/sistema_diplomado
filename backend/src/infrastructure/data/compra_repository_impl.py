from core.models.compra import Compra
from infrastructure.data.AppDbContext import SessionLocal
from core.interfaces.compra_repository import CompraRepositoryInterface

class CompraRepositoryImpl(CompraRepositoryInterface):
    def listar_todos(self):
        session = SessionLocal()
        try:
            return session.query(Compra).all()
        finally:
            session.close()

    def crear(self, compra: Compra):
        session = SessionLocal()
        try:
            session.add(compra)
            session.commit()
            session.refresh(compra)
            return compra
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        session = SessionLocal()
        try:
            return session.query(Compra).filter_by(idingreso=id).first()
        finally:
            session.close()

    def actualizar(self, id: int, data: dict):
        session = SessionLocal()
        try:
            compra = session.query(Compra).filter_by(idingreso=id).first()
            if not compra:
                return None
            for key, value in data.items():
                setattr(compra, key, value)
            session.commit()
            session.refresh(compra)
            return compra
        finally:
            session.close()

    def eliminar(self, id: int) -> bool:
        session = SessionLocal()
        try:
            compra = session.query(Compra).filter_by(idingreso=id).first()
            if not compra:
                return False
            session.delete(compra)
            session.commit()
            return True
        finally:
            session.close()
