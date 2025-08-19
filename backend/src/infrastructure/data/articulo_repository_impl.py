# infrastructure/data/articulo_repository_impl.py (REEMPLAZAR COMPLETAMENTE)
from infrastructure.data.AppDbContext import SessionLocal
from core.interfaces.articulo_repository import ArticuloRepositoryInterface
from sqlalchemy import text
from datetime import datetime
from typing import Dict, Any, List

class ArticuloRepositoryImpl(ArticuloRepositoryInterface):
    def listar_todos(self):
        """Lista todos los artículos usando SQL puro"""
        session = SessionLocal()
        try:
            query = text("""
                SELECT 
                    idarticulo, articulonombre, articulostock, articulodescripcion,
                    articuloimagen, articulocodigogener, articulocodigo,
                    articulofecha, articulohora, id_tipo, id_proveedor
                FROM articulos
                ORDER BY idarticulo DESC
            """)
            
            resultados = session.execute(query).fetchall()
            
            articulos_lista = []
            for articulo in resultados:
                articulo_dict = {
                    'idarticulo': articulo[0],
                    'articulonombre': articulo[1],
                    'articulostock': articulo[2],
                    'articulodescripcion': articulo[3],
                    'articuloimagen': articulo[4],
                    'articulocodigogener': articulo[5],
                    'articulocodigo': articulo[6],
                    'articulofecha': articulo[7],
                    'articulohora': articulo[8],
                    'id_tipo': articulo[9],
                    'id_proveedor': articulo[10]
                }
                articulos_lista.append(articulo_dict)
            
            return articulos_lista
            
        except Exception as e:
            print(f"Error al listar artículos: {e}")
            return []
        finally:
            session.close()

    def crear(self, datos):
        """Crea un nuevo artículo - acepta tanto objetos Articulo como diccionarios"""
        session = SessionLocal()
        try:
            # Si recibe un objeto Articulo, convertir a diccionario
            if hasattr(datos, '__dict__'):
                datos_dict = {
                    'articulonombre': getattr(datos, 'articulonombre', None),
                    'articulostock': getattr(datos, 'articulostock', 0),
                    'articulodescripcion': getattr(datos, 'articulodescripcion', None),
                    'articuloimagen': getattr(datos, 'articuloimagen', None),
                    'articulocodigogener': getattr(datos, 'articulocodigogener', None),
                    'articulocodigo': getattr(datos, 'articulocodigo', None),
                    'articulofecha': getattr(datos, 'articulofecha', None),
                    'articulohora': getattr(datos, 'articulohora', None),
                    'id_tipo': getattr(datos, 'id_tipo', None),
                    'id_proveedor': getattr(datos, 'id_proveedor', None)
                }
            else:
                datos_dict = datos
            
            print(f"Creando artículo con datos: {datos_dict}")
            
            # Campos de la tabla artículos (sin ID que es autoincremental)
            campos_permitidos = [
                'articulonombre', 'articulostock', 'articulodescripcion', 
                'articuloimagen', 'articulocodigogener', 'articulocodigo',
                'articulofecha', 'articulohora', 'id_tipo', 'id_proveedor'
            ]
            
            # Filtrar datos
            datos_bd = {}
            for campo in campos_permitidos:
                if campo in datos_dict and datos_dict[campo] is not None:
                    valor = datos_dict[campo]
                    
                    # Conversión de tipos especiales para SQLite
                    if campo == 'articulofecha':
                        if isinstance(valor, str):
                            try:
                                valor = datetime.fromisoformat(valor).date()
                                valor = valor.strftime('%Y-%m-%d')  # Convertir a string para SQLite
                            except:
                                valor = None
                        elif hasattr(valor, 'strftime'):  # Es un objeto date
                            valor = valor.strftime('%Y-%m-%d')
                    elif campo == 'articulohora':
                        if isinstance(valor, str):
                            try:
                                valor = datetime.fromisoformat(f"2000-01-01T{valor}").time()
                                valor = valor.strftime('%H:%M:%S')  # Convertir a string para SQLite
                            except:
                                valor = None
                        elif hasattr(valor, 'strftime'):  # Es un objeto time
                            valor = valor.strftime('%H:%M:%S')
                    
                    datos_bd[campo] = valor
            
            if not datos_bd:
                raise Exception("No hay datos válidos para insertar")
            
            # Construir query de inserción
            columnas = list(datos_bd.keys())
            columnas_str = ", ".join(columnas)
            valores_str = ", ".join([f":{col}" for col in columnas])
            
            query_insert = text(f"""
                INSERT INTO articulos ({columnas_str})
                VALUES ({valores_str})
            """)
            
            session.execute(query_insert, datos_bd)
            session.commit()
            
            # Obtener el registro creado
            query_select = text("""
                SELECT 
                    idarticulo, articulonombre, articulostock, articulodescripcion,
                    articuloimagen, articulocodigogener, articulocodigo,
                    articulofecha, articulohora, id_tipo, id_proveedor
                FROM articulos
                WHERE articulonombre = :nombre
                ORDER BY idarticulo DESC
                LIMIT 1
            """)
            
            resultado = session.execute(query_select, {"nombre": datos_bd.get('articulonombre')}).fetchone()
            
            if resultado:
                return {
                    'idarticulo': resultado[0],
                    'articulonombre': resultado[1],
                    'articulostock': resultado[2],
                    'articulodescripcion': resultado[3],
                    'articuloimagen': resultado[4],
                    'articulocodigogener': resultado[5],
                    'articulocodigo': resultado[6],
                    'articulofecha': resultado[7],
                    'articulohora': resultado[8],
                    'id_tipo': resultado[9],
                    'id_proveedor': resultado[10]
                }
            else:
                raise Exception("No se pudo recuperar el artículo creado")
            
        except Exception as e:
            session.rollback()
            print(f"Error al crear artículo: {e}")
            raise e
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        """Obtiene un artículo por ID usando SQL puro"""
        session = SessionLocal()
        try:
            query = text("""
                SELECT 
                    idarticulo, articulonombre, articulostock, articulodescripcion,
                    articuloimagen, articulocodigogener, articulocodigo,
                    articulofecha, articulohora, id_tipo, id_proveedor
                FROM articulos
                WHERE idarticulo = :id
            """)
            
            resultado = session.execute(query, {"id": id}).fetchone()
            
            if not resultado:
                return None
                
            return {
                'idarticulo': resultado[0],
                'articulonombre': resultado[1],
                'articulostock': resultado[2],
                'articulodescripcion': resultado[3],
                'articuloimagen': resultado[4],
                'articulocodigogener': resultado[5],
                'articulocodigo': resultado[6],
                'articulofecha': resultado[7],
                'articulohora': resultado[8],
                'id_tipo': resultado[9],
                'id_proveedor': resultado[10]
            }
            
        finally:
            session.close()

    def obtener_por_id_con_precios(self, id: int):
        """Obtiene un artículo con información de precios del último ingreso usando SQL puro"""
        session = SessionLocal()
        try:
            # Consulta SQL pura para obtener el artículo
            query_articulo = text("""
                SELECT 
                    idarticulo, articulonombre, articulostock, articulodescripcion,
                    articuloimagen, articulocodigogener, articulocodigo,
                    articulofecha, articulohora, id_tipo, id_proveedor
                FROM articulos 
                WHERE idarticulo = :articulo_id
            """)
            
            resultado_articulo = session.execute(query_articulo, {"articulo_id": id}).fetchone()
            
            if not resultado_articulo:
                return None
                
            # Convertir a diccionario
            articulo_dict = {
                'idarticulo': resultado_articulo[0],
                'articulonombre': resultado_articulo[1],
                'articulostock': resultado_articulo[2],
                'articulodescripcion': resultado_articulo[3],
                'articuloimagen': resultado_articulo[4],
                'articulocodigogener': resultado_articulo[5],
                'articulocodigo': resultado_articulo[6],
                'articulofecha': resultado_articulo[7],
                'articulohora': resultado_articulo[8],
                'id_tipo': resultado_articulo[9],
                'id_proveedor': resultado_articulo[10]
            }
            
            # Consulta para obtener el último precio de ingreso
            query_precio = text("""
                SELECT 
                    detalle_ingresoprecio_compra,
                    detalle_ingresoprecio_venta,
                    detalle_ingresocantidad
                FROM detalle_ingreso 
                WHERE idarticulo = :articulo_id 
                ORDER BY iddetalle_ingreso DESC 
                LIMIT 1
            """)
            
            resultado_precio = session.execute(query_precio, {"articulo_id": id}).fetchone()
            
            if resultado_precio:
                articulo_dict['ultimo_precio_compra'] = resultado_precio[0]
                articulo_dict['ultimo_precio_venta'] = resultado_precio[1]
                
                # Obtener todos los detalles de ingreso
                query_all = text("""
                    SELECT 
                        detalle_ingresoprecio_compra,
                        detalle_ingresoprecio_venta,
                        detalle_ingresocantidad
                    FROM detalle_ingreso 
                    WHERE idarticulo = :articulo_id 
                    ORDER BY iddetalle_ingreso DESC
                """)
                
                todos_detalles = session.execute(query_all, {"articulo_id": id}).fetchall()
                
                articulo_dict['detalles_ingreso'] = []
                for detalle in todos_detalles:
                    articulo_dict['detalles_ingreso'].append({
                        'detalle_ingresoprecio_compra': detalle[0],
                        'detalle_ingresoprecio_venta': detalle[1],
                        'detalle_ingresocantidad': detalle[2]
                    })
            else:
                articulo_dict['ultimo_precio_compra'] = None
                articulo_dict['ultimo_precio_venta'] = None
                articulo_dict['detalles_ingreso'] = []
            
            return articulo_dict
            
        finally:
            session.close()

    def actualizar(self, id: int, data: dict):
        """Actualiza un artículo usando SQL puro"""
        session = SessionLocal()
        try:
            # Verificar que existe
            existe = self.obtener_por_id(id)
            if not existe:
                return None
            
            print(f"Actualizando artículo {id} con datos: {data}")
            
            # Campos permitidos para actualizar
            campos_permitidos = [
                'articulonombre', 'articulostock', 'articulodescripcion', 
                'articuloimagen', 'articulocodigogener', 'articulocodigo',
                'articulofecha', 'articulohora', 'id_tipo', 'id_proveedor'
            ]
            
            datos_filtrados = {}
            for campo in campos_permitidos:
                if campo in data and data[campo] is not None:
                    valor = data[campo]
                    
                    # Conversión de tipos especiales para SQLite
                    if campo == 'articulofecha':
                        if isinstance(valor, str):
                            try:
                                valor = datetime.fromisoformat(valor).date()
                                valor = valor.strftime('%Y-%m-%d')  # Convertir a string para SQLite
                            except:
                                continue
                        elif hasattr(valor, 'strftime'):  # Es un objeto date
                            valor = valor.strftime('%Y-%m-%d')
                    elif campo == 'articulohora':
                        if isinstance(valor, str):
                            try:
                                valor = datetime.fromisoformat(f"2000-01-01T{valor}").time()
                                valor = valor.strftime('%H:%M:%S')  # Convertir a string para SQLite
                            except:
                                continue
                        elif hasattr(valor, 'strftime'):  # Es un objeto time
                            valor = valor.strftime('%H:%M:%S')
                    
                    datos_filtrados[campo] = valor
            
            if not datos_filtrados:
                return existe  
            set_clause = ", ".join([f"{campo} = :{campo}" for campo in datos_filtrados.keys()])
            
            query_update = text(f"""
                UPDATE articulos 
                SET {set_clause}
                WHERE idarticulo = :id
            """)
            
            params = {**datos_filtrados, "id": id}
            session.execute(query_update, params)
            session.commit()
            
            # Devolver el registro actualizado
            return self.obtener_por_id(id)
            
        except Exception as e:
            session.rollback()
            print(f"Error al actualizar artículo: {e}")
            raise e
        finally:
            session.close()

    def eliminar(self, id: int) -> bool:
        """Elimina un artículo usando SQL puro"""
        session = SessionLocal()
        try:
            # Verificar que existe
            existe = self.obtener_por_id(id)
            if not existe:
                return False
            
            query = text("DELETE FROM articulos WHERE idarticulo = :id")
            resultado = session.execute(query, {"id": id})
            
            session.commit()
            
            return resultado.rowcount > 0
            
        except Exception as e:
            session.rollback()
            print(f"Error al eliminar artículo: {e}")
            return False
        finally:
            session.close()