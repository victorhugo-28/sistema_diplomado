from core.interfaces.cliente_repository import ClienteRepositoryInterface   
class EliminarClienteHandler:
    def __init__(self, repo: ClienteRepositoryInterface):
        self.repo = repo

    def handle(self, id: int):
        return self.repo.eliminar(id)
