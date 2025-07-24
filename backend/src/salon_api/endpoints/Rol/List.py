from fastapi import APIRouter
from core.handlers.rol.listar_rol_handler import ListarRolHandler
from infrastructure.data.rol_repository_impl import RolRepositoryImpl

router = APIRouter()

@router.get("/roles")
def listar_roles():
    handler = ListarRolHandler(RolRepositoryImpl())
    return handler.handle()
