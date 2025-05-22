from core.models.usuario import Usuario
from infrastructure.data.AppDbContext import SessionLocal
from core.interfaces.usuario_repository import UsuarioRepositoryInterface


class UsuarioRepositoryImpl(UsuarioRepositoryInterface):
    def listar_todos(self):
        session = SessionLocal()
        try:
            return session.query(Usuario).all()
        finally:
            session.close()
    def crear(self, usuario: Usuario):
        session = SessionLocal()
        try:
            session.add(usuario)
            session.commit()
            session.refresh(usuario)
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        session = SessionLocal()
        try:
            return session.query(Usuario).filter_by(id=id).first()
        finally:
            session.close()

    def actualizar(self, id: int, nombre: str, correo: str):
        session = SessionLocal()
        try:
            usuario = session.query(Usuario).filter_by(id=id).first()
            if not usuario:
                return None
            usuario.nombre = nombre
            usuario.correo = correo
            session.commit()

            datos = {
                "id": usuario.id,
                "nombre": usuario.nombre,
                "correo": usuario.correo
            }

            return datos  
        finally:
            session.close()


    def eliminar(self, id: int) -> bool:
        session = SessionLocal()
        try:
            usuario = session.query(Usuario).filter_by(id=id).first()
            if not usuario:
                return False
            session.delete(usuario)
            session.commit()
            return True
        finally:
            session.close()




