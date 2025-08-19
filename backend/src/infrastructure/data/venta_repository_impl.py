# infrastructure/data/venta_repository_impl.py (VERSIÓN COMPLETA)
from infrastructure.data.AppDbContext import SessionLocal
from core.interfaces.venta_repository import VentaRepositoryInterface
from core.services.stock_service import StockService
from datetime import datetime
from sqlalchemy import text
from sqlalchemy.orm import joinedload

class VentaRepositoryImpl(VentaRepositoryInterface):
    def __init__(self):
        self.stock_service = StockService()
    
    def listar_todos(self):
        session = SessionLocal()
        try:
            # Usar SQL puro para evitar problemas de relaciones
            query = text("""
                SELECT 
                    idventa, ventatipo_comprobante, ventaserie_comprobante, 
                    ventanum_comprobante, ventafecha_hora, ventaimpuesto,
                    ventatotal_venta, idcliente, ventacondicion, 
                    ventapago_cliente, ventacambio
                FROM venta
                ORDER BY idventa DESC
            """)
            
            resultados = session.execute(query).fetchall()
            
            ventas_lista = []
            for venta in resultados:
                venta_dict = {
                    'idventa': venta[0],
                    'ventatipo_comprobante': venta[1],
                    'ventaserie_comprobante': venta[2],
                    'ventanum_comprobante': venta[3],
                    'ventafecha_hora': venta[4],
                    'ventaimpuesto': venta[5],
                    'ventatotal_venta': venta[6],
                    'idcliente': venta[7],
                    'ventacondicion': venta[8],
                    'ventapago_cliente': float(venta[9]) if venta[9] else None,
                    'ventacambio': float(venta[10]) if venta[10] else None
                }
                ventas_lista.append(venta_dict)
            
            return ventas_lista
        finally:
            session.close()

    def crear(self, venta):
        # Este método mantiene compatibilidad con handlers existentes
        # pero internamente usa el nuevo método
        if hasattr(venta, 'detalles'):
            # Es un objeto Venta con detalles
            venta_data = {
                'ventatipo_comprobante': venta.ventatipo_comprobante,
                'ventaserie_comprobante': str(venta.ventaserie_comprobante),
                'ventanum_comprobante': venta.ventanum_comprobante,
                'ventafecha_hora': venta.ventafecha_hora.strftime('%Y-%m-%d %H:%M:%S') if venta.ventafecha_hora else None,
                'ventaimpuesto': venta.ventaimpuesto,
                'ventatotal_venta': str(venta.ventatotal_venta),
                'idcliente': venta.idcliente,
                'ventacondicion': venta.ventacondicion,
                'ventapago_cliente': venta.ventapago_cliente,
                'ventacambio': venta.ventacambio
            }
            
            detalles = []
            for detalle in venta.detalles:
                detalle_dict = {
                    'idarticulo': detalle.idarticulo,
                    'detalle_ventacantidad': detalle.detalle_ventacantidad,
                    'detalle_ventaprecio_venta': str(detalle.detalle_ventaprecio_venta),
                    'detalle_ventadescuento': str(detalle.detalle_ventadescuento) if detalle.detalle_ventadescuento else "0.0"
                }
                detalles.append(detalle_dict)
            
            return self.crear_venta_con_detalles(venta_data, detalles)
        else:
            raise Exception("Método crear requiere objeto Venta con detalles")

    def crear_venta_con_detalles(self, venta_data: dict, detalles: list):
        """Crea una venta con sus detalles usando SQL puro"""
        session = SessionLocal()
        try:
            print(f"Creando venta con datos: {venta_data}")
            print(f"Detalles: {detalles}")
            
            # Insertar venta principal
            campos_venta = [
                'ventatipo_comprobante', 'ventaserie_comprobante', 'ventanum_comprobante',
                'ventafecha_hora', 'ventaimpuesto', 'ventatotal_venta', 'idcliente',
                'ventacondicion', 'ventapago_cliente', 'ventacambio'
            ]
            
            # Filtrar datos de venta
            datos_filtrados = {k: v for k, v in venta_data.items() if k in campos_venta and v is not None}
            
            columnas_str = ", ".join(datos_filtrados.keys())
            valores_str = ", ".join([f":{col}" for col in datos_filtrados.keys()])
            
            query_venta = text(f"""
                INSERT INTO venta ({columnas_str})
                VALUES ({valores_str})
            """)
            
            session.execute(query_venta, datos_filtrados)
            
            # Obtener ID de la venta recién creada
            query_last_id = text("SELECT last_insert_rowid()")
            venta_id = session.execute(query_last_id).fetchone()[0]
            
            print(f"Venta creada con ID: {venta_id}")
            
            # Insertar detalles de venta
            for detalle in detalles:
                detalle_data = {
                    'idventa': venta_id,
                    'idarticulo': detalle['idarticulo'],
                    'detalle_ventacantidad': detalle['detalle_ventacantidad'],
                    'detalle_ventaprecio_venta': detalle['detalle_ventaprecio_venta'],
                    'detalle_ventadescuento': detalle.get('detalle_ventadescuento', '0.0')
                }
                
                query_detalle = text("""
                    INSERT INTO detalle_venta (idventa, idarticulo, detalle_ventacantidad, detalle_ventaprecio_venta, detalle_ventadescuento)
                    VALUES (:idventa, :idarticulo, :detalle_ventacantidad, :detalle_ventaprecio_venta, :detalle_ventadescuento)
                """)
                
                session.execute(query_detalle, detalle_data)
                print(f"Detalle insertado: {detalle_data}")
            
            session.commit()
            
            # Devolver la venta creada
            return {
                'idventa': venta_id,
                **datos_filtrados
            }
            
        except Exception as e:
            session.rollback()
            print(f"Error al crear venta con detalles: {e}")
            raise e
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        session = SessionLocal()
        try:
            query = text("""
                SELECT 
                    idventa, ventatipo_comprobante, ventaserie_comprobante, 
                    ventanum_comprobante, ventafecha_hora, ventaimpuesto,
                    ventatotal_venta, idcliente, ventacondicion, 
                    ventapago_cliente, ventacambio
                FROM venta
                WHERE idventa = :id
            """)
            
            resultado = session.execute(query, {"id": id}).fetchone()
            
            if not resultado:
                return None
                
            return {
                'idventa': resultado[0],
                'ventatipo_comprobante': resultado[1],
                'ventaserie_comprobante': resultado[2],
                'ventanum_comprobante': resultado[3],
                'ventafecha_hora': resultado[4],
                'ventaimpuesto': resultado[5],
                'ventatotal_venta': resultado[6],
                'idcliente': resultado[7],
                'ventacondicion': resultado[8],
                'ventapago_cliente': float(resultado[9]) if resultado[9] else None,
                'ventacambio': float(resultado[10]) if resultado[10] else None
            }
        finally:
            session.close()

    def actualizar(self, id: int, cambios: dict):
        session = SessionLocal()
        try:
            venta = self.obtener_por_id(id)
            if not venta:
                return None

            # Campos permitidos para actualizar
            campos_permitidos = [
                'ventatipo_comprobante', 'ventaserie_comprobante', 'ventanum_comprobante',
                'ventafecha_hora', 'ventaimpuesto', 'ventatotal_venta', 'idcliente',
                'ventacondicion', 'ventapago_cliente', 'ventacambio'
            ]

            datos_filtrados = {}
            for campo in campos_permitidos:
                if campo in cambios and cambios[campo] is not None:
                    valor = cambios[campo]
                    
                    # Manejo especial para fechas datetime
                    if campo == "ventafecha_hora" and isinstance(valor, str):
                        try:
                            valor = datetime.fromisoformat(valor).strftime('%Y-%m-%d %H:%M:%S')
                        except:
                            continue
                    
                    datos_filtrados[campo] = valor

            if not datos_filtrados:
                return venta  # No hay nada que actualizar

            # Construir query de actualización
            set_clause = ", ".join([f"{campo} = :{campo}" for campo in datos_filtrados.keys()])
            
            query_update = text(f"""
                UPDATE venta 
                SET {set_clause}
                WHERE idventa = :id
            """)

            params = {**datos_filtrados, "id": id}
            session.execute(query_update, params)
            session.commit()

            # Devolver el registro actualizado
            return self.obtener_por_id(id)
        finally:
            session.close()

    def eliminar(self, id: int) -> bool:
        """Elimina una venta y restaura el stock"""
        session = SessionLocal()
        try:
            print(f"Intentando eliminar venta con ID: {id}")
            
            # Primero obtener los detalles para restaurar stock
            query_detalles = text("""
                SELECT idarticulo, detalle_ventacantidad 
                FROM detalle_venta 
                WHERE idventa = :venta_id
            """)
            
            detalles = session.execute(query_detalles, {"venta_id": id}).fetchall()
            
            if not detalles:
                print(f"No se encontraron detalles para la venta {id}")
                return False  # No existe la venta
            
            print(f"Detalles encontrados para eliminar: {len(detalles)}")
            
            # Preparar lista para restaurar stock
            articulos_cantidad = [(detalle[0], detalle[1]) for detalle in detalles]
            
            # Eliminar detalles primero (FK constraint)
            query_delete_detalles = text("DELETE FROM detalle_venta WHERE idventa = :venta_id")
            result_detalles = session.execute(query_delete_detalles, {"venta_id": id})
            print(f"Detalles eliminados: {result_detalles.rowcount}")
            
            # Eliminar la venta
            query_delete_venta = text("DELETE FROM venta WHERE idventa = :venta_id")
            result_venta = session.execute(query_delete_venta, {"venta_id": id})
            print(f"Venta eliminada: {result_venta.rowcount}")
            
            if result_venta.rowcount == 0:
                session.rollback()
                print("No se eliminó ninguna venta")
                return False
            
            session.commit()
            print("Transacción de eliminación confirmada")
            
            # Restaurar stock después de eliminar exitosamente
            if articulos_cantidad:
                stock_restaurado = self.stock_service.restaurar_stock_multiple(articulos_cantidad)
                print(f"Stock restaurado: {stock_restaurado}")
            
            return True
            
        except Exception as e:
            session.rollback()
            print(f"Error al eliminar venta: {e}")
            return False
        finally:
            session.close()

    # Métodos adicionales que ya teníamos
    def listar_con_detalles(self, limit=50, offset=0):
        """Lista ventas con detalles usando SQL puro"""
        session = SessionLocal()
        try:
            from datetime import datetime
            
            # Consulta para obtener ventas con paginación
            query_ventas = text("""
                SELECT 
                    idventa, ventatipo_comprobante, ventaserie_comprobante,
                    ventanum_comprobante, ventafecha_hora, ventaimpuesto,
                    ventatotal_venta, idcliente, ventacondicion,
                    ventapago_cliente, ventacambio
                FROM venta 
                ORDER BY idventa DESC
                LIMIT :limit OFFSET :offset
            """)
            
            resultados_ventas = session.execute(query_ventas, {"limit": limit, "offset": offset}).fetchall()
            
            ventas_lista = []
            
            for venta in resultados_ventas:
                # Manejar la fecha correctamente
                fecha_hora = venta[4]
                if fecha_hora:
                    if isinstance(fecha_hora, str):
                        try:
                            fecha_hora = datetime.fromisoformat(fecha_hora.replace('Z', '+00:00'))
                        except ValueError:
                            try:
                                fecha_hora = datetime.strptime(fecha_hora, '%Y-%m-%d %H:%M:%S')
                            except ValueError:
                                fecha_hora = None
                    elif not isinstance(fecha_hora, datetime):
                        fecha_hora = None
                
                venta_dict = {
                    'idventa': venta[0],
                    'ventatipo_comprobante': venta[1],
                    'ventaserie_comprobante': str(venta[2]) if venta[2] is not None else "0",
                    'ventanum_comprobante': venta[3],
                    'ventafecha_hora': fecha_hora,
                    'ventaimpuesto': venta[5],
                    'ventatotal_venta': str(venta[6]),
                    'idcliente': venta[7],
                    'ventacondicion': venta[8],
                    'ventapago_cliente': float(venta[9]) if venta[9] else None,
                    'ventacambio': float(venta[10]) if venta[10] else None,
                    'detalles': []
                }
                
                # Obtener detalles para cada venta
                query_detalles = text("""
                    SELECT 
                        dv.iddetalle_venta, dv.idarticulo, dv.detalle_ventacantidad,
                        dv.detalle_ventaprecio_venta, dv.detalle_ventadescuento,
                        a.articulonombre, a.articulocodigo, a.articulostock
                    FROM detalle_venta dv
                    LEFT JOIN articulos a ON dv.idarticulo = a.idarticulo
                    WHERE dv.idventa = :venta_id
                """)
                
                detalles = session.execute(query_detalles, {"venta_id": venta[0]}).fetchall()
                
                for detalle in detalles:
                    detalle_dict = {
                        'iddetalle_venta': detalle[0],
                        'idarticulo': detalle[1],
                        'detalle_ventacantidad': detalle[2],
                        'detalle_ventaprecio_venta': str(detalle[3]),
                        'detalle_ventadescuento': str(detalle[4]) if detalle[4] else "0.0",
                        'articulo': {
                            'idarticulo': detalle[1],
                            'articulonombre': detalle[5],
                            'articulocodigo': detalle[6],
                            'articulostock': detalle[7]
                        } if detalle[5] else None
                    }
                    venta_dict['detalles'].append(detalle_dict)
                
                ventas_lista.append(venta_dict)
            
            return ventas_lista
            
        finally:
            session.close()

    def obtener_por_id_con_detalles(self, id: int):
        """Obtiene una venta con sus detalles usando SQL puro"""
        session = SessionLocal()
        try:
            from datetime import datetime
            
            # Consulta SQL pura para obtener la venta
            query_venta = text("""
                SELECT 
                    idventa, ventatipo_comprobante, ventaserie_comprobante,
                    ventanum_comprobante, ventafecha_hora, ventaimpuesto,
                    ventatotal_venta, idcliente, ventacondicion,
                    ventapago_cliente, ventacambio
                FROM venta 
                WHERE idventa = :venta_id
            """)
            
            resultado_venta = session.execute(query_venta, {"venta_id": id}).fetchone()
            
            if not resultado_venta:
                return None
            
            # Manejar la fecha correctamente
            fecha_hora = resultado_venta[4]
            if fecha_hora:
                if isinstance(fecha_hora, str):
                    try:
                        fecha_hora = datetime.fromisoformat(fecha_hora.replace('Z', '+00:00'))
                    except ValueError:
                        try:
                            fecha_hora = datetime.strptime(fecha_hora, '%Y-%m-%d %H:%M:%S')
                        except ValueError:
                            fecha_hora = None
                elif not isinstance(fecha_hora, datetime):
                    fecha_hora = None
            
            # Crear diccionario de venta
            venta_dict = {
                'idventa': resultado_venta[0],
                'ventatipo_comprobante': resultado_venta[1],
                'ventaserie_comprobante': str(resultado_venta[2]) if resultado_venta[2] is not None else "0",
                'ventanum_comprobante': resultado_venta[3],
                'ventafecha_hora': fecha_hora,
                'ventaimpuesto': resultado_venta[5],
                'ventatotal_venta': str(resultado_venta[6]),
                'idcliente': resultado_venta[7],
                'ventacondicion': resultado_venta[8],
                'ventapago_cliente': float(resultado_venta[9]) if resultado_venta[9] else None,
                'ventacambio': float(resultado_venta[10]) if resultado_venta[10] else None,
                'detalles': []
            }
            
            # Consulta para obtener los detalles con información del artículo
            query_detalles = text("""
                SELECT 
                    dv.iddetalle_venta, dv.idarticulo, dv.detalle_ventacantidad,
                    dv.detalle_ventaprecio_venta, dv.detalle_ventadescuento,
                    a.articulonombre, a.articulocodigo, a.articulostock
                FROM detalle_venta dv
                LEFT JOIN articulos a ON dv.idarticulo = a.idarticulo
                WHERE dv.idventa = :venta_id
                ORDER BY dv.iddetalle_venta
            """)
            
            resultados_detalles = session.execute(query_detalles, {"venta_id": id}).fetchall()
            
            for detalle in resultados_detalles:
                detalle_dict = {
                    'iddetalle_venta': detalle[0],
                    'idarticulo': detalle[1],
                    'detalle_ventacantidad': detalle[2],
                    'detalle_ventaprecio_venta': str(detalle[3]),
                    'detalle_ventadescuento': str(detalle[4]) if detalle[4] else "0.0",
                    'articulo': {
                        'idarticulo': detalle[1],
                        'articulonombre': detalle[5],
                        'articulocodigo': detalle[6],
                        'articulostock': detalle[7]
                    } if detalle[5] else None
                }
                venta_dict['detalles'].append(detalle_dict)
            
            return venta_dict
            
        finally:
            session.close()