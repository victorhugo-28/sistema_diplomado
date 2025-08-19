from fastapi import APIRouter, HTTPException
from core.dto.proveedor_dto import ActualizarProveedorDTO
from core.handlers.proveedor.actualizar_proveedor_handler import ActualizarProveedorHandler
from infrastructure.data.proveedor_repository_impl import ProveedorRepositoryImpl

router = APIRouter()

@router.put("/proveedores/{id}")
def actualizar_proveedor(id: int, data: ActualizarProveedorDTO):
    repo = ProveedorRepositoryImpl()
    try:
        # Convertir a diccionario, excluyendo valores None
        datos = {k: v for k, v in data.dict().items() if v is not None}
        
        print(f"Actualizando proveedor {id} con datos: {datos}")  # Debug
        
        proveedor_actualizado = repo.actualizar(id, datos)
        
        if not proveedor_actualizado:
            raise HTTPException(status_code=404, detail="Proveedor no encontrado")
        
        return proveedor_actualizado
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error interno: {str(e)}")