from fastapi import APIRouter
from core.dto.rol_dto import CrearRolDTO
from core.handlers.rol.crear_rol_handler import CrearRolHandler
from infrastructure.data.rol_repository_impl import RolRepositoryImpl

router = APIRouter()

@router.post("/roles")
def crear_rol(data: CrearRolDTO):
    handler = CrearRolHandler(RolRepositoryImpl())
    rol = handler.handle(data)
    return {
        "id": rol.idrol,
        "nombre": rol.rolnombre,
        "condicion": rol.rolcondicion
    }
