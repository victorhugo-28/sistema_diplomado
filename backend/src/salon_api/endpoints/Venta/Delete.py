# salon_api/endpoints/Venta/Delete.py (corregido)
from fastapi import APIRouter, HTTPException
from infrastructure.data.venta_repository_impl import VentaRepositoryImpl

router = APIRouter()

@router.delete("/ventas/{id}")
def eliminar_venta(id: int):
    repo = VentaRepositoryImpl()
    eliminado = repo.eliminar(id)
    
    if not eliminado:
        raise HTTPException(status_code=404, detail=f"Venta con ID {id} no encontrada")
    
    return {"message": f"Venta {id} eliminada exitosamente", "success": True}