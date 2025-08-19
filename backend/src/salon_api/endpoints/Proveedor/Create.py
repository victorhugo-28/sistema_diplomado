from fastapi import APIRouter , HTTPException
from core.dto.proveedor_dto import CrearProveedorDTO
from core.handlers.proveedor.crear_proveedor_handler import CrearProveedorHandler
from infrastructure.data.proveedor_repository_impl import ProveedorRepositoryImpl

router = APIRouter()

@router.post("/proveedores")
def crear_proveedor(data: CrearProveedorDTO):
    repo = ProveedorRepositoryImpl()
    try:
        # Convertir a diccionario, excluyendo valores None
        datos = {k: v for k, v in data.dict().items() if v is not None}
        
        print(f"Datos del DTO: {datos}")  # Debug
        
        proveedor_creado = repo.crear(datos)
        
        if not proveedor_creado:
            raise HTTPException(status_code=400, detail="Error al crear el proveedor")
        
        return proveedor_creado
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error interno: {str(e)}")