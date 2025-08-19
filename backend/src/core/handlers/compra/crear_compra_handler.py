# core/handlers/compra/crear_compra_handler.py (versión completa)
from core.dto.compra_dto import CrearCompraDTO
from core.models.compra import Compra
from core.models.detalle_compra import DetalleIngreso
from infrastructure.data.compra_repository_impl import CompraRepositoryImpl
from infrastructure.data.AppDbContext import SessionLocal

class CrearCompraHandler:
    def __init__(self, repo=None):
        self.repo = repo or CompraRepositoryImpl()
    
    def handle(self, dto: CrearCompraDTO):
        session = SessionLocal()
        try:
            # Extraer detalles del DTO
            detalles_data = dto.detalles
            
            # Crear el diccionario de la compra sin los detalles
            compra_dict = dto.dict()
            del compra_dict['detalles']
            
            # Crear la instancia de Compra
            compra = Compra(**compra_dict)
            
            # Guardar la compra
            session.add(compra)
            session.commit()
            session.refresh(compra)
            
            # Crear los detalles si existen
            if detalles_data:
                for detalle_data in detalles_data:
                    detalle = DetalleIngreso(
                        idingreso=compra.idingreso,
                        idarticulo=detalle_data.idarticulo,
                        detalle_ingresocantidad=detalle_data.detalle_ingresocantidad,
                        detalle_ingresoprecio_compra=detalle_data.detalle_ingresoprecio_compra,
                        detalle_ingresoprecio_venta=detalle_data.detalle_ingresoprecio_venta
                    )
                    session.add(detalle)
                
                session.commit()
            
            # Obtener la compra completa con detalles para retornar
            compra_completa = self.repo.obtener_por_id_con_detalles(compra.idingreso)
            return compra_completa
            
        except Exception as e:
            session.rollback()
            raise e
        finally:
            session.close()