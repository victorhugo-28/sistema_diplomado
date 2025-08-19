# salon_api/endpoints/Articulo/List.py (REEMPLAZAR tu archivo actual)
from fastapi import APIRouter
from infrastructure.data.articulo_repository_impl import ArticuloRepositoryImpl

router = APIRouter()

@router.get("/articulos")
def listar_articulos():
    # Usar repositorio directamente (sin handler)
    repo = ArticuloRepositoryImpl()
    articulos = repo.listar_todos()
    
    # El repositorio ya devuelve diccionarios con el formato correcto
    # Opcional: formatear la respuesta si quieres un formato específico
    articulos_formateados = []
    for articulo in articulos:
        articulo_formateado = {
            "id": articulo['idarticulo'],
            "nombre": articulo['articulonombre'],
            "stock": articulo['articulostock'],
            "descripcion": articulo['articulodescripcion'],
            "imagen": articulo['articuloimagen'],
            "codigo_gener": articulo['articulocodigogener'],
            "codigo": articulo['articulocodigo'],
            "fecha": articulo['articulofecha'],
            "hora": articulo['articulohora'],
            "id_tipo": articulo['id_tipo'],
            "id_proveedor": articulo['id_proveedor']
        }
        articulos_formateados.append(articulo_formateado)
    
    return articulos_formateados