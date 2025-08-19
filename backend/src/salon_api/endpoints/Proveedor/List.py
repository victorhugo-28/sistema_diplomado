from fastapi import APIRouter
from infrastructure.data.proveedor_repository_impl import ProveedorRepositoryImpl

router = APIRouter()

@router.get("/proveedores")
def listar_proveedores():
    repo = ProveedorRepositoryImpl()
    return repo.listar_todos()