from fastapi import APIRouter
from core.handlers.proveedor.eliminar_proveedor_handler import EliminarProveedorHandler
from infrastructure.data.proveedor_repository_impl import ProveedorRepositoryImpl

router = APIRouter()

@router.delete("/proveedores/{id}")
def eliminar_proveedor(id: int):
    handler = EliminarProveedorHandler(ProveedorRepositoryImpl())
    resultado = handler.handle(id)
    return {"eliminado": resultado}
