from core.models.venta import Venta
from infrastructure.data.AppDbContext import SessionLocal
from core.interfaces.venta_repository import VentaRepositoryInterface

class VentaRepositoryImpl(VentaRepositoryInterface):
    def listar_todos(self):
        session = SessionLocal()
        try:
            return session.query(Venta).all()
        finally:
            session.close()

    def crear(self, venta: Venta):
        session = SessionLocal()
        try:
            session.add(venta)
            session.commit()
            session.refresh(venta)
            return venta
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        session = SessionLocal()
        try:
            return session.query(Venta).filter_by(idventa=id).first()
        finally:
            session.close()

    def actualizar(self, venta: Venta):
        session = SessionLocal()
        try:
            v = session.query(Venta).filter_by(idventa=venta.idventa).first()
            if not v:
                return None
            for attr, value in vars(venta).items():
                if attr != "_sa_instance_state":
                    setattr(v, attr, value)
            session.commit()
            session.refresh(v)
            return v
        finally:
            session.close()

    def eliminar(self, id: int) -> bool:
        session = SessionLocal()
        try:
            v = session.query(Venta).filter_by(idventa=id).first()
            if not v:
                return False
            session.delete(v)
            session.commit()
            return True
        finally:
            session.close()
