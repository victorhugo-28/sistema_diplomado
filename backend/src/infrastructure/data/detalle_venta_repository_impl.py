# infrastructure/data/detalle_venta_repository_impl.py
from infrastructure.data.AppDbContext import SessionLocal
from core.interfaces.detalle_venta_repository import DetalleVentaRepositoryInterface
from core.models.detalle_venta import DetalleVenta
from core.models.articulo import Articulo
from sqlalchemy.orm import joinedload
from typing import List

class DetalleVentaRepositoryImpl(DetalleVentaRepositoryInterface):
    def listar_todos(self):
        session = SessionLocal()
        try:
            return session.query(DetalleVenta).options(joinedload(DetalleVenta.articulo)).all()
        finally:
            session.close()

    def crear(self, detalle_venta: DetalleVenta):
        session = SessionLocal()
        try:
            session.add(detalle_venta)
            session.commit()
            session.refresh(detalle_venta)
            return detalle_venta
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        session = SessionLocal()
        try:
            return session.query(DetalleVenta).options(joinedload(DetalleVenta.articulo)).filter_by(iddetalle_venta=id).first()
        finally:
            session.close()

    def obtener_por_venta_id(self, venta_id: int) -> List[DetalleVenta]:
        session = SessionLocal()
        try:
            return session.query(DetalleVenta).options(joinedload(DetalleVenta.articulo)).filter_by(idventa=venta_id).all()
        finally:
            session.close()

    def actualizar(self, id: int, cambios: dict):
        session = SessionLocal()
        try:
            detalle = session.query(DetalleVenta).filter_by(iddetalle_venta=id).first()
            if not detalle:
                return None

            columnas = {c.name for c in DetalleVenta.__table__.columns}
            cambios_filtrados = {k: v for k, v in cambios.items() if k in columnas}

            for k, v in cambios_filtrados.items():
                setattr(detalle, k, v)

            session.commit()
            session.refresh(detalle)
            return detalle
        finally:
            session.close()

    def eliminar(self, id: int) -> bool:
        session = SessionLocal()
        try:
            detalle = session.query(DetalleVenta).filter_by(iddetalle_venta=id).first()
            if not detalle:
                return False
            session.delete(detalle)
            session.commit()
            return True
        finally:
            session.close()

    def eliminar_por_venta_id(self, venta_id: int) -> bool:
        session = SessionLocal()
        try:
            detalles = session.query(DetalleVenta).filter_by(idventa=venta_id).all()
            if not detalles:
                return False
            
            for detalle in detalles:
                session.delete(detalle)
            
            session.commit()
            return True
        finally:
            session.close()

    def crear_multiples(self, detalles: List[DetalleVenta]):
        """Método adicional para crear múltiples detalles de venta de una vez"""
        session = SessionLocal()
        try:
            session.add_all(detalles)
            session.commit()
            for detalle in detalles:
                session.refresh(detalle)
            return detalles
        finally:
            session.close()

    def listar_con_articulos(self, limit=50, offset=0):
        """Método adicional para listar detalles con información completa de artículos"""
        session = SessionLocal()
        try:
            return (session.query(DetalleVenta)
                    .options(joinedload(DetalleVenta.articulo))
                    .order_by(DetalleVenta.iddetalle_venta.desc())
                    .limit(limit).offset(offset).all())
        finally:
            session.close()