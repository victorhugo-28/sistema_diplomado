from core.models.tipo_articulo import TipoArticulo
from infrastructure.data.AppDbContext import SessionLocal
from core.interfaces.tipo_articulo_repository import TipoArticuloRepositoryInterface

class TipoArticuloRepositoryImpl(TipoArticuloRepositoryInterface):
    def listar_todos(self):
        session = SessionLocal()
        try:
            return session.query(TipoArticulo).all()
        finally:
            session.close()

    def crear(self, tipo: TipoArticulo):
        session = SessionLocal()
        try:
            session.add(tipo)
            session.commit()
            session.refresh(tipo)
            return tipo
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        session = SessionLocal()
        try:
            return session.query(TipoArticulo).filter_by(id=id).first()
        finally:
            session.close()

    def actualizar(self, id: int, nombre: str):
        session = SessionLocal()
        try:
            tipo = session.query(TipoArticulo).filter_by(id=id).first()
            if not tipo:
                return None
            tipo.nombre = nombre
            session.commit()
            session.refresh(tipo)
            return {
                "id": tipo.id,
                "nombre": tipo.nombre
            }
        finally:
            session.close()

    def eliminar(self, id: int) -> bool:
        session = SessionLocal()
        try:
            tipo = session.query(TipoArticulo).filter_by(id=id).first()
            if not tipo:
                return False
            session.delete(tipo)
            session.commit()
            return True
        finally:
            session.close()
