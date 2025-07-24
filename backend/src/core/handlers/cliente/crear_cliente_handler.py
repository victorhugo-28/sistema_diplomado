from core.models.cliente import Cliente

class CrearClienteHandler:
    def __init__(self, repo):
        self.repo = repo

    def handle(self, data):
        cliente = Cliente(**data.dict())
        return self.repo.crear(cliente)
