from fastapi import APIRouter
from core.handlers.rol.actualizar_rol_handler import ActualizarRolHandler
from infrastructure.data.rol_repository_impl import RolRepositoryImpl

router = APIRouter()

@router.put("/roles/{id}")
def actualizar_rol(id: int, data: dict):
    handler = ActualizarRolHandler(RolRepositoryImpl())
    rol_actualizado = handler.handle(id, data)
    if rol_actualizado is None:
        return {"mensaje": "Rol no encontrado"}
    return {
        "id": rol_actualizado.idrol,
        "nombre": rol_actualizado.rolnombre,
        "condicion": rol_actualizado.rolcondicion
    }
