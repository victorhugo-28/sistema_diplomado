# core/handlers/venta/crear_venta_handler.py
from core.dto.venta_dto import CrearVentaDTO
from core.models.venta import Venta
from infrastructure.data.venta_repository_impl import VentaRepositoryImpl
from infrastructure.data.detalle_venta_repository_impl import DetalleVentaRepositoryImpl
from sqlalchemy.orm import Session
from infrastructure.data.AppDbContext import SessionLocal

class CrearVentaHandler:
    def __init__(self, venta_repo: VentaRepositoryImpl, detalle_repo: DetalleVentaRepositoryImpl):
        self.venta_repo = venta_repo
        self.detalle_repo = detalle_repo

    def handle(self, data: CrearVentaDTO):
        # ⚠️ Pydantic v1
        venta_data = data.dict(exclude={"detalles"})
        # 1) Crear el modelo y dejar que el repo lo persista (usa su propia sesión/commit)
        venta_model = Venta(**venta_data)
        nueva_venta = self.venta_repo.crear(venta_model)  # <- SIN db, SIN dict

        # 2) Insertar detalles en otra sesión (transacción separada)
        db: Session = SessionLocal()
        try:
            for detalle in data.detalles:
                d = detalle.dict()
                d["idventa"] = nueva_venta.idventa
                self.detalle_repo.crear(db, d)  # este repo sí espera db
            db.commit()
        except:
            db.rollback()
            raise
        finally:
            db.close()

        return {"message": "Venta registrada con éxito", "idventa": nueva_venta.idventa}
