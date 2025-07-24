from core.interfaces.rol_repository import RolRepositoryInterface
class ListarRolHandler:
    def __init__(self, repo: RolRepositoryInterface):
        self.repo = repo

    def handle(self):
        roles = self.repo.listar_todos()
        return [
            {
                "id": r.idrol,
                "nombre": r.rolnombre,
                "condicion": r.rolcondicion
            } for r in roles
        ]
