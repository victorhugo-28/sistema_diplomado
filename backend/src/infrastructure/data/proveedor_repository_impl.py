from core.models.proveedor import Proveedor
from infrastructure.data.AppDbContext import SessionLocal
from core.interfaces.proveedor_repository import ProveedorRepositoryInterface

class ProveedorRepositoryImpl(ProveedorRepositoryInterface):
    def listar_todos(self):
        session = SessionLocal()
        try:
            return session.query(Proveedor).all()
        finally:
            session.close()

    def crear(self, proveedor: Proveedor):
        session = SessionLocal()
        try:
            session.add(proveedor)
            session.commit()
            session.refresh(proveedor)
            return proveedor
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        session = SessionLocal()
        try:
            return session.query(Proveedor).filter_by(id=id).first()
        finally:
            session.close()

    def actualizar(self, id: int, nombre: str, contacto: str, direccion: str):
        session = SessionLocal()
        try:
            proveedor = session.query(Proveedor).filter_by(id=id).first()
            if not proveedor:
                return None
            proveedor.nombre = nombre
            proveedor.contacto = contacto
            proveedor.direccion = direccion
            session.commit()

            datos = {
                "id": proveedor.id,
                "nombre": proveedor.nombre,
                "contacto": proveedor.contacto,
                "direccion": proveedor.direccion
            }

            return datos
        finally:
            session.close()

    def eliminar(self, id: int) -> bool:
        session = SessionLocal()
        try:
            proveedor = session.query(Proveedor).filter_by(id=id).first()
            if not proveedor:
                return False
            session.delete(proveedor)
            session.commit()
            return True
        finally:
            session.close()
