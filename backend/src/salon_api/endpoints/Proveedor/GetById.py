# salon_api/endpoints/Proveedor/GetById.py
from fastapi import APIRouter, HTTPException
from infrastructure.data.proveedor_repository_impl import ProveedorRepositoryImpl

router = APIRouter()

@router.get("/proveedores/{id}")
def obtener_proveedor(id: int):
    repo = ProveedorRepositoryImpl()
    proveedor = repo.obtener_por_id(id)
    
    if not proveedor:
        raise HTTPException(status_code=404, detail="Proveedor no encontrado")
    
    return proveedor