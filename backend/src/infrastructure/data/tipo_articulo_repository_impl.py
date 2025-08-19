# infrastructure/data/tipo_articulo_repository_impl.py (versión sin relaciones)
from infrastructure.data.AppDbContext import SessionLocal
from sqlalchemy import text
from typing import List, Dict, Any

class TipoArticuloRepositoryImpl:
    def listar_todos(self) -> List[Dict[str, Any]]:
        """Lista todos los tipos de artículo usando SQL puro"""
        session = SessionLocal()
        try:
            # Verificar la estructura de la tabla
            query_structure = text("PRAGMA table_info(tipos_articulo)")
            columnas = session.execute(query_structure).fetchall()
            
            print("Estructura de la tabla tipos_articulo:")
            nombres_columnas = []
            for columna in columnas:
                print(f"  - {columna[1]} ({columna[2]})")
                nombres_columnas.append(columna[1])
            
            if len(nombres_columnas) < 2:
                print("Error: La tabla necesita al menos 2 columnas (id y nombre)")
                return []
            
            # Usar las primeras dos columnas (generalmente ID y nombre)
            columna_id = nombres_columnas[0]
            columna_nombre = nombres_columnas[1]
            
            print(f"Usando columnas: {columna_id}, {columna_nombre}")
            
            # Consulta dinámica basada en la estructura real
            query_final = text(f"""
                SELECT {columna_id}, {columna_nombre}
                FROM tipos_articulo
                ORDER BY {columna_id}
            """)
            
            resultados = session.execute(query_final).fetchall()
            
            tipos_lista = []
            for tipo in resultados:
                tipo_dict = {
                    'id': tipo[0],           # Tu DTO usa 'id'
                    'nombre': tipo[1]        # Tu DTO usa 'nombre'
                }
                tipos_lista.append(tipo_dict)
            
            return tipos_lista
            
        except Exception as e:
            print(f"Error general: {e}")
            return []
        finally:
            session.close()

    def crear(self, datos: dict):
        """Crea un nuevo tipo de artículo"""
        session = SessionLocal()
        try:
            # Primero verificar la estructura de la tabla
            query_structure = text("PRAGMA table_info(tipos_articulo)")
            columnas = session.execute(query_structure).fetchall()
            
            print("Estructura de la tabla tipos_articulo:")
            nombres_columnas = []
            for columna in columnas:
                print(f"  - {columna[1]} ({columna[2]})")  # nombre y tipo
                nombres_columnas.append(columna[1])
            
            # Detectar los nombres correctos de las columnas
            columna_id = None
            columna_nombre = None
            columna_descripcion = None
            
            for nombre in nombres_columnas:
                nombre_lower = nombre.lower()
                if 'id' in nombre_lower and ('tipo' in nombre_lower or 'articulo' in nombre_lower):
                    columna_id = nombre
                elif 'nombre' in nombre_lower:
                    columna_nombre = nombre
                elif 'descripcion' in nombre_lower or 'desc' in nombre_lower:
                    columna_descripcion = nombre
            
            print(f"Columnas detectadas: ID={columna_id}, Nombre={columna_nombre}, Descripcion={columna_descripcion}")
            
            if not columna_nombre:
                # Si no encontramos columna de nombre, usar la segunda columna (después del ID)
                if len(nombres_columnas) >= 2:
                    columna_nombre = nombres_columnas[1]
                    print(f"Usando columna por posición: {columna_nombre}")
            
            if not columna_nombre:
                raise Exception(f"No se pudo detectar la columna de nombre. Columnas disponibles: {nombres_columnas}")
            
            # Construir query dinámicamente
            if columna_descripcion and len(nombres_columnas) >= 3:
                # Tabla con descripción
                query_insert = text(f"""
                    INSERT INTO tipos_articulo ({columna_nombre}, {columna_descripcion})
                    VALUES (:nombre, :descripcion)
                """)
                params = {
                    "nombre": datos.get('tipo_articulonombre'),
                    "descripcion": datos.get('tipo_articulodescripcion')
                }
            else:
                # Tabla solo con nombre
                query_insert = text(f"""
                    INSERT INTO tipos_articulo ({columna_nombre})
                    VALUES (:nombre)
                """)
                params = {
                    "nombre": datos.get('tipo_articulonombre')
                }
            
            print(f"Ejecutando query: {query_insert}")
            print(f"Con parámetros: {params}")
            
            session.execute(query_insert, params)
            session.commit()
            
            # Obtener el registro recién creado
            query_select = text(f"""
                SELECT {columna_id or nombres_columnas[0]}, {columna_nombre}
                FROM tipos_articulo
                WHERE {columna_nombre} = :nombre
                ORDER BY {columna_id or nombres_columnas[0]} DESC
                LIMIT 1
            """)
            
            resultado = session.execute(query_select, {
                "nombre": datos.get('tipo_articulonombre')
            }).fetchone()
            
            if not resultado:
                raise Exception("No se pudo crear el tipo de artículo")
            
            return {
                'idtipo_articulo': resultado[0],
                'tipo_articulonombre': resultado[1]
            }
            
        except Exception as e:
            session.rollback()
            print(f"Error al crear tipo artículo: {e}")
            raise e
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        """Obtiene un tipo de artículo por ID"""
        session = SessionLocal()
        try:
            query = text("""
                SELECT 
                    id,
                    nombre
                FROM tipos_articulo
                WHERE id = :id
            """)
            
            resultado = session.execute(query, {"id": id}).fetchone()
            
            if not resultado:
                return None
                
            return {
                'id': resultado[0],
                'nombre': resultado[1]
            }
            
        finally:
            session.close()

    def actualizar(self, id: int, nombre: str):
        """
        Actualiza un tipo de artículo
        
        Args:
            id (int): ID del tipo de artículo
            nombre (str): Nuevo nombre (viene como string desde el handler)
        """
        session = SessionLocal()
        try:
            print(f"Actualizando tipo artículo ID: {id} con nombre: '{nombre}'")
            
            # Verificar que existe
            existe = self.obtener_por_id(id)
            if not existe:
                print(f"No se encontró tipo artículo con ID: {id}")
                return None
            
            # Actualizar usando SQLite (no soporta RETURNING)
            query_update = text("""
                UPDATE tipos_articulo
                SET nombre = :nombre
                WHERE id = :id
            """)
            
            resultado = session.execute(query_update, {
                "id": id,
                "nombre": nombre  # Aquí usamos directamente el string
            })
            
            session.commit()
            
            # Verificar que se actualizó
            if resultado.rowcount == 0:
                print(f"No se actualizó ningún registro para ID: {id}")
                return None
            
            # Obtener el registro actualizado
            query_select = text("""
                SELECT id, nombre
                FROM tipos_articulo
                WHERE id = :id
            """)
            
            registro_actualizado = session.execute(query_select, {"id": id}).fetchone()
            
            if not registro_actualizado:
                print(f"Error: No se pudo recuperar el registro actualizado")
                return None
            
            print(f"Tipo artículo actualizado exitosamente: {registro_actualizado}")
            
            return {
                'id': registro_actualizado[0],
                'nombre': registro_actualizado[1]
            }
            
        except Exception as e:
            session.rollback()
            print(f"Error al actualizar tipo artículo: {e}")
            raise e
        finally:
            session.close()

    def eliminar(self, id: int) -> bool:
        """Elimina un tipo de artículo"""
        session = SessionLocal()
        try:
            # Verificar que existe
            existe = self.obtener_por_id(id)
            if not existe:
                print(f"No se encontró tipo artículo con ID: {id}")
                return False
            
            query = text("DELETE FROM tipos_articulo WHERE id = :id")
            resultado = session.execute(query, {"id": id})
            
            session.commit()
            
            exito = resultado.rowcount > 0
            print(f"Eliminación {'exitosa' if exito else 'falló'} para ID: {id}")
            
            return exito
            
        except Exception as e:
            session.rollback()
            print(f"Error al eliminar tipo artículo: {e}")
            return False
        finally:
            session.close()