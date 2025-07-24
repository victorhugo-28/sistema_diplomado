from core.dto.proveedor_dto import CrearProveedorDTO
from core.models.proveedor import Proveedor
from core.interfaces.proveedor_repository import ProveedorRepositoryInterface

class CrearProveedorHandler:
    def __init__(self, repo: ProveedorRepositoryInterface):
        self.repo = repo

    def handle(self, data: CrearProveedorDTO):
        proveedor = Proveedor(
            nombre=data.nombre,
            contacto=data.contacto,
            direccion=data.direccion
        )
        return self.repo.crear(proveedor)
