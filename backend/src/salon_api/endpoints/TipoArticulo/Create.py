# salon_api/endpoints/TipoArticulo/Create.py (tu versión actual - corregida)
from fastapi import APIRouter, HTTPException
from core.dto.tipo_articulo_dto import CrearTipoArticuloDTO
from infrastructure.data.tipo_articulo_repository_impl import TipoArticuloRepositoryImpl

router = APIRouter()

@router.post("/tipos_articulo")
def crear_tipo_articulo(data: CrearTipoArticuloDTO):
    # Usar el repositorio directamente en lugar del handler problemático
    repo = TipoArticuloRepositoryImpl()
    
    try:
        # Convertir el DTO a diccionario
        datos = {
            'tipo_articulonombre': data.nombre,
        }
        
        tipo_creado = repo.crear(datos)
        
        if not tipo_creado:
            raise HTTPException(status_code=400, detail="Error al crear el tipo de artículo")
        
        # Mantener el formato de respuesta que tenías
        return {
            "id": tipo_creado['idtipo_articulo'],
            "nombre": tipo_creado['tipo_articulonombre']
        }
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error interno: {str(e)}")