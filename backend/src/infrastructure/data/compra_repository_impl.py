# infrastructure/data/compra_repository_impl.py
from core.models.compra import Compra
from core.models.detalle_compra import DetalleIngreso  # Importar DetalleIngreso
from infrastructure.data.AppDbContext import SessionLocal
from core.interfaces.compra_repository import CompraRepositoryInterface
from datetime import datetime

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

    def actualizar(self, id: int, cambios: dict):
        session = SessionLocal()
        try:
            compra = session.query(Compra).filter_by(idingreso=id).first()
            if not compra:
                return None

            columnas = {c.name for c in Compra.__table__.columns}
            cambios_filtrados = {k: v for k, v in cambios.items() if k in columnas}

            if "ingresofecha_hora" in cambios_filtrados and isinstance(cambios_filtrados["ingresofecha_hora"], str):
                cambios_filtrados["ingresofecha_hora"] = datetime.fromisoformat(cambios_filtrados["ingresofecha_hora"])

            for k, v in cambios_filtrados.items():
                setattr(compra, k, v)

            session.commit()
            session.refresh(compra)
            return compra
        finally:
            session.close()

    def listar_con_detalles(self, limit=50, offset=0):
        session = SessionLocal()
        try:
            # Ahora con joins para incluir detalles
            from sqlalchemy.orm import joinedload
            return (session.query(Compra)
                    .options(joinedload(Compra.detalles)
                            .joinedload(DetalleIngreso.articulo))
                    .order_by(Compra.idingreso.desc())
                    .limit(limit).offset(offset).all())
        finally:
            session.close()

    def obtener_por_id_con_detalles(self, id: int):
        session = SessionLocal()
        try:
            # Con joins para incluir detalles
            from sqlalchemy.orm import joinedload
            return (session.query(Compra)
                    .options(joinedload(Compra.detalles)
                            .joinedload(DetalleIngreso.articulo))
                    .filter(Compra.idingreso == id).first())
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