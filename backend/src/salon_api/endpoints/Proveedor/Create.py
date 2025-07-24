from fastapi import APIRouter
from core.dto.proveedor_dto import CrearProveedorDTO
from core.handlers.proveedor.crear_proveedor_handler import CrearProveedorHandler
from infrastructure.data.proveedor_repository_impl import ProveedorRepositoryImpl

router = APIRouter()

@router.post("/proveedores")
def crear_proveedor(data: CrearProveedorDTO):
    handler = CrearProveedorHandler(ProveedorRepositoryImpl())
    proveedor = handler.handle(data)
    return {
        "id": proveedor.id,
        "nombre": proveedor.nombre,
        "contacto": proveedor.contacto,
        "direccion": proveedor.direccion
    }
