from core.interfaces.cliente_repository import ClienteRepositoryInterface   
class ListarClienteHandler:
    def __init__(self, repo: ClienteRepositoryInterface):
        self.repo = repo

    def handle(self):
        clientes = self.repo.listar_todos()
        return [{"id": c.id, "nombre": c.nombre, "email": c.email, "telefono": c.telefono} for c in clientes]
