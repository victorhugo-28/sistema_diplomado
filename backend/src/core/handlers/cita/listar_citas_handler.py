from core.interfaces.cita_repository import CitaRepositoryInterface

class ListarCitasHandler:
    def __init__(self, repo):
        self.repo = repo

    def handle(self):
        return [
            {
                "id": cita.id,
                "fecha": cita.fecha.isoformat(),
                "descripcion": cita.descripcion,
                "usuario_id": cita.usuario_id
            }
            for cita in self.repo.listar_todas()
        ]
