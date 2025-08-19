# infrastructure/data/proveedor_repository_impl.py (versión SQL puro)
from infrastructure.data.AppDbContext import SessionLocal
from sqlalchemy import text
from typing import List, Dict, Any

class ProveedorRepositoryImpl:
    def listar_todos(self) -> List[Dict[str, Any]]:
        """Lista todos los proveedores usando SQL puro"""
        session = SessionLocal()
        try:
            # Detectar estructura de la tabla proveedores (PLURAL)
            query_structure = text("PRAGMA table_info(proveedores)")
            columnas = session.execute(query_structure).fetchall()
            
            print("Estructura de la tabla proveedores:")
            nombres_columnas = []
            for columna in columnas:
                print(f"  - {columna[1]} ({columna[2]})")
                nombres_columnas.append(columna[1])
            
            if len(nombres_columnas) < 2:
                return []
            
            # Construir query dinámicamente con todas las columnas
            columnas_str = ", ".join(nombres_columnas)
            
            query = text(f"""
                SELECT {columnas_str}
                FROM proveedores
                ORDER BY {nombres_columnas[0]}
            """)
            
            resultados = session.execute(query).fetchall()
            
            proveedores_lista = []
            for proveedor in resultados:
                # Crear diccionario dinámicamente
                proveedor_dict = {}
                for i, nombre_columna in enumerate(nombres_columnas):
                    proveedor_dict[nombre_columna] = proveedor[i]
                
                proveedores_lista.append(proveedor_dict)
            
            return proveedores_lista
            
        except Exception as e:
            print(f"Error al listar proveedores: {e}")
            return []
        finally:
            session.close()

    def crear(self, datos: dict):
        """Crea un nuevo proveedor"""
        session = SessionLocal()
        try:
            # Tu tabla es "proveedores" y tiene: id, nombre, contacto, direccion
            print(f"Datos recibidos: {datos}")
            
            # Mapear datos del DTO a las columnas exactas de tu modelo
            datos_bd = {}
            if 'nombre' in datos:
                datos_bd['nombre'] = datos['nombre']
            if 'contacto' in datos:
                datos_bd['contacto'] = datos['contacto']  
            if 'direccion' in datos:
                datos_bd['direccion'] = datos['direccion']
            
            print(f"Datos para BD: {datos_bd}")
            
            if not datos_bd:
                raise Exception("No hay datos válidos para insertar")
            
            # Construir query de inserción para tu tabla específica
            columnas = list(datos_bd.keys())
            columnas_str = ", ".join(columnas)
            valores_str = ", ".join([f":{col}" for col in columnas])
            
            query_insert = text(f"""
                INSERT INTO proveedores ({columnas_str})
                VALUES ({valores_str})
            """)
            
            print(f"Query: INSERT INTO proveedores ({columnas_str}) VALUES ({valores_str})")
            print(f"Parámetros: {datos_bd}")
            
            session.execute(query_insert, datos_bd)
            session.commit()
            
            # Obtener el registro creado por nombre (único identificador disponible)
            query_select = text("""
                SELECT id, nombre, contacto, direccion
                FROM proveedores
                WHERE nombre = :nombre
                ORDER BY id DESC
                LIMIT 1
            """)
            
            resultado = session.execute(query_select, {"nombre": datos_bd['nombre']}).fetchone()
            
            if resultado:
                return {
                    'id': resultado[0],
                    'nombre': resultado[1],
                    'contacto': resultado[2],
                    'direccion': resultado[3]
                }
            else:
                raise Exception("No se pudo recuperar el proveedor creado")
            
        except Exception as e:
            session.rollback()
            print(f"Error al crear proveedor: {e}")
            raise e
        finally:
            session.close()

    def obtener_por_id(self, id: int):
        """Obtiene un proveedor por ID"""
        session = SessionLocal()
        try:
            query = text("""
                SELECT id, nombre, contacto, direccion
                FROM proveedores
                WHERE id = :id
            """)
            
            resultado = session.execute(query, {"id": id}).fetchone()
            
            if not resultado:
                return None
                
            return {
                'id': resultado[0],
                'nombre': resultado[1],
                'contacto': resultado[2],
                'direccion': resultado[3]
            }
            
        finally:
            session.close()

    def actualizar(self, id: int, datos: dict):
        """Actualiza un proveedor"""
        session = SessionLocal()
        try:
            # Verificar que existe
            existe = self.obtener_por_id(id)
            if not existe:
                return None
            
            print(f"Actualizando proveedor {id} con datos: {datos}")
            
            # Filtrar solo los campos que existen en la tabla
            campos_validos = ['nombre', 'contacto', 'direccion']
            datos_filtrados = {k: v for k, v in datos.items() if k in campos_validos and v is not None}
            
            if not datos_filtrados:
                print("No hay datos válidos para actualizar")
                return existe  # No hay nada que actualizar
            
            # Construir query de actualización
            set_clause = ", ".join([f"{campo} = :{campo}" for campo in datos_filtrados.keys()])
            
            query_update = text(f"""
                UPDATE proveedores 
                SET {set_clause}
                WHERE id = :id
            """)
            
            params = {**datos_filtrados, "id": id}
            print(f"Query: UPDATE proveedores SET {set_clause} WHERE id = :id")
            print(f"Parámetros: {params}")
            
            session.execute(query_update, params)
            session.commit()
            
            # Devolver el registro actualizado
            return self.obtener_por_id(id)
            
        except Exception as e:
            session.rollback()
            print(f"Error al actualizar proveedor: {e}")
            raise e
        finally:
            session.close()

    def eliminar(self, id: int) -> bool:
        """Elimina un proveedor"""
        session = SessionLocal()
        try:
            # Verificar que existe
            existe = self.obtener_por_id(id)
            if not existe:
                return False
            
            query = text("DELETE FROM proveedores WHERE id = :id")
            resultado = session.execute(query, {"id": id})
            
            session.commit()
            
            return resultado.rowcount > 0
            
        except Exception as e:
            session.rollback()
            print(f"Error al eliminar proveedor: {e}")
            return False
        finally:
            session.close()