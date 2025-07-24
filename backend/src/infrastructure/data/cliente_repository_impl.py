from core.models.cliente import Cliente
from infrastructure.data.AppDbContext import SessionLocal
from core.interfaces.cliente_repository import ClienteRepositoryInterface

class ClienteRepositoryImpl(ClienteRepositoryInterface):
    def listar_todos(self):
        session = SessionLocal()
        try:
            return session.query(Cliente).all()
        finally:
            session.close()

    def crear(self, cliente: Cliente):
        session = SessionLocal()
        try:
            session.add(cliente)
            session.commit()
            session.refresh(cliente)
            return cliente
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        session = SessionLocal()
        try:
            return session.query(Cliente).filter_by(id=id).first()
        finally:
            session.close()

    def actualizar(self, id: int, data: dict):
        session = SessionLocal()
        try:
            cliente = session.query(Cliente).filter_by(id=id).first()
            if not cliente:
                return None
            for key, value in data.items():
                setattr(cliente, key, value)
            session.commit()
            session.refresh(cliente)
            return cliente
        finally:
            session.close()

    def eliminar(self, id: int) -> bool:
        session = SessionLocal()
        try:
            cliente = session.query(Cliente).filter_by(id=id).first()
            if not cliente:
                return False
            session.delete(cliente)
            session.commit()
            return True
        finally:
            session.close()
