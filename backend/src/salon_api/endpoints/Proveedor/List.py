from fastapi import APIRouter
from core.handlers.proveedor.listar_proveedores_handler import ListarProveedoresHandler
from infrastructure.data.proveedor_repository_impl import ProveedorRepositoryImpl

router = APIRouter()

@router.get("/proveedores")
def listar_proveedores():
    handler = ListarProveedoresHandler(ProveedorRepositoryImpl())
    return handler.handle()
