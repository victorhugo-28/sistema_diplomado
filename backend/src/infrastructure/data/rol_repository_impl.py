from core.models.rol import Rol
from infrastructure.data.AppDbContext import SessionLocal
from core.interfaces.rol_repository import RolRepositoryInterface

class RolRepositoryImpl(RolRepositoryInterface):
    def listar_todos(self):
        session = SessionLocal()
        try:
            return session.query(Rol).all()
        finally:
            session.close()

    def crear(self, rol: Rol):
        session = SessionLocal()
        try:
            session.add(rol)
            session.commit()
            session.refresh(rol)
            return rol
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        session = SessionLocal()
        try:
            return session.query(Rol).filter_by(idrol=id).first()
        finally:
            session.close()

    def actualizar(self, rol: Rol):
        session = SessionLocal()
        try:
            session.merge(rol)  
            session.commit()
            session.refresh(rol)
            return rol
        finally:
            session.close()

    def eliminar(self, id: int) -> bool:
        session = SessionLocal()
        try:
            rol = session.query(Rol).filter_by(idrol=id).first()
            if not rol:
                return False
            session.delete(rol)
            session.commit()
            return True
        finally:
            session.close()
