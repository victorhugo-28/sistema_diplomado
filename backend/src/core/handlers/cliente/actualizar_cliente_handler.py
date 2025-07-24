from core.interfaces.cliente_repository import ClienteRepositoryInterface   
class ActualizarClienteHandler:
    def __init__(self, repo: ClienteRepositoryInterface):
        self.repo = repo

    def handle(self, id: int, data: dict):
        return self.repo.actualizar(id, data)
