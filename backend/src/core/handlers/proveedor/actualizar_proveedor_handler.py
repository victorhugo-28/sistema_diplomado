from core.dto.proveedor_dto import ActualizarProveedorDTO
from core.models.proveedor import Proveedor
from core.interfaces.proveedor_repository import ProveedorRepositoryInterface


class ActualizarProveedorHandler:
    def __init__(self, repo: ProveedorRepositoryInterface):
        self.repo = repo

    def handle(self, id: int, data: ActualizarProveedorDTO):
        datos = self.repo.actualizar(id, data.nombre, data.contacto, data.direccion)
        if not datos:
            return {"error": "No se pudo actualizar el proveedor"}
        return {"mensaje": "Proveedor actualizado con éxito"}
