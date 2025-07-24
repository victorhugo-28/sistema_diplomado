from core.models.rol import Rol

class CrearRolHandler:
    def __init__(self, repo):
        self.repo = repo

    def handle(self, data):
        rol = Rol(**data.dict())
        return self.repo.crear(rol)
