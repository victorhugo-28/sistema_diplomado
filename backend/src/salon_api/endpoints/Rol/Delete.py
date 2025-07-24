from fastapi import APIRouter
from core.handlers.rol.eliminar_rol_handler import EliminarRolHandler
from infrastructure.data.rol_repository_impl import RolRepositoryImpl

router = APIRouter()

@router.delete("/roles/{id}")
def eliminar_rol(id: int):
    handler = EliminarRolHandler(RolRepositoryImpl())
    eliminado = handler.handle(id)
    return {"eliminado": eliminado}
