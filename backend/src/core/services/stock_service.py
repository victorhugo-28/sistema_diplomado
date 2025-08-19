# core/services/stock_service.py (crear este archivo)
from infrastructure.data.AppDbContext import SessionLocal
from sqlalchemy import text
from typing import List, Tuple

class StockService:
    """Servicio para manejar el stock de artículos"""
    
    def verificar_disponibilidad_multiple(self, articulos_cantidad: List[Tuple[int, int]]) -> Tuple[bool, str]:
        """
        Verifica si hay suficiente stock para múltiples artículos
        Args:
            articulos_cantidad: Lista de tuplas (idarticulo, cantidad_requerida)
        Returns:
            Tuple (es_valido, mensaje_error)
        """
        session = SessionLocal()
        try:
            for idarticulo, cantidad_requerida in articulos_cantidad:
                query = text("SELECT articulonombre, articulostock FROM articulos WHERE idarticulo = :id")
                resultado = session.execute(query, {"id": idarticulo}).fetchone()
                
                if not resultado:
                    return False, f"Artículo con ID {idarticulo} no encontrado"
                
                nombre, stock_actual = resultado[0], resultado[1] or 0
                
                if stock_actual < cantidad_requerida:
                    return False, f"Stock insuficiente para {nombre}. Disponible: {stock_actual}, Requerido: {cantidad_requerida}"
            
            return True, "Stock suficiente"
            
        finally:
            session.close()
    
    def descontar_stock_multiple(self, articulos_cantidad: List[Tuple[int, int]]) -> bool:
        """
        Descuenta stock de múltiples artículos en una transacción
        Args:
            articulos_cantidad: Lista de tuplas (idarticulo, cantidad)
        """
        session = SessionLocal()
        try:
            # verificamos la disponibilidad
            es_valido, mensaje = self.verificar_disponibilidad_multiple(articulos_cantidad)
            if not es_valido:
                print(f"Error en verificación de stock: {mensaje}")
                return False

            # descontamos stock de todos los artículos
            for idarticulo, cantidad in articulos_cantidad:
                query = text("""
                    UPDATE articulos 
                    SET articulostock = articulostock - :cantidad 
                    WHERE idarticulo = :id
                """)
                session.execute(query, {"cantidad": cantidad, "id": idarticulo})
                print(f"Stock descontado: Artículo {idarticulo}, Cantidad {cantidad}")
            
            session.commit()
            return True
            
        except Exception as e:
            session.rollback()
            print(f"Error al descontar stock múltiple: {e}")
            return False
        finally:
            session.close()
    
    def restaurar_stock_multiple(self, articulos_cantidad: List[Tuple[int, int]]) -> bool:
        """
        Restaura stock de múltiples artículos (para cancelaciones)
        """
        session = SessionLocal()
        try:
            for idarticulo, cantidad in articulos_cantidad:
                query = text("""
                    UPDATE articulos 
                    SET articulostock = articulostock + :cantidad 
                    WHERE idarticulo = :id
                """)
                session.execute(query, {"cantidad": cantidad, "id": idarticulo})
                print(f"Stock restaurado: Artículo {idarticulo}, Cantidad {cantidad}")
            
            session.commit()
            return True
            
        except Exception as e:
            session.rollback()
            print(f"Error al restaurar stock múltiple: {e}")
            return False
        finally:
            session.close()



            