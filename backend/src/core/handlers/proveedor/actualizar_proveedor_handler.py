# core/handlers/proveedor/actualizar_proveedor_handler.py
from core.interfaces.proveedor_repository import ProveedorRepositoryInterface
from core.dto.proveedor_dto import ActualizarProveedorDTO

class ActualizarProveedorHandler:
    def __init__(self, repo: ProveedorRepositoryInterface):
        self.repo = repo

    def handle(self, id: int, data: ActualizarProveedorDTO):
        # PUT: requiere los 3 campos
        return self.repo.actualizar(id, data.nombre, data.contacto, data.direccion)
