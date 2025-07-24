from core.models.articulo import Articulo
from infrastructure.data.AppDbContext import SessionLocal
from core.interfaces.articulo_repository import ArticuloRepositoryInterface

class ArticuloRepositoryImpl(ArticuloRepositoryInterface):
    def listar_todos(self):
        session = SessionLocal()
        try:
            return session.query(Articulo).all()
        finally:
            session.close()

    def crear(self, articulo: Articulo):
        session = SessionLocal()
        try:
            session.add(articulo)
            session.commit()
            session.refresh(articulo)
            return articulo
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        session = SessionLocal()
        try:
            return session.query(Articulo).filter_by(idarticulo=id).first()
        finally:
            session.close()

    def actualizar(self, id: int, data: dict):
        session = SessionLocal()
        try:
            articulo = session.query(Articulo).filter_by(idarticulo=id).first()
            if not articulo:
                return None
            for key, value in data.items():
                setattr(articulo, key, value)
            session.commit()
            session.refresh(articulo)
            return articulo
        finally:
            session.close()

    def eliminar(self, id: int) -> bool:
        session = SessionLocal()
        try:
            articulo = session.query(Articulo).filter_by(idarticulo=id).first()
            if not articulo:
                return False
            session.delete(articulo)
            session.commit()
            return True
        finally:
            session.close()
