from fastapi import APIRouter, HTTPException
from core.handlers.proveedor.eliminar_proveedor_handler import EliminarProveedorHandler
from infrastructure.data.proveedor_repository_impl import ProveedorRepositoryImpl

router = APIRouter()

@router.delete("/proveedores/{id}")
def eliminar_proveedor(id: int):
    repo = ProveedorRepositoryImpl()
    eliminado = repo.eliminar(id)
    
    if not eliminado:
        raise HTTPException(status_code=404, detail=f"Proveedor con ID {id} no encontrado")
    
    return {"message": f"Proveedor {id} eliminado exitosamente", "success": True}