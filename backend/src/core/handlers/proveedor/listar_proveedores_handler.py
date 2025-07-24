from core.interfaces.proveedor_repository import ProveedorRepositoryInterface


class ListarProveedoresHandler:
    def __init__(self, repo: ProveedorRepositoryInterface):
        self.repo = repo

    def handle(self):
        proveedores = self.repo.listar_todos()
        return [
            {
                "id": p.id,
                "nombre": p.nombre,
                "contacto": p.contacto,
                "direccion": p.direccion
            } for p in proveedores
        ]
