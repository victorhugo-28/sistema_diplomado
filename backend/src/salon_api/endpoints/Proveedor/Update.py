from fastapi import APIRouter
from core.handlers.proveedor.actualizar_proveedor_handler import ActualizarProveedorHandler
from infrastructure.data.proveedor_repository_impl import ProveedorRepositoryImpl

router = APIRouter()

@router.put("/proveedores/{id}")
def actualizar_proveedor(id: int, data: dict):
    handler = ActualizarProveedorHandler(ProveedorRepositoryImpl())
    proveedor = handler.handle(id, data)
    if not proveedor:
        return {"mensaje": "Proveedor no encontrado"}
    return proveedor
